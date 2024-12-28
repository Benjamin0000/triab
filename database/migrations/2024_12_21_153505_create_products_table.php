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
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('shop_id')->indexed();
            $table->uuid('parent_id')->nullable()->indexed();
            $table->string('productID')->nullable();
            $table->string('name');
            $table->text('images')->nullable();
            $table->decimal('cost_price', 65, 2)->default(0);
            $table->decimal('selling_price', 65, 2)->default(0);
            $table->integer('total')->default(0);
            $table->boolean('type')->default(0);  //either a category or an item
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
