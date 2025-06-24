<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class KeuanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'belfinance',
            'email' => 'keuangan@belindokitchen.com',
            'password' => Hash::make('belindo_keuangan'),
            'email_verified_at' => now(),
            'role' => 'keuangan',
        ]);

        User::create([
            'name' => 'belowner',
            'email' => 'owner@belindokitchen.com',
            'password' => Hash::make('belindo_owner'),
            'email_verified_at' => now(),
            'role' => 'owner',
        ]);
    }
}


