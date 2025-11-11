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
        Schema::create('client_rate_cut_pendings', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no');
            $table->string('client_code')->nullable();
            $table->string('client_name');
            $table->date('transaction_date');
            $table->string('sales_person');
            $table->decimal('pure_weight', 10, 3);
            $table->decimal('sale_rate', 10, 2);
            $table->decimal('amount', 12, 2)->nullable();
            $table->decimal('rate_cut', 10, 2)->nullable();
            $table->decimal('amt', 12, 2)->nullable();
            $table->decimal('diff_amt', 12, 2)->nullable();
            $table->string('transaction_no')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_rate_cut_pendings');
    }
};
