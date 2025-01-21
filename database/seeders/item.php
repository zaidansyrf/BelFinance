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
                    ['code' => 'KAB', 'name' => 'KO Ayam Bakar', 'price' => 19000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'KAG', 'name' => 'KO Ayam Goreng', 'price' => 19000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'KPI', 'name' => 'KO Ayam Penyet Ijo', 'price' => 19000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'KPM', 'name' => 'KO Ayam Penyet Merah', 'price' => 19000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'KAP', 'name' => 'KO Ampela', 'price' => 13000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'KCM', 'name' => 'KO Cumi', 'price' => 18000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'KTY', 'name' => 'KO Teriyaki', 'price' => 19000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'KTC', 'name' => 'KO Taichan 10', 'price' => 21000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'KT5', 'name' => 'KO Taichan 5', 'price' => 16000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'MT5', 'name' => 'KM Taichan 5', 'price' => 15000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'MT1', 'name' => 'KM Taichan 10', 'price' => 20000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'MAB', 'name' => 'KM Ayam Bakar', 'price' => 18000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'MAG', 'name' => 'KM Ayam Goreng', 'price' => 18000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'MPI', 'name' => 'KM Ayam Penyet Ijo', 'price' => 18000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'MPM', 'name' => 'KM Ayam Penyet Merah', 'price' => 18000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'MAP', 'name' => 'KM Ampela', 'price' => 12000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'MCM', 'name' => 'KM Cumi', 'price' => 17000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'MTY', 'name' => 'KM Teriyaki', 'price' => 18000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'NAB', 'name' => 'N Ayam Bakar', 'price' => 16000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'NAG', 'name' => 'N Ayam Goreng', 'price' => 16000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'NPI', 'name' => 'N Ayam Penyet Ijo', 'price' => 16000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'NPM', 'name' => 'N Ayam Penyet Merah', 'price' => 16000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'NAP', 'name' => 'N Ati Ampela', 'price' => 10000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'NCM', 'name' => 'N Cumi', 'price' => 15000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'NTY', 'name' => 'N Teriyaki', 'price' => 16000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'NTL', 'name' => 'N Telor Dadar/Ceplok', 'price' => 10000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'NTC', 'name' => 'N Taichan 10', 'price' => 18000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'NT5', 'name' => 'N Taichan 5', 'price' => 13000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'ALB', 'name' => 'ALC Ayam Bakar', 'price' => 13000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'ALG', 'name' => 'ALC Ayam Goreng', 'price' => 13000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'API', 'name' => 'ALC Penyet Ijo', 'price' => 13000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'APM', 'name' => 'ALC Penyet Merah', 'price' => 13000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'AST', 'name' => 'ALC Sambel Tempe', 'price' => 13000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'ACM', 'name' => 'ALC Cumi', 'price' => 5000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'ATY', 'name' => 'ALC Teriyaki', 'price' => 10000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'NPO', 'name' => 'Nasi Putih/Onigiri', 'price' => 12000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'AIM', 'name' => 'Air Mineral', 'price' => 4000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'ICH', 'name' => 'Ichi Ocha', 'price' => 3000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'AT5', 'name' => 'ALC Taichan 5', 'price' => 4000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'AT1', 'name' => 'ALC Taichan 10', 'price' => 10000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['code' => 'AEC', 'name' => 'ALC/Extra Telor Ceplok', 'price' => 15000, 'quantity' => 0, 'created_at' => now(), 'updated_at' => now()],
                ];
                
        
                // Insert data ke tabel 'items'
                DB::table('items')->insert($items);

    }
}
