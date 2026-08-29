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
        Schema::create('quotation_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quotation_id');
            $table->integer('item_order')->default(1);
            $table->string('title')->nullable();
            $table->json('description_points')->nullable();
            $table->string('hsn_code')->nullable();
            $table->string('unit')->nullable();
            $table->double('quantity', 15, 2)->default(1);
            $table->double('unit_rate', 15, 2)->default(0);
            $table->double('total_rate', 15, 2)->default(0);
            $table->timestamps();

            $table->foreign('quotation_id')->references('id')->on('quotations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_items');
    }
};
