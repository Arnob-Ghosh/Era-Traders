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
        Schema::create('invoiceprices', function (Blueprint $table) {
            $table->id();
            $table->string('ref_id');
            $table->string('total_price');
            $table->string('paid');
            $table->string('due');
            $table->string('customer_id');
            $table->string('customer_name');
            $table->string('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoiceprices');
    }
};
