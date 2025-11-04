<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('buyers', function (Blueprint $table) {
            $table->id(); // buyer_id
            $table->string('buyer_name', 50);
            $table->text('buyer_address')->nullable();
            $table->string('buyer_city', 50)->nullable();
            $table->string('buyer_state', 50)->nullable();
            $table->string('buyer_country', 50)->nullable();
            $table->string('buyer_mobile_no', 15)->nullable();
            $table->string('buyer_email_id', 100)->nullable();
            $table->string('buyer_tax_no', 30)->nullable(); // e.g., GST or PAN
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buyers');
    }
};
