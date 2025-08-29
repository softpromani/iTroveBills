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
        Schema::create('g_s_t_invoice_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id');
            $table->string('desc_product');
            $table->string('hsn_code');
            $table->string('unit');
            $table->double('quantity',10,2)->default(0.00);
            $table->double('weight',10,2)->default(0.00);
            $table->double('rate',10,2)->default(0.00);
            $table->decimal('gst_percentage',12,2)->default(0.00);
            $table->decimal('gst_amount',12,2)->default(0.00);
            $table->decimal('subtotal_amount',12,2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('g_s_t_invoice_items');
    }
};
