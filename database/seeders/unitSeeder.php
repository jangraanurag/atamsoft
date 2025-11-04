<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class unitSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('units')->insert([
            [
                'unit_name'      => 'Kilogram',
                'unit_shorthand' => 'KG',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'unit_name'      => 'Gram',
                'unit_shorthand' => 'GM',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'unit_name'      => 'Meter',
                'unit_shorthand' => 'M',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'unit_name'      => 'Millimeter',
                'unit_shorthand' => 'MM',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'unit_name'      => 'Piece',
                'unit_shorthand' => 'PC',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
        ]);
    }
}
