<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Gọi file Seeder phân quyền và tạo Admin của chúng ta
        $this->call([
            RolesAndPermissionsSeeder::class,
        ]);
    }
}