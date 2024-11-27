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
        Schema::create('product_in_histories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id');
            $table->string('product_name');
            $table->bigInteger('category_id');
            $table->string('category_name');
            $table->integer('quantity');
            $table->integer('unit_id');
            $table->string('unit');
            $table->integer('sub_unit_id');
            $table->string('sub_unit');
            $table->double('unit_price');
            $table->double('sub_unit_price')->nullable();
            $table->string('created_by');
            $table->string('product_in_num');
            $table->date('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_in_histories');
    }
};
