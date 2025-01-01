<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat 10 user menggunakan factory
        User::factory(10)->create();

        // Panggil seeder lainnya
        $this->call([
            Source::class,
            bill::class,
            item::class,
        ]);
    }
}
