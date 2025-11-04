<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Buyer; // Import the Buyer model

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Fetch existing buyers
        $buyers = Buyer::all();

        // 2. Check if any buyers exist before proceeding
        if ($buyers->isEmpty()) {
            $this->command->info('No buyers found. Please seed buyers first.');
            return;
        }

        // 3. Insert brands and assign a random buyer_id to each
        DB::table('brands')->insert([
            [
                'brand_name' => 'Atam Labels',
                'brand_image' => 'atam.png',
                'buyer_id' => $buyers->random()->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'brand_name' => 'Speed Labels',
                'brand_image' => 'speed.png',
                'buyer_id' => $buyers->random()->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'brand_name' => 'Classic Prints',
                'brand_image' => 'classic.png',
                'buyer_id' => $buyers->random()->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
