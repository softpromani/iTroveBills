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
        Schema::table('ledgers', function (Blueprint $table) {
            $table->unsignedBigInteger('seller_customer_id')->nullable()->change();
            $table->unsignedBigInteger('party_id')->nullable()->after('seller_customer_id');
            $table->string('particular_type')->nullable()->after('payment_type'); // Voucher / Bill
            $table->foreign('party_id')->references('id')->on('parties')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ledgers', function (Blueprint $table) {
            //
        });
    }
};
