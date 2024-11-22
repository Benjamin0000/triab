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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('cost', 65, 2)->default(0);
            $table->decimal('discount', 65, 2)->default(0);
            $table->integer('max_gen')->default(0); //max reward gen
            $table->integer('total_users')->default(0); 
            $table->string('services')->nullable(); 
            $table->timestamps();
        });
    }

    //benefits from Package purchase. 
    //get cashback into your main balance. starting from you the main user who signed up. 
    //pv 
    //move to g-steam wheel 

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
