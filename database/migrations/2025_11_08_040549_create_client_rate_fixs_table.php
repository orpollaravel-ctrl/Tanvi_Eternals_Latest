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
        Schema::create('client_rate_fixs', function (Blueprint $table) {
            $table->id();
            $table->string('serial_no');
            $table->string('transaction_no')->unique();
            $table->string('client_code');
            $table->string('client_name');
            $table->date('transaction_date');
            $table->string('sales_person');
            $table->decimal('weight', 15, 3)->nullable();
            $table->decimal('rate', 15, 2)->nullable();
            $table->decimal('amount', 20, 2)->nullable();            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_rate_fixs');
    }
};
