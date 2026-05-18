<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ubah kolom time dari tipe 'time' ke 'string' agar bisa menyimpan nama sesi
     * seperti "Sesi Pagi (08:00 - 12:00)" bukan hanya jam "08:00:00"
     */
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->string('time', 100)->change();
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->time('time')->change();
        });
    }
};
