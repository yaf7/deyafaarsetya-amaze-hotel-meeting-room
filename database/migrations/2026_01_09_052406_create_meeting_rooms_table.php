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
        Schema::create('meeting_rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');                 // Nama ruangan (e.g., Brawijaya Room)
            $table->integer('capacity');            // Kapasitas maksimal (Theater)
            $table->text('facilities');             // Fasilitas (text biasa atau JSON)
            $table->text('layout');                 // JSON: {"theater":200, "classroom":120, ...}
            $table->bigInteger('price')->default(0); // Harga dasar (opsional, karena harga utama dari paket)
            $table->string('photo')->nullable();    // Path foto
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meeting_rooms');
    }
};
