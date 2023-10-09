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
            $table->string('address');
            $table->string('city');
            $table->string('pin');
            $table->string('gstin')->comment('here user fill GST or TPN or PAN');
            $table->string('iec');
            $table->string('mobile');
            $table->string('email');
            $table->string('invoice_series');
            $table->string('logo');
            $table->string('sign');
            $table->string('bank_name');
            $table->string('bank_branch');
            $table->string('bank_ifsc');
            $table->string('bank_account_no');
            $table->string('ad_code');
            $table->unsignedBigInteger('status');
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
