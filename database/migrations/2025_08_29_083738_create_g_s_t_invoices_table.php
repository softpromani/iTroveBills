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
        Schema::create('g_s_t_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number');
            $table->date('invoice_date');
            $table->double('total_ammount',10,2)->default(0.00);
            $table->double('paid_ammount',10,2)->default(0.00);
            $table->decimal('tax_amount', 12,2)->default(0.00);
            $table->decimal('subtotal_amount', 12,2)->default(0.00);
            $table->unsignedBigInteger('payment_status');
            $table->double('total_weight',10,2)->default(0.00);
            $table->string('vehicle_no');
            $table->bigInteger('no_packets')->nullable();
            $table->unsignedBigInteger('customer_company_id')->comment('link with the company table on basis of seller, customer_company');
            $table->unsignedBigInteger('company_id');
            $table->string('lut_id')->nullable();
            $table->longText('bill_data')->nullable();
            $table->foreign('customer_company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('g_s_t_invoices');
    }
};
