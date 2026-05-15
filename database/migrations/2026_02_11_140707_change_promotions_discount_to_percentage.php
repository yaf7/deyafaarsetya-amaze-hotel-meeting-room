<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('promotions', function (Blueprint $table) {
            // Ubah discount dari bigInteger (Rupiah) menjadi decimal (persentase 0-100)
            $table->decimal('discount', 5, 2)->change();  // Misal: 50.00 untuk 50%
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('promotions', function (Blueprint $table) {
            // Kembalikan ke bigInteger (Rupiah)
            $table->bigInteger('discount')->change();
        });
    }
};
