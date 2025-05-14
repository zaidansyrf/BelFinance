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
                    ['code' => 'KO 1', 'name' => 'KO Ayam Bakar', 'price' => 19000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'KO 2', 'name' => 'KO Ayam Goreng', 'price' => 19000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'KO 3', 'name' => 'KO Ayam Penyet Ijo', 'price' => 19000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'KO 4', 'name' => 'KO Ayam Penyet Merah', 'price' => 19000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'KO 5', 'name' => 'KO Ampela', 'price' => 13000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'KO 6', 'name' => 'KO Cumi', 'price' => 18000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'KO 7', 'name' => 'KO Teriyaki', 'price' => 19000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'KO 8', 'name' => 'KO Taichan 10', 'price' => 21000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'KO 9', 'name' => 'KO Taichan 5', 'price' => 16000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'KM 8', 'name' => 'KM Taichan 5', 'price' => 15000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'KM 9', 'name' => 'KM Taichan 10', 'price' => 20000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'KM 1', 'name' => 'KM Ayam Bakar', 'price' => 18000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'KM 2', 'name' => 'KM Ayam Goreng', 'price' => 18000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'KM 3', 'name' => 'KM Ayam Penyet Ijo', 'price' => 18000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'KM 4', 'name' => 'KM Ayam Penyet Merah', 'price' => 18000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'KM 5', 'name' => 'KM Ampela', 'price' => 12000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'KM 6', 'name' => 'KM Cumi', 'price' => 17000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'KM 7', 'name' => 'KM Teriyaki', 'price' => 18000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'N 1', 'name' => 'N Ayam Bakar', 'price' => 16000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'N 2', 'name' => 'N Ayam Goreng', 'price' => 16000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'N 3', 'name' => 'N Ayam Penyet Ijo', 'price' => 16000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'N 4', 'name' => 'N Ayam Penyet Merah', 'price' => 16000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'N 5', 'name' => 'N Ati Ampela', 'price' => 10000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'N 6', 'name' => 'N Cumi', 'price' => 15000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'N 7', 'name' => 'N Teriyaki', 'price' => 16000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'N 10', 'name' => 'N Telor Dadar/Ceplok', 'price' => 10000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'N 9', 'name' => 'N Taichan 10', 'price' => 18000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'N 8', 'name' => 'N Taichan 5', 'price' => 13000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'ALC 1', 'name' => 'ALC Ayam Bakar', 'price' => 13000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'ALC 2', 'name' => 'ALC Ayam Goreng', 'price' => 13000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'ALC 3', 'name' => 'ALC Penyet Ijo', 'price' => 13000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'ALC 4', 'name' => 'ALC Penyet Merah', 'price' => 13000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'ALC 5', 'name' => 'ALC Sambel Tempe', 'price' => 13000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'ALC 6', 'name' => 'ALC Cumi', 'price' => 5000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'ALC 7', 'name' => 'ALC Teriyaki', 'price' => 10000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'NPO', 'name' => 'Nasi Putih/Onigiri', 'price' => 12000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'AIM', 'name' => 'Air Mineral', 'price' => 4000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'ICH', 'name' => 'Ichi Ocha', 'price' => 3000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'ALC 8', 'name' => 'ALC Taichan 5', 'price' => 4000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'ALC 9', 'name' => 'ALC Taichan 10', 'price' => 10000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'ALC 10', 'name' => 'ALC/Extra Telor Ceplok', 'price' => 15000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                ];
                
        
                // Insert data ke tabel 'items'
                DB::table('items')->insert($items);

    }
}
