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
        Schema::create('bullion_purchases', function (Blueprint $table) {
            $table->id();
            $table->string('serial_no');       
            $table->date('transaction_date');   
            $table->string('name');            
            $table->decimal('converted_weight', 15, 3); 
            $table->decimal('purchase_rate', 15, 2); 
            $table->decimal('amount', 20, 2); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bullion_purchases');
    }
};
