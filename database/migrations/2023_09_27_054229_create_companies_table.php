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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('pin')->nullable();
            $table->string('gstin')->comment('here user fill GST or TPN or PAN');
            $table->string('iec')->nullable();
            $table->string('mobile');
            $table->string('email');
            $table->string('invoice_series')->nullable();
            $table->string('logo')->nullable();
            $table->string('sign')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_branch')->nullable();
            $table->string('bank_ifsc')->nullable();
            $table->string('bank_account_no')->nullable();
            $table->string('ad_code')->nullable();
            $table->unsignedBigInteger('seller_id')->nullable();
            $table->unsignedBigInteger('status');
            $table->enum('tax_type', ['gst', 'tpn', 'pan'])->default('gst');
            $table->unsignedBigInteger('invoice_template_id')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
