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
        Schema::create('bullion_rates', function (Blueprint $table) {
            $table->id();
            $table->string('serial_no'); 
            $table->string('rate_cut_on_off');          
            $table->string('name');                      
            $table->date('date');                       

            $table->decimal('quantity', 15, 3)->default(0);          
            $table->decimal('rate', 15, 2)->default(0);             
            $table->decimal('amount', 20, 2)->default(0);     
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bullion_rates');
    }
};
