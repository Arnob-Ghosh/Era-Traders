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
        Schema::create('product_return_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('product_name');
            $table->unsignedBigInteger('category_id');
            $table->string('category_name');
            $table->unsignedBigInteger('unit_id');
            $table->string('unit');
            $table->unsignedBigInteger('sub_unit_id')->nullable();
            $table->string('sub_unit_name')->nullable();
            $table->decimal('quantity', 15, 2);
            $table->double('unit_price');
            $table->string('return_num')->unique();
            $table->timestamp('date');
            $table->unsignedBigInteger('created_by');
            $table->string('note')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_return_histories');
    }
};
