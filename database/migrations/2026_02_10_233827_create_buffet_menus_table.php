<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('buffet_menus', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('category', ['soup', 'mie', 'ayam', 'ikan', 'sayuran', 'fritter']);
            $table->timestamps();
        });

        // Seeder data menu dari gambar standar buffet
        DB::table('buffet_menus')->insert([
            // Menu Sup
            ['name' => 'Sup Brokoli Jamur', 'category' => 'soup'],
            ['name' => 'Sup Tahu Bakso', 'category' => 'soup'],
            ['name' => 'Sup Tom Yum', 'category' => 'soup'],
            ['name' => 'Sup Sechuan', 'category' => 'soup'],
            ['name' => 'Sup Ikan Asam Pedas', 'category' => 'soup'],
            ['name' => 'Sup Kembang Tahu', 'category' => 'soup'],
            ['name' => 'Sup Kacang Merah', 'category' => 'soup'],
            ['name' => 'Soto Padang', 'category' => 'soup'],
            ['name' => 'Chicken Corn Soup', 'category' => 'soup'],

            // Menu Mie
            ['name' => 'Mie Goreng', 'category' => 'mie'],
            ['name' => 'Mie Goreng Kari', 'category' => 'mie'],
            ['name' => 'Bihun Goreng', 'category' => 'mie'],
            ['name' => 'Kwetiau Goreng', 'category' => 'mie'],
            ['name' => 'Soun Goreng Pengantin', 'category' => 'mie'],
            ['name' => 'Bihun Goreng Singapore', 'category' => 'mie'],

            // Menu Ayam
            ['name' => 'Ayam Bakar Pedas Manis (AW)', 'category' => 'ayam'],
            ['name' => 'Ayam Bakar Taliwang (AW)', 'category' => 'ayam'],
            ['name' => 'Ayam Bakar Padang (AW)', 'category' => 'ayam'],
            ['name' => 'Ayam Bakar Matah (PB)', 'category' => 'ayam'],
            ['name' => 'Ayam Rica Rica Kemangi (AW)', 'category' => 'ayam'],
            ['name' => 'Ayam Bumbu Bali (AW)', 'category' => 'ayam'],
            ['name' => 'Ayam Asam Manis (PB)', 'category' => 'ayam'],
            ['name' => 'Gulai Ayam Padang (AW)', 'category' => 'ayam'],
            ['name' => 'Chicken Kung Pao (PB)', 'category' => 'ayam'],

            // Menu Ikan
            ['name' => 'Ikan Sambal Dabu Dabu', 'category' => 'ikan'],
            ['name' => 'Ikan Acar Kuning', 'category' => 'ikan'],
            ['name' => 'Ikan Asam Manis', 'category' => 'ikan'],
            ['name' => 'Ikan Sambal Matah', 'category' => 'ikan'],
            ['name' => 'Ikan Rica-Rica Kemangi', 'category' => 'ikan'],
            ['name' => 'Dori Colo-Colo', 'category' => 'ikan'],

            // Menu Sayuran
            ['name' => 'Cap Cay Kekian', 'category' => 'sayuran'],
            ['name' => 'Tumis Pokcoy Baso Ikan', 'category' => 'sayuran'],
            ['name' => 'Tumis Pokcoy Cah Ebi', 'category' => 'sayuran'],
            ['name' => 'Buncis Sechuan', 'category' => 'sayuran'],
            ['name' => 'Buncis Cah Jamur', 'category' => 'sayuran'],
            ['name' => 'Gulai Nangka', 'category' => 'sayuran'],
            ['name' => 'Gulai Daun Singkong', 'category' => 'sayuran'],
            ['name' => 'Tempe Tahu Orek Balado', 'category' => 'sayuran'],
            ['name' => 'Tempe Tahu Orek Basah Kecap', 'category' => 'sayuran'],

            // Menu Fritter
            ['name' => 'Tempe Mendoan Half', 'category' => 'fritter'],
            ['name' => 'Tempe Kremes', 'category' => 'fritter'],
            ['name' => 'Tahu Isi', 'category' => 'fritter'],
            ['name' => 'Bakwan Jagung', 'category' => 'fritter'],
            ['name' => 'Tempe Tahu Bacem', 'category' => 'fritter'],
            ['name' => 'Perkedel Tempe Trasi', 'category' => 'fritter'],
            ['name' => 'Perkedel Kentang Pedas', 'category' => 'fritter'],
            ['name' => 'Perkedel Tahu', 'category' => 'fritter'],
            ['name' => 'Tahu Kremes', 'category' => 'fritter'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buffet_menus');
    }
};
