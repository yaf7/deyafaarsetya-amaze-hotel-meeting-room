<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Update harga dan deskripsi paket meeting sesuai aturan bisnis baru.
     */
    public function up(): void
    {
        // Half Day Meeting: 150.000 → 195.000
        DB::table('food_packages')
            ->where('name', 'Half Day Meeting')
            ->update([
                'price' => 195000,
                'description' => '4 jam: 1x Coffee Break + 1x Meal',
            ]);

        // Full Day Meeting: 195.000 → 235.000
        DB::table('food_packages')
            ->where('name', 'Full Day Meeting')
            ->update([
                'price' => 235000,
                'description' => '8 jam: 2x Coffee Break + 1x Meal',
            ]);

        // Full Board Meeting: 235.000 → 380.000
        DB::table('food_packages')
            ->where('name', 'Full Board Meeting')
            ->update([
                'price' => 380000,
                'description' => '12 jam: 2x Coffee Break + 2x Meal',
            ]);

        // Residential Full Day Meeting: tetap 550.000 (display Twin)
        DB::table('food_packages')
            ->where('name', 'Residential Full Day Meeting')
            ->update([
                'price' => 550000,
                'description' => '8 jam + Menginap: Superior Room + 1x CB + 1x Meal',
            ]);

        // Residential Full Board Meeting: tetap 600.000 (display Twin)
        DB::table('food_packages')
            ->where('name', 'Residential Full Board Meeting')
            ->update([
                'price' => 600000,
                'description' => '12 jam + Menginap: Superior Room + 2x CB + 3x Meals',
            ]);
    }

    /**
     * Reverse the migrations — kembalikan ke harga lama.
     */
    public function down(): void
    {
        DB::table('food_packages')
            ->where('name', 'Half Day Meeting')
            ->update([
                'price' => 150000,
                'description' => '4 jam: 1 Coffee Break + 1 Meal',
            ]);

        DB::table('food_packages')
            ->where('name', 'Full Day Meeting')
            ->update([
                'price' => 195000,
                'description' => '8 jam: 2 Coffee Break + 1 Meal',
            ]);

        DB::table('food_packages')
            ->where('name', 'Full Board Meeting')
            ->update([
                'price' => 235000,
                'description' => '12 jam: 2 Coffee Break + 2 Meal',
            ]);

        DB::table('food_packages')
            ->where('name', 'Residential Full Day Meeting')
            ->update([
                'price' => 550000,
                'description' => '8 jam + 1 malam menginap: Superior Room + 1 Coffee Break + 1 Meal (Twin)',
            ]);

        DB::table('food_packages')
            ->where('name', 'Residential Full Board Meeting')
            ->update([
                'price' => 600000,
                'description' => '12 jam + 1 malam menginap: Superior Room + 2 Coffee Break + 3 Meal (Twin)',
            ]);
    }
};
