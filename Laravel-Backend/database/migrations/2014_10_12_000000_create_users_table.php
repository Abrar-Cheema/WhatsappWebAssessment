<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('stripe_account_id')->nullable();
            $table->enum('stripe_onboarding',['Pending','Completed'])->default('Pending');
            $table->boolean('payouts_enabled')->default(0);
            $table->string('google_uid')->nullable();
            $table->string('facebook_uid')->nullable();
            $table->string('apple_uid')->nullable();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->foreignId('picture')->nullable()->constrained('images');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->integer('otp')->nullable();
            $table->timestamp('otp_expires_at')->nullable();
            $table->boolean('active')->default(true);
            $table->string('company')->nullable();
            $table->string('category')->nullable();
            $table->text('description')->nullable();
            $table->boolean('phone_notification')->default(false);
            $table->boolean('email_notification')->default(false);
            $table->boolean('sms_notification')->default(false);
            $table->string('verify_key')->nullable();
            $table->timestamp('key_expires_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
