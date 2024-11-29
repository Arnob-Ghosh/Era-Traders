<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->bigInteger('product_id');
            $table->bigInteger('category_id');
            $table->string('category_name');
            $table->bigInteger('unit_id');
            $table->string('unit_name');
            $table->string('sub_unit_name');
            $table->string('sub_unit');
            $table->bigInteger('sub_unit_id');
            $table->double('unit_price');
            $table->double('sub_unit_price');
            $table->double('unit_quantity');
            $table->double('sub_unit_quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
