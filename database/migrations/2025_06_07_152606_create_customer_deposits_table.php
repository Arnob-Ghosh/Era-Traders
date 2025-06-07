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
        Schema::create('customer_deposits', function (Blueprint $table) {
           $table->id();
        $table->string('ref_id')->unique();
        $table->decimal('amount', 12, 2);
        $table->date('date');
        $table->unsignedBigInteger('customer_id');
        $table->bigInteger('sales_id');
        $table->string('note');
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_deposits');
    }
};
