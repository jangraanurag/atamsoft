<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class colorSetSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('colorsets')->insert([
            [
                'colorset_name' => 'Single Color',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'colorset_name' => 'Two Color',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'colorset_name' => 'Four Color',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'colorset_name' => 'Full Color',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
        ]);
    }
}
