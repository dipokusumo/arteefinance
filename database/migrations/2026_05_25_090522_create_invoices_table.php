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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('taxpayer_id')->nullable()->constrained('taxpayers')->onDelete('set null');
            $table->foreignId('pph_type_id')->nullable()->constrained('pph_types')->onDelete('set null');
            $table->foreignId('pic_id')->nullable()->constrained('pics')->onDelete('set null');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('invoice_number');
            $table->string('reference_number');
            $table->boolean('input_status')->default(false);
            $table->boolean('payment_status')->default(false);
            $table->date('invoice_date');
            $table->date('payment_date');
            $table->integer('base_amount');
            $table->integer('gross_up_amount');
            $table->integer('pph_amount');
            $table->integer('take_home_pay');
            $table->integer('djp_tax_amount');
            $table->string('note');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
