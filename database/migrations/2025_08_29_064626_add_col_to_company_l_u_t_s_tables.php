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
        Schema::table('company_l_u_t_s', function (Blueprint $table) {
            $table->string('starting_bill_count')->nullable()->after('lut_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_l_u_t_s_tables', function (Blueprint $table) {
            //
        });
    }
};
