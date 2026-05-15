<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin Amaze Hotel',
            'username' => 'admin',
            'password' => '12345678',
            'role' => 'admin'
        ]);
    }
}
