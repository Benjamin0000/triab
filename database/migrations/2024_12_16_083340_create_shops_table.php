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
        Schema::create('shops', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->indexed();
            $table->string('storeID')->indexed(); //a randomely generated store ID
            $table->integer('category_id')->indexed();

            $table->string('logo'); 
            $table->string('name');
            $table->text('description');
            $table->string('state');
            $table->string('city');
            $table->string('address', 1000);
 
            $table->decimal('total_rewards', 65, 5)->default(0); //reward from the platform
            $table->decimal('total_sales', 65, 5)->default(0);
            $table->decimal('total_profit', 65, 5)->default(0);
            $table->decimal('vat', 65, 2)->default(0); 
            $table->decimal('service_fee', 65, 2)->default(0); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
