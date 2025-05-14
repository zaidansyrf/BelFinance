<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        
         $Keuangan = [
        'name' => 'belfinance',
        'email' => 'keuangan@belindokitchen.com',
        'password' => Hash::make('belindo_keuangan'),
        'email_verified_at' => now(),
    ];
    DB::table('users')->insert($Keuangan);
    }
}
