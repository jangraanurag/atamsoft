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
         $this->call([
            StatusSeeder::class,
            userSeeder::class,
            buyerSeeder::class,
            brandSeeder::class,

            // Add other seeders here
            colorsetSeeder::class,
            labeltypeSeeder::class,
            shipperSeeder::class,
            dunitSeeder::class,
            unitSeeder::class,
            productSeeder::class,
            supplierSeeder::class,

            orderSeeder::class,


        ]);


    }
}
