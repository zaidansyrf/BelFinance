<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class bill extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                        // Data untuk tabel 'sources'
                        $bills = [
                            ['name' => 'Listrik', 'created_at' => now(), 'updated_at' => now()],
                            ['name' => 'Air', 'created_at' => now(), 'updated_at' => now()],
                            ['name' => 'Bulanan ATM', 'created_at' => now(), 'updated_at' => now()],
                            ['name' => 'Bayar Kontrakan', 'created_at' => now(), 'updated_at' => now()],
                        ];
                
                        // Insert data ke tabel 'bills'
                        DB::table('bills')->insert($bills);
    }
}
