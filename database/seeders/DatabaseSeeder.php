<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Usertiga;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        Usertiga::create([
            'name' => 'Admin',
            'address' => 'Puloreang',
            'phonenumber' => '0987654321',
            'email' => 'admin123@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
            'is_active' => 'active',
        ]);
    }
}
