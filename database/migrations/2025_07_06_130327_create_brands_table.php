<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->id(); // brand_id
            $table->string('brand_name', 100);
            $table->string('brand_image', 200)->nullable();

            // Foreign key to the buyers table
            $table->unsignedBigInteger('buyer_id')->nullable();
            $table->foreign('buyer_id')->references('id')->on('buyers')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
