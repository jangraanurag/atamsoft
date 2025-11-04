<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class labeltypeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('label_types')->insert([
            [
                'labeltype_name' => 'Roll Form',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'labeltype_name' => 'Sheet Form',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'labeltype_name' => 'Individual Cut',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
        ]);
    }
}
