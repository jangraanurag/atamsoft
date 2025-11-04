<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'product_name' => 'Self Adhesive Labels',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'product_name' => 'Barcode Stickers',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'product_name' => 'Die Cut Labels',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'product_name' => 'Printed Tags',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
        ]);
    }
}
