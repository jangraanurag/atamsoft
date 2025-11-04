<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class shipperSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('shippers')->insert([
            [
                'shipper_name' => 'FedEx',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'shipper_name' => 'DHL',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'shipper_name' => 'BlueDart',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'shipper_name' => 'India Post',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
        ]);
    }
}
