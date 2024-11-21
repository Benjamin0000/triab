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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('mobile_number')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->timestamp('last_password_change')->nullable();
            $table->integer('type')->default(0); //type of user
            $table->string('gnumber')->index(); //number in the app. 
            $table->uuid('ref_by')->nullable();
            $table->uuid('placed_by')->nullable(); //this will take precedence in terms of reward

            $table->decimal('main_balance', 65, 2)->default(0);
            $table->decimal('reward_balance', 65, 2)->default(0); // in main currency like cashback and the rest. 
            $table->decimal('token_balance', 65, 5)->default(0);
            $table->decimal('health_token', 65, 2)->default(0); 
            $table->bigInteger('mpp')->default(0); 
            $table->bigInteger('pv')->default(0);
            $table->bigInteger('total_referrals')->default(0); 

            $table->integer('rank')->default(0); 
            $table->integer('package_id')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('email');
            $table->timestamps();
        });

        Schema::create('verify_email_tokens', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('email');
            $table->timestamps();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('verify_email_tokens');
        Schema::dropIfExists('sessions');
    }
};
