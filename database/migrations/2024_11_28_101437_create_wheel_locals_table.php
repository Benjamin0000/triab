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
        Schema::create('wheel_locals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->indexed();
            $table->uuid('placed_under')->indexed();
            $table->decimal('main_balance')->default(0);
            $table->decimal('pending_balance')->default(0);
            $table->integer('stage')->default(1); 
            $table->integer('times_received')->default(0);
            $table->boolean('giving');
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wheel_locals');
    }
};
