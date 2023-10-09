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
            Schema::create('subscription_packs', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('slug');
                $table->string('short_desc');
                $table->bigInteger('no_of_bills_inmonth')->nullable();
                $table->bigInteger('no_of_email_inmonth')->nullable();
                $table->bigInteger('no_of_customer')->nullable();
                $table->bigInteger('no_of_company')->nullable();
                $table->decimal('mrp',10,2)->default(0);
                $table->enum('discount_type',['none','flat','percentage'])->default('none');
                $table->integer('discount')->default(0);
                $table->bigInteger('month')->nullable();
                $table->unsignedBigInteger('status');
                $table->softDeletes();
                $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_packs');
    }
};
