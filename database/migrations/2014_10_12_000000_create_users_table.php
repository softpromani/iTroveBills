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
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('app_logo')->nullable();
            $table->string('app_name')->nullable();
            $table->string('mobile')->unique();
            $table->string('gst')->unique()->comment('here user fill GST or TPN or PAN');
            $table->unsignedBigInteger('inv_tax_type')->unique();
            $table->string('fcm')->unique();
            $table->string('address')->unique();
            $table->string('pin')->unique();
            $table->unsignedBigInteger('state_id')->unique();
            $table->unsignedBigInteger('city_id')->unique();
            $table->unsignedBigInteger('country_id')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
