<?php

namespace App\Services;

use App\Models\Reservation;
use Carbon\Carbon;

class WhatsappNotificationService
{
    /**
     * Kirim notifikasi WhatsApp simulasi otomatis.
     * Dipanggil ketika status reservasi berubah ke 'sukses'.
     */
    public static function sendAutoNotification(Reservation $reservation): void
    {
        // Jika sudah pernah dikirim, skip
        if ($reservation->whatsapp_sent) {
            return;
        }

        // Load relasi yang diperlukan jika belum di-load
        $reservation->loadMissing([
            'meetingRoom',
            'foodPackage',
            'promotion',
            'buffetSelections.buffetMenu'
        ]);

        // Generate template pesan
        $tanggal = Carbon::parse($reservation->date)->locale('id')->isoFormat('dddd, D MMM Y');
        $buffetMenuText = "";
        if ($reservation->buffetSelections->count() > 0) {
            $buffetMenuText = "\n🍱 *Menu Buffet:*\n";
            foreach ($reservation->buffetSelections as $sel) {
                $buffetMenuText .= "• " . $sel->buffetMenu->name . " (" . ucfirst($sel->buffetMenu->category) . ")\n";
            }
        }

        $discountPercent = $reservation->promotion?->discount ?? 0;
        $discountAmount = ($reservation->total_price * $discountPercent) / 100;
        $finalPrice = number_format($reservation->total_price - $discountAmount, 0, ',', '.');

        $message = "Assalamu'alaikum {$reservation->customer_name} 👋\n\n"
            . "Kami dari *Amaze Hotel Kediri* ingin mengkonfirmasi reservasi meeting room Anda.\n\n"
            . "📋 *KONFIRMASI RESERVASI #" . str_pad($reservation->id, 6, '0', STR_PAD_LEFT) . "*\n"
            . "━━━━━━━━━━━━━━━━━━\n"
            . "📅 Tanggal: {$tanggal}\n"
            . "🕐 Sesi: {$reservation->time}\n"
            . "🏨 Ruangan: {$reservation->meetingRoom->name}\n"
            . "👥 Peserta: {$reservation->participants} orang\n"
            . "🍽️ Paket: {$reservation->foodPackage->name}\n\n"
            . "🏢 *Fasilitas Ruangan:*\n"
            . "• LCD Projector + Screen\n"
            . "• Sound System\n"
            . "• Flipchart & Writing Materials\n"
            . "• Air Mineral\n\n"
            . "🍴 *Menu Include:*\n"
            . "• Nasi Putih\n"
            . "• 2 Kind of Slice Fruit\n"
            . "• Assorted Dessert\n"
            . "• Any Kind Juice\n"
            . "• Mineral Dispenser\n"
            . "• Coffee Break\n"
            . $buffetMenuText . "\n"
            . "💰 *Total Bayar: Rp{$finalPrice}*\n\n"
            . "✅ Pembayaran telah diterima. Reservasi Anda sudah dikonfirmasi. Silakan datang 15 menit sebelum waktu yang ditentukan.\n\n"
            . "Terima kasih! 🙏";

        // Simpan pesan admin (notif otomatis)
        $reservation->chats()->create([
            'sender' => 'admin',
            'message' => $message
        ]);

        // Simulasi balasan otomatis customer
        $reservation->chats()->create([
            'sender' => 'customer',
            'message' => "Waalaikumsalam, baik terima kasih konfirmasinya 🙏"
        ]);

        // Tandai WhatsApp sebagai terkirim
        $reservation->update([
            'whatsapp_sent' => true,
            'whatsapp_sent_at' => now()
        ]);
    }

    /**
     * Kirim notifikasi WhatsApp simulasi untuk reschedule yang disetujui.
     */
    public static function sendRescheduleNotification(Reservation $reservation, string $oldDate, string $oldSession): void
    {
        // Load relasi yang diperlukan
        $reservation->loadMissing([
            'meetingRoom',
            'foodPackage',
            'promotion',
        ]);

        $tanggalLama = Carbon::parse($oldDate)->locale('id')->isoFormat('dddd, D MMM Y');
        $tanggalBaru = Carbon::parse($reservation->date)->locale('id')->isoFormat('dddd, D MMM Y');

        $discountPercent = $reservation->promotion?->discount ?? 0;
        $discountAmount = ($reservation->total_price * $discountPercent) / 100;
        $finalPrice = number_format($reservation->total_price - $discountAmount, 0, ',', '.');

        $message = "Assalamu'alaikum {$reservation->customer_name} 👋\n\n"
            . "Kami dari *Amaze Hotel Kediri* ingin menginformasikan bahwa pengajuan *reschedule* reservasi Anda telah *disetujui*. ✅\n\n"
            . "📋 *RESCHEDULE RESERVASI #" . str_pad($reservation->id, 6, '0', STR_PAD_LEFT) . "*\n"
            . "━━━━━━━━━━━━━━━━━━\n\n"
            . "❌ *Jadwal Lama:*\n"
            . "📅 Tanggal: {$tanggalLama}\n"
            . "🕐 Sesi: {$oldSession}\n\n"
            . "✅ *Jadwal Baru:*\n"
            . "📅 Tanggal: {$tanggalBaru}\n"
            . "🕐 Sesi: {$reservation->time}\n\n"
            . "🏨 Ruangan: {$reservation->meetingRoom->name}\n"
            . "👥 Peserta: {$reservation->participants} orang\n"
            . "🍽️ Paket: {$reservation->foodPackage->name}\n"
            . "💰 Total Bayar: Rp{$finalPrice}\n\n"
            . "Silakan datang 15 menit sebelum waktu yang ditentukan.\n\n"
            . "Terima kasih! 🙏";

        // Simpan pesan admin (notif otomatis reschedule)
        $reservation->chats()->create([
            'sender' => 'admin',
            'message' => $message
        ]);

        // Simulasi balasan otomatis customer
        $reservation->chats()->create([
            'sender' => 'customer',
            'message' => "Baik, terima kasih atas informasi perubahan jadwalnya 🙏"
        ]);

        // Update timestamp WhatsApp terkirim
        $reservation->update([
            'whatsapp_sent_at' => now()
        ]);
    }
}
