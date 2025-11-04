<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); // order_id

            // Core Details
            $table->unsignedBigInteger('order_brand')->nullable();
            $table->unsignedBigInteger('order_productName')->nullable();
            $table->unsignedBigInteger('order_labelType')->nullable();
            $table->unsignedBigInteger('order_buyer'); // Keeping NOT nullable (cascade delete)
            $table->date('order_date')->nullable();
            $table->date('order_deliverydate')->nullable();

            // Quantity & Dimensions
            $table->float('order_weight')->nullable();
            $table->float('order_qty')->nullable();
            $table->unsignedBigInteger('order_unit')->nullable();
            $table->float('order_manufacturedqty')->nullable();
            $table->float('order_length')->nullable();
            $table->float('order_width')->nullable();
            $table->unsignedBigInteger('order_d_unit')->nullable();

            // Materials & Colors
            $table->string('order_nailmaterial')->nullable();
            $table->json('colors')->nullable(); // ADDED: To store the dynamic list of colors

            // Shipping & Status
            $table->unsignedBigInteger('order_status')->nullable();
            $table->string('order_shipmentno', 100)->nullable();
            $table->unsignedBigInteger('order_shipvia')->nullable();
            $table->string('order_salesman', 100)->nullable();

            // Attachments
            $table->string('order_logo', 200)->nullable();
            $table->string('order_image', 200)->nullable();
            $table->string('order_cdr', 200)->nullable();
            $table->string('order_po', 200)->nullable();

            // Pricing
            $table->float('order_price')->nullable();
            $table->float('order_mouldprice')->nullable();
            $table->boolean('dieset')->default(false);
            $table->decimal('buyer_total', 10, 2)->nullable();

            // Supplier Info
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->decimal('supplier_price', 10, 2)->nullable();
            $table->decimal('supplier_mould_price', 10, 2)->nullable();
            $table->decimal('supplier_total', 10, 2)->nullable();

            // Remarks
            $table->text('order_remark')->nullable();
            $table->text('buyer_remark')->nullable();
            $table->text('supplier_remark')->nullable();

            // Bill To Fields
            $table->string('bill_to_name', 255)->nullable();
            $table->string('bill_to_address_line_1', 255)->nullable();
            $table->string('bill_to_address_line_2', 255)->nullable();
            $table->string('bill_to_city', 100)->nullable();
            $table->string('bill_to_state', 100)->nullable();
            $table->string('bill_to_postal_code', 20)->nullable();
            $table->string('bill_to_country', 100)->nullable();

            // Ship To Fields
            $table->string('ship_to_name', 255)->nullable();
            $table->string('ship_to_address_line_1', 255)->nullable();
            $table->string('ship_to_address_line_2', 255)->nullable();
            $table->string('ship_to_city', 100)->nullable();
            $table->string('ship_to_state', 100)->nullable();
            $table->string('ship_to_postal_code', 20)->nullable();
            $table->string('ship_to_country', 100)->nullable();

            $table->timestamps();

            // Foreign Keys with Correct Null Handling
            $table->foreign('order_brand')->references('id')->on('brands')->onDelete('set null');
            $table->foreign('order_productName')->references('id')->on('products')->onDelete('set null');
            $table->foreign('order_labelType')->references('id')->on('label_types')->onDelete('set null');
            $table->foreign('order_unit')->references('id')->on('units')->onDelete('set null');
            $table->foreign('order_d_unit')->references('id')->on('d_units')->onDelete('set null');
            $table->foreign('order_status')->references('id')->on('statuses')->onDelete('set null');
            $table->foreign('order_buyer')->references('id')->on('buyers')->onDelete('cascade');
            $table->foreign('order_shipvia')->references('id')->on('shippers')->onDelete('set null');
            // REMOVED: order_colorset foreign key
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
