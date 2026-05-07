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
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('vehicle_no')->nullable()->change();
        });
        Schema::table('performa_invoices', function (Blueprint $table) {
            $table->string('vehicle_no')->nullable()->change();
        });
        Schema::table('plain_bills', function (Blueprint $table) {
            $table->string('vehicle_no')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('vehicle_no')->nullable(false)->change();
        });
        Schema::table('performa_invoices', function (Blueprint $table) {
            $table->string('vehicle_no')->nullable(false)->change();
        });
        Schema::table('plain_bills', function (Blueprint $table) {
            $table->string('vehicle_no')->nullable(false)->change();
        });
    }
};
