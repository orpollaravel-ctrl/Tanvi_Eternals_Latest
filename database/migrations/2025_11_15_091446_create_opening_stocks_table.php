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
        Schema::create('opening_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->decimal('quantity', 15, 2)->default(0);
            $table->decimal('mrp', 15, 2)->nullable();
            $table->decimal('sale_rate', 15, 2)->nullable();
            $table->decimal('purchase_price', 15, 2)->nullable();
            $table->timestamps();

            $table->unique('product_id'); // One opening stock per product
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opening_stocks');
    }
};
