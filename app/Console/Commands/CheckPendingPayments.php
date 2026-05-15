<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Payment;
use Midtrans\Config;

class CheckPendingPayments extends Command
{
    protected $signature = 'payments:check-pending';
    protected $description = 'Cek status pembayaran pending ke Midtrans API dan update jika sudah settlement';

    public function handle()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');

        // Ambil semua payment yang masih pending dan punya transaction_id
        $pendingPayments = Payment::where('payment_status', 'pending')
            ->whereNotNull('transaction_id')
            ->get();

        $updated = 0;

        foreach ($pendingPayments as $payment) {
            try {
                $status = \Midtrans\Transaction::status($payment->transaction_id);
                $transactionStatus = $status->transaction_status ?? null;
                $paymentType = $status->payment_type ?? 'midtrans';

                if (in_array($transactionStatus, ['capture', 'settlement'])) {
                    $payment->update([
                        'payment_status' => 'sukses',
                        'payment_method' => $paymentType,
                    ]);
                    $payment->reservation->update(['status' => 'sukses']);
                    $updated++;
                    $this->info("✅ SUKSES: {$payment->transaction_id}");
                } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                    $payment->update([
                        'payment_status' => 'gagal',
                        'payment_method' => $paymentType,
                    ]);
                    $payment->reservation->update(['status' => 'gagal']);
                    $this->info("❌ GAGAL: {$payment->transaction_id}");
                } else {
                    $this->info("⏳ PENDING: {$payment->transaction_id} (status: {$transactionStatus})");
                }
            } catch (\Exception $e) {
                $this->warn("⚠️ Error checking {$payment->transaction_id}: {$e->getMessage()}");
            }
        }

        $this->info("Selesai. {$updated} pembayaran diupdate ke sukses.");
        return Command::SUCCESS;
    }
}
