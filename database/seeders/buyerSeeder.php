<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BuyerSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('buyers')->insert([
            [
                'buyer_name'      => 'ABC Enterprises',
                'buyer_address'   => '123 Industrial Area, Delhi',
                'buyer_city'      => 'New Delhi',
                'buyer_state'     => 'Delhi',
                'buyer_country'   => 'India',
                'buyer_mobile_no' => '9876543210',
                'buyer_email_id'  => 'contact@abc.com',
                'buyer_tax_no'    => 'DEL1234ABC',
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'buyer_name'      => 'XYZ Pvt Ltd',
                'buyer_address'   => '456 Business Park, Mumbai',
                'buyer_city'      => 'Mumbai',
                'buyer_state'     => 'Maharashtra',
                'buyer_country'   => 'India',
                'buyer_mobile_no' => '9123456780',
                'buyer_email_id'  => 'info@xyz.com',
                'buyer_tax_no'    => 'MH5678XYZ',
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'buyer_name'      => 'Global Traders',
                'buyer_address'   => '789 Commercial Street, Bengaluru',
                'buyer_city'      => 'Bengaluru',
                'buyer_state'     => 'Karnataka',
                'buyer_country'   => 'India',
                'buyer_mobile_no' => '9012345678',
                'buyer_email_id'  => 'sales@globaltraders.in',
                'buyer_tax_no'    => 'KA9012GT',
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
        ]);
    }
}
