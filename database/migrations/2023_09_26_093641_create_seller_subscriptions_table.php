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
        Schema::create('seller_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seller_id');
            $table->unsignedBigInteger('subscription_id');
            $table->decimal('order_ammount',10,2);
            $table->decimal('paid_ammount',10,2);
            $table->date('purchase_date');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->unsignedBigInteger('status')->default(true);
            $table->timestamps();
            $table->foreign('seller_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seller_subscriptions');
    }
};
