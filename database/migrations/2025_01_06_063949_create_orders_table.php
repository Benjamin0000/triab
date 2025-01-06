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
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('shop_id')->indexed();
            $table->string('orderID')->indexed();
            $table->string('staff')->nullable();
            $table->decimal('sub_total', 65, 2);
            $table->decimal('vat', 65, 2)->default(0); 
            $table->decimal('fee', 65, 2)->default(0); 
            $table->decimal('total', 65, 2);
            $table->string('pay_method');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
