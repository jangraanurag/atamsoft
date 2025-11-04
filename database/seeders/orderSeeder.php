<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('orders')->insert([
            [
                // Core Details
                'order_brand'           => 1,
                'order_productName'     => 1,
                'order_labelType'       => 1,
                'order_buyer'           => 1,
                'order_date'            => Carbon::now(),
                'order_deliverydate'    => Carbon::now()->addDays(7),

                // Quantity & Dimensions
                'order_weight'          => 150.5,
                'order_qty'             => 1000,
                'order_unit'            => 1,
                'order_manufacturedqty' => 950,
                'order_length'          => 10.0,
                'order_width'           => 5.0,
                'order_d_unit'          => 1,

                // Materials & Colors
                'order_nailmaterial'    => 'Steel',
                'colors'                => json_encode(['Red', 'Blue', 'Green']), // UPDATED: Stored as JSON

                // Shipping & Status
                'order_status'          => 1,
                'order_shipmentno'      => 'SHIP-001',
                'order_shipvia'         => 1,
                'order_salesman'        => 'Rahul',

                // Attachments
                'order_logo'            => null,
                'order_image'           => null,
                'order_cdr'             => null,
                'order_po'              => 'PO-001',

                // Pricing
                'order_price'           => 5000.00,
                'order_mouldprice'      => 500.00,
                'dieset'                => true,
                'buyer_total'           => 5500.00,

                // Supplier Info
                'supplier_id'           => 1,
                'supplier_price'        => 4000.00,
                'supplier_mould_price'  => 400.00,
                'supplier_total'        => 4400.00,

                // Remarks
                'order_remark'          => 'First order, standard materials.',
                'buyer_remark'          => 'Buyer wants delivery by Friday.',
                'supplier_remark'       => 'Supplier confirmed stock availability.',

                // Bill To Fields
                'bill_to_name'          => 'Bill To Customer A',
                'bill_to_address_line_1'=> '123 Billing St',
                'bill_to_address_line_2'=> null,
                'bill_to_city'          => 'Mumbai',
                'bill_to_state'         => 'Maharashtra',
                'bill_to_postal_code'   => '400001',
                'bill_to_country'       => 'India',

                // Ship To Fields
                'ship_to_name'          => 'Ship To Customer A',
                'ship_to_address_line_1'=> '456 Shipping Ave',
                'ship_to_address_line_2'=> 'Apt 2B',
                'ship_to_city'          => 'Mumbai',
                'ship_to_state'         => 'Maharashtra',
                'ship_to_postal_code'   => '400002',
                'ship_to_country'       => 'India',

                'created_at'            => Carbon::now(),
                'updated_at'            => Carbon::now(),
            ],
            [
                // Core Details
                'order_brand'           => 2,
                'order_productName'     => 2,
                'order_labelType'       => 1,
                'order_buyer'           => 2,
                'order_date'            => Carbon::now(),
                'order_deliverydate'    => Carbon::now()->addDays(10),

                // Quantity & Dimensions
                'order_weight'          => 200.0,
                'order_qty'             => 500,
                'order_unit'            => 2,
                'order_manufacturedqty' => 450,
                'order_length'          => 12.5,
                'order_width'           => 6.5,
                'order_d_unit'          => 1,

                // Materials & Colors
                'order_nailmaterial'    => 'Plastic',
                'colors'                => json_encode(['Yellow', 'Black']), // UPDATED: Stored as JSON

                // Shipping & Status
                'order_status'          => 2,
                'order_shipmentno'      => 'SHIP-002',
                'order_shipvia'         => 2,
                'order_salesman'        => 'Amit',

                // Attachments
                'order_logo'            => null,
                'order_image'           => null,
                'order_cdr'             => null,
                'order_po'              => 'PO-002',

                // Pricing
                'order_price'           => 6000.00,
                'order_mouldprice'      => 700.00,
                'dieset'                => false,
                'buyer_total'           => 6700.00,

                // Supplier Info
                'supplier_id'           => 2,
                'supplier_price'        => 5000.00,
                'supplier_mould_price'  => 600.00,
                'supplier_total'        => 5600.00,

                // Remarks
                'order_remark'          => 'Urgent order, special packaging required.',
                'buyer_remark'          => 'Requires special packaging.',
                'supplier_remark'       => 'Awaiting material from vendor.',

                // Bill To Fields
                'bill_to_name'          => 'Bill To Customer B',
                'bill_to_address_line_1'=> '789 Billing Blvd',
                'bill_to_address_line_2'=> null,
                'bill_to_city'          => 'Delhi',
                'bill_to_state'         => 'Delhi',
                'bill_to_postal_code'   => '110001',
                'bill_to_country'       => 'India',

                // Ship To Fields
                'ship_to_name'          => 'Ship To Customer B',
                'ship_to_address_line_1'=> '789 Billing Blvd',
                'ship_to_address_line_2'=> null,
                'ship_to_city'          => 'Delhi',
                'ship_to_state'         => 'Delhi',
                'ship_to_postal_code'   => '110001',
                'ship_to_country'       => 'India',

                'created_at'            => Carbon::now(),
                'updated_at'            => Carbon::now(),
            ],
        ]);
    }
}
