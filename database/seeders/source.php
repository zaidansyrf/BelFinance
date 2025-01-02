<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class source extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                                 // Data untuk tabel 'sources'
                                 $source = [
                                    ['name' => 'CASH', 'created_at' => now(), 'updated_at' => now()],
                                    ['name' => 'BNI', 'created_at' => now(), 'updated_at' => now()],
                                    ['name' => 'BTN', 'created_at' => now(), 'updated_at' => now()],
                                    ['name' => 'GOJEK', 'created_at' => now(), 'updated_at' => now()],
                                    ['name' => 'GRAB', 'created_at' => now(), 'updated_at' => now()],
                                ];
                        
                                // Insert data ke tabel 'source'
                                DB::table('sources')->insert($source);       
    }
}
