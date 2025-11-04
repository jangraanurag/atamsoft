<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DUnitSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('d_units')->insert([
            [
                'dunit_name'      => 'Inch',
                'dunit_shorthand' => 'IN',
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'dunit_name'      => 'Millimeter',
                'dunit_shorthand' => 'MM',
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'dunit_name'      => 'Centimeter',
                'dunit_shorthand' => 'CM',
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'dunit_name'      => 'Meter',
                'dunit_shorthand' => 'M',
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
        ]);
    }
}
