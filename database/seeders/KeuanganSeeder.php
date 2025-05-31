<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class KeuanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $keuangan = [
            'name' => 'belfinance',
            'email' => 'keuangan@belindokitchen.com',
            'password' => Hash::make('belindo_keuangan'),
            'email_verified_at' => now(),
            'role' => 'keuangan',
        ];

        $owner = [
            'name' => 'belowner',
            'email' => 'owner@belindokitchen.com',
            'password' => Hash::make('belindo_owner'),
            'email_verified_at' => now(),
            'role' => 'owner',
        ];

        DB::table('users')->insert([$keuangan, $owner]);
    }
}
