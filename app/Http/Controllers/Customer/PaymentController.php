<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Payment;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Halaman pembayaran — generate Snap token & tampilkan popup
     */
    public function pay($id)
    {
        $reservation = Reservation::with(['meetingRoom', 'foodPackage', 'promotion', 'payment'])->findOrFail($id);
        $payment = $reservation->payment;

        if (!$payment) {
            return redirect()->route('reservation.confirmation', $id)->with('error', 'Data pembayaran tidak ditemukan.');
        }

        // Jika sudah sukses, langsung ke invoice
        if ($payment->payment_status === 'sukses') {
            return redirect()->route('reservation.invoice', $id);
        }

        // Cek apakah sudah expired (20 menit)
        $expiresAt = $reservation->created_at->copy()->addMinutes(20);
        if (now()->greaterThanOrEqualTo($expiresAt) && $payment->payment_status === 'pending') {
            // Hapus data reservasi yang expired
            $reservation->buffetSelections()->delete();
            $reservation->payment()->delete();
            $reservation->delete();

            return redirect()->route('home')->with('error', 'Waktu pembayaran telah habis (20 menit). Reservasi dibatalkan secara otomatis. Silakan buat reservasi baru.');
        }

        // Hitung total setelah diskon
        $discountPercent = $reservation->promotion?->discount ?? 0;
        $discountAmount = ($reservation->total_price * $discountPercent) / 100;
        $grossAmount = (int) ($reservation->total_price - $discountAmount);

        // Cek apakah sudah punya snap_token yang valid
        if (!$payment->snap_token) {
            // Generate Snap token baru
            $orderId = 'AMAZE-' . $reservation->id . '-' . time();

            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => $grossAmount,
                ],
                'item_details' => [
                    [
                        'id' => 'room-' . $reservation->meeting_room_id,
                        'price' => $grossAmount,
                        'quantity' => 1,
                        'name' => substr('Meeting: ' . $reservation->meetingRoom->name . ' - ' . $reservation->foodPackage->name, 0, 50),
                    ],
                ],
                'customer_details' => [
                    'first_name' => $reservation->customer_name,
                    'phone' => $reservation->phone,
                ],
            ];

            try {
                $snapToken = Snap::getSnapToken($params);

                $payment->update([
                    'snap_token' => $snapToken,
                    'gross_amount' => $grossAmount,
                    'transaction_id' => $orderId,
                ]);
            } catch (\Exception $e) {
                return redirect()->route('reservation.confirmation', $id)
                    ->with('error', 'Gagal menghubungi payment gateway: ' . $e->getMessage());
            }
        }

        $clientKey = config('midtrans.client_key');
        $isProduction = config('midtrans.is_production');

        return view('customer.payment', compact('reservation', 'payment', 'grossAmount', 'clientKey', 'isProduction'));
    }

    /**
     * Callback dari Midtrans server notification (webhook)
     */
    public function callback(Request $request)
    {
        \Log::info('Midtrans Callback Received', [
            'all_data' => $request->all(),
            'raw_body' => $request->getContent(),
        ]);

        try {
            // Coba parse dari Midtrans Notification class
            $notification = new Notification();

            $transactionStatus = $notification->transaction_status;
            $orderId = $notification->order_id;
            $paymentType = $notification->payment_type;
            $fraudStatus = $notification->fraud_status ?? null;
        } catch (\Exception $e) {
            // Fallback: parse langsung dari request body
            \Log::warning('Midtrans Notification class failed, using raw request', ['error' => $e->getMessage()]);

            $body = json_decode($request->getContent(), true);
            if (!$body) {
                $body = $request->all();
            }

            $transactionStatus = $body['transaction_status'] ?? null;
            $orderId = $body['order_id'] ?? null;
            $paymentType = $body['payment_type'] ?? 'midtrans';
            $fraudStatus = $body['fraud_status'] ?? null;
        }

        \Log::info('Midtrans Parsed Data', [
            'order_id' => $orderId,
            'transaction_status' => $transactionStatus,
            'payment_type' => $paymentType,
            'fraud_status' => $fraudStatus,
        ]);

        if (!$orderId) {
            return response()->json(['message' => 'No order_id'], 400);
        }

        // Cari payment berdasarkan transaction_id (order_id)
        $payment = Payment::where('transaction_id', $orderId)->first();

        if (!$payment) {
            \Log::warning('Payment not found for order_id: ' . $orderId);
            return response()->json(['message' => 'Payment not found'], 404);
        }

        $reservation = $payment->reservation;

        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
            if ($fraudStatus == 'accept' || $fraudStatus === null) {
                $payment->update([
                    'payment_status' => 'sukses',
                    'payment_method' => $paymentType,
                ]);
                $reservation->update(['status' => 'sukses']);
                \Log::info('Payment SUKSES for order_id: ' . $orderId);
            }
        } elseif ($transactionStatus == 'pending') {
            $payment->update([
                'payment_status' => 'pending',
                'payment_method' => $paymentType,
            ]);
            \Log::info('Payment PENDING for order_id: ' . $orderId);
        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            $payment->update([
                'payment_status' => 'gagal',
                'payment_method' => $paymentType,
            ]);
            $reservation->update(['status' => 'gagal']);
            \Log::info('Payment GAGAL for order_id: ' . $orderId);
        }

        return response()->json(['message' => 'OK']);
    }

    /**
     * Redirect setelah user selesai di popup Snap
     */
    public function finish(Request $request)
    {
        $orderId = $request->get('order_id');
        $snapStatus = $request->get('snap_status');
        $transactionStatusParam = $request->get('transaction_status');

        \Log::info('Payment Finish Called', [
            'order_id' => $orderId,
            'snap_status' => $snapStatus,
            'transaction_status_param' => $transactionStatusParam,
            'all_params' => $request->all(),
        ]);

        if ($orderId) {
            $payment = Payment::where('transaction_id', $orderId)->first();

            if ($payment) {
                $reservation = $payment->reservation;

                // Jika sudah sukses, langsung ke invoice
                if ($payment->payment_status === 'sukses') {
                    return redirect()->route('reservation.invoice', $payment->reservation_id);
                }

                // Cek status dari Midtrans API
                $updated = false;
                try {
                    $status = \Midtrans\Transaction::status($orderId);
                    $transactionStatus = $status->transaction_status ?? null;
                    $paymentType = $status->payment_type ?? $request->get('payment_type', 'midtrans');

                    \Log::info('Midtrans API Status Check', [
                        'order_id' => $orderId,
                        'api_status' => $transactionStatus,
                    ]);

                    if (in_array($transactionStatus, ['capture', 'settlement'])) {
                        $payment->update([
                            'payment_status' => 'sukses',
                            'payment_method' => $paymentType,
                        ]);
                        $reservation->update(['status' => 'sukses']);
                        $updated = true;
                        \Log::info('Finish: Set SUKSES via Midtrans API');
                    } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                        $payment->update([
                            'payment_status' => 'gagal',
                            'payment_method' => $paymentType,
                        ]);
                        $reservation->update(['status' => 'gagal']);
                        $updated = true;
                        \Log::info('Finish: Set GAGAL via Midtrans API');
                    }
                } catch (\Exception $e) {
                    \Log::warning('Finish: Midtrans API failed', ['error' => $e->getMessage()]);
                }

                // Fallback 1: snap_status dari Snap popup onSuccess
                if (!$updated && $snapStatus === 'success') {
                    $payment->update([
                        'payment_status' => 'sukses',
                        'payment_method' => $request->get('payment_type', 'midtrans'),
                    ]);
                    $reservation->update(['status' => 'sukses']);
                    $updated = true;
                    \Log::info('Finish: Set SUKSES via snap_status fallback');
                }

                // Fallback 2: transaction_status param dari query string
                if (!$updated && in_array($transactionStatusParam, ['capture', 'settlement'])) {
                    $payment->update([
                        'payment_status' => 'sukses',
                        'payment_method' => $request->get('payment_type', 'midtrans'),
                    ]);
                    $reservation->update(['status' => 'sukses']);
                    \Log::info('Finish: Set SUKSES via transaction_status param fallback');
                }

                return redirect()->route('reservation.invoice', $payment->reservation_id);
            }
        }

        return redirect()->route('home')->with('error', 'Data pembayaran tidak ditemukan.');
    }
}
