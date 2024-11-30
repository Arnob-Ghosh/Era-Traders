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
        Schema::create('sales', function (Blueprint $table) {
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
            $table->double('sale_quantity');
            $table->string('sale_unit');
            $table->double('sale_price');
            $table->date('date');
            $table->string('customer_name');
            $table->string('ref_id');
            $table->bigInteger('customer_id');
            $table->text('note');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
