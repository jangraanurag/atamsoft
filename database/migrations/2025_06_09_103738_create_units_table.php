<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id(); // unit_id (auto-incremented primary key)
            $table->string('unit_name', 20);
            $table->string('unit_shorthand', 10);
            $table->timestamps(); // created_at, updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
