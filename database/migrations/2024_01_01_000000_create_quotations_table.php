<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('contact');
            $table->string('customer_code');
            $table->enum('metal', ['yellow gold', 'rose gold', 'white gold']);
            $table->enum('purity', ['22k', '18k', '14k', '9k']);
            $table->enum('diamond', ['SI-IJ', 'SI-GH', 'VS-GH', 'VVS-EF', 'VS-SIGH', 'VS-ISHI', 'SI-HI', 'CVD']);
            $table->string('women_ring_size_from')->nullable();
            $table->string('women_ring_size_to')->nullable();
            $table->string('men_ring_size_from')->nullable();
            $table->string('men_ring_size_to')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};