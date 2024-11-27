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
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID
            $table->BigInteger('product_id'); // Foreign key to products table
            $table->string('product_name'); // Name of the product
            $table->string('category_name'); // Name of the category
            $table->BigInteger('category_id'); // Foreign key to categories table
            $table->timestamps(); // Created at & Updated at columns

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_categories');
    }
};
