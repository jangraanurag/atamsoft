<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class supplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('suppliers')->insert([
            [
                'name' => 'Global Tech Parts',
                'email' => 'sales@globaltech.com',
                'phone' => '9876543210',
                'tax_id' => 'GSTIN12345ABCDE',
                'address_line_1' => 'Plot 42, Sector 18',
                'address_line_2' => null,
                'city' => 'Gurugram',
                'state' => 'Haryana',
                'postal_code' => '122001',
                'country' => 'India',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Precision Plastics Inc.',
                'email' => 'contact@precisionplastics.com',
                'phone' => '8765432109',
                'tax_id' => 'GSTIN67890FGHIJ',
                'address_line_1' => 'A-1 Industrial Area',
                'address_line_2' => 'Phase II',
                'city' => 'Noida',
                'state' => 'Uttar Pradesh',
                'postal_code' => '201301',
                'country' => 'India',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'MetalCraft Solutions',
                'email' => 'info@metalcraft.co.in',
                'phone' => '7654321098',
                'tax_id' => 'GSTIN11223KLMNO',
                'address_line_1' => 'C-55, Industrial Estate',
                'address_line_2' => null,
                'city' => 'Faridabad',
                'state' => 'Haryana',
                'postal_code' => '121004',
                'country' => 'India',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
