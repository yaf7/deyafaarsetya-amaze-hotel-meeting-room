<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Jalankan pengecekan reservasi expired setiap menit
Schedule::command('reservations:cancel-expired')->everyMinute();

// Cek status pembayaran pending ke Midtrans API setiap menit
Schedule::command('payments:check-pending')->everyMinute();
