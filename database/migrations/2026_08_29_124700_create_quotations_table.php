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
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('customer_company_id')->nullable();
            $table->json('customer_data')->nullable();
            $table->string('quotation_number');
            $table->date('quotation_date');
            $table->string('title')->nullable();
            $table->string('recipient_designation')->nullable();
            $table->string('subject')->nullable();
            $table->json('cover_letter_content')->nullable();
            $table->double('total_amount', 15, 2)->default(0);
            $table->text('tax_note')->nullable();
            $table->json('terms_and_conditions')->nullable();
            $table->string('status')->default('draft');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
