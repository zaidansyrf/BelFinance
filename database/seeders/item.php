<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class item extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                   // Data untuk tabel 'items'
                   $items = [
                    ['name' => 'KO Ayam Bakar', 'price' => 19000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'KO Ayam goreng', 'price' => 19000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'KO Ayam Penyet Ijo', 'price' => 19000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'KO Ayam Penyet Merah', 'price' => 19000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'KO Ampela', 'price' => 13000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'KO Cumi', 'price' => 18000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'KO Teriyaki', 'price' => 19000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'KO Taichan 10', 'price' => 21000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'KO Taichan 5', 'price' => 16000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'KM Taichan 5', 'price' => 15000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'KM Taichan 10', 'price' => 20000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'KM Ayam Bakar', 'price' => 18000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'KM Ayam goreng', 'price' => 18000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'KM Ayam Penyet Ijo', 'price' => 18000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'KM Ayam Penyet Merah', 'price' => 18000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'KM Ampela', 'price' => 12000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'KM Cumi', 'price' => 17000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'KM Teriyaki', 'price' => 18000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'N Ayam Bakar', 'price' => 16000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'N Ayam Goreng', 'price' => 16000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'N Ayam Penyet Ijo', 'price' => 16000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'N Ayam Penyet Merah', 'price' => 16000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'N Ati Ampela', 'price' => 10000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'N Cumi', 'price' => 15000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'N Teriyaki', 'price' => 16000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'N Telor Dadar/Ceplok', 'price' => 10000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'N Taichan 10', 'price' => 18000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'N Taichan 5', 'price' => 13000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'ALC Ayam Bakar', 'price' => 13000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'ALC Ayam Goreng', 'price' => 13000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'ALC Penyet Ijo', 'price' => 13000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'ALC Penyet Merah', 'price' => 13000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'ALC Sambel Tempe', 'price' => 13000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'ALC Cumi', 'price' => 5000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'ALC Teriyaki', 'price' => 10000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'Nasi Putih/Onigiri', 'price' => 12000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'Air Mineral', 'price' => 4000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'Ichi Ocha', 'price' => 3000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'ALC Taichan 5', 'price' => 4000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'ALC Taichan 10', 'price' => 10000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'ALC/Extra Telor Ceplok', 'price' => 15000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                ];
                
        
                // Insert data ke tabel 'items'
                DB::table('items')->insert($items);

    }
}
