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
        Schema::create('eway_bills', function (Blueprint $table) {
            $table->id();
            // Status + Supervisor
            $table->enum('status', ['active', 'cancelled'])->default('active');
            $table->unsignedBigInteger('supervisor_id')->nullable();

            // Document
            $table->string('doc_type', 50)->nullable();
            $table->string('doc_no', 100)->nullable();
            $table->date('doc_date')->nullable();

            // Supply info
            $table->string('supply_type', 50)->nullable();
            $table->string('sub_supply_type', 50)->nullable();
            $table->string('sub_supply_desc')->nullable();
            $table->unsignedInteger('transaction_type')->nullable();

            // Parties (from/to)
            $table->string('from_gstin', 20)->nullable();
            $table->string('from_trd_name')->nullable();
            $table->string('from_addr1')->nullable();
            $table->string('from_addr2')->nullable();
            $table->string('from_place')->nullable();
            $table->unsignedInteger('from_pincode')->nullable();
            $table->unsignedInteger('from_state_code')->nullable();

            $table->string('to_gstin', 20)->nullable();
            $table->string('to_trd_name')->nullable();
            $table->string('to_addr1')->nullable();
            $table->string('to_addr2')->nullable();
            $table->string('to_place')->nullable();
            $table->unsignedInteger('to_pincode')->nullable();
            $table->unsignedInteger('to_state_code')->nullable();

            $table->unsignedInteger('act_from_state_code')->nullable();
            $table->unsignedInteger('act_to_state_code')->nullable();

            // Dispatch / ShipTo
            $table->string('dispatch_from_gstin', 20)->nullable();
            $table->string('dispatch_from_trade_name')->nullable();
            $table->string('ship_to_gstin', 20)->nullable();
            $table->string('ship_to_trade_name')->nullable();

            // Transport
            $table->string('trans_mode', 20)->nullable();
            $table->string('trans_distance', 20)->nullable();
            $table->string('transporter_name')->nullable();
            $table->string('transporter_id')->nullable();
            $table->string('trans_doc_no')->nullable();
            $table->date('trans_doc_date')->nullable();
            $table->string('vehicle_no', 50)->nullable();
            $table->string('vehicle_type', 20)->nullable();

            // Values
            $table->decimal('total_value', 15, 2)->nullable();
            $table->decimal('tot_inv_value', 15, 2)->nullable();
            $table->decimal('cgst_value', 15, 2)->nullable();
            $table->decimal('sgst_value', 15, 2)->nullable();
            $table->decimal('igst_value', 15, 2)->nullable();
            $table->decimal('cess_value', 15, 2)->nullable();
            $table->decimal('cess_non_advol_value', 15, 2)->nullable();

            // EWB Specific
            $table->string('ewb_eway_bill_no')->nullable()->unique();
            $table->timestamp('ewb_created_at')->nullable();
            $table->decimal('ewb_quantity', 15, 2)->nullable();
            $table->string('ewb_grn_no')->nullable();

            // Store raw API data for auditing/debug
            $table->longText('json_data')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eway_bills');
    }
};
