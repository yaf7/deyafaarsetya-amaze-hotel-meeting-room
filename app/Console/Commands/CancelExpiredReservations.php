<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use Carbon\Carbon;

class CancelExpiredReservations extends Command
{
    protected $signature = 'reservations:cancel-expired';
    protected $description = 'Batalkan dan hapus reservasi yang belum dibayar setelah 20 menit';

    public function handle()
    {
        $expiredTime = Carbon::now()->subMinutes(20);

        // Cari reservasi pending yang sudah lebih dari 20 menit
        $expiredReservations = Reservation::where('status', 'pending')
            ->where('created_at', '<=', $expiredTime)
            ->whereHas('payment', function ($q) {
                $q->where('payment_status', 'pending');
            })
            ->get();

        $count = $expiredReservations->count();

        foreach ($expiredReservations as $reservation) {
            // Hapus buffet selections
            $reservation->buffetSelections()->delete();

            // Hapus payment
            $reservation->payment()->delete();

            // Hapus reservasi
            $reservation->delete();
        }

        if ($count > 0) {
            $this->info("Berhasil menghapus {$count} reservasi yang expired.");
        } else {
            $this->info('Tidak ada reservasi expired.');
        }

        return Command::SUCCESS;
    }
}
