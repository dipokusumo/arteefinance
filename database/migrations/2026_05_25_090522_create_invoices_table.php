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
            $table->string('invoice_number')->nullable();
            $table->string('reference_number')->nullable();
            $table->boolean('input_status')->default(false);
            $table->boolean('payment_status')->default(false);
            $table->date('invoice_date')->nullable();
            $table->date('payment_date')->nullable();
            $table->integer('base_amount')->nullable();
            $table->integer('gross_up_amount')->nullable();
            $table->integer('pph_amount')->nullable();
            $table->integer('take_home_pay')->nullable();
            $table->integer('djp_tax_amount')->nullable();
            $table->string('note')->nullable();
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
