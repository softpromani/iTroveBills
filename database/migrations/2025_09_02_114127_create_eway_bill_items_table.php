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
        Schema::create('eway_bill_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('eway_bill_id');
            $table->string('product_name');
            $table->string('product_desc')->nullable();
            $table->string('hsn_code', 20)->nullable();
            $table->decimal('taxable_amount', 15, 2)->default(0);
            $table->decimal('quantity', 15, 2)->default(0);
            $table->string('qty_unit', 20)->nullable();
            $table->decimal('sgst_rate', 5, 2)->default(0);
            $table->decimal('cgst_rate', 5, 2)->default(0);
            $table->decimal('igst_rate', 5, 2)->default(0);
            $table->decimal('cess_rate', 5, 2)->default(0);

            $table->timestamps();

            $table->foreign('eway_bill_id')->references('id')->on('eway_bills')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eway_bill_items');
    }
};
