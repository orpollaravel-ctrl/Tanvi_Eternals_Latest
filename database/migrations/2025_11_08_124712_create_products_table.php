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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->string('barcode_number')->unique()->nullable();
            $table->string('tool_code')->unique()->nullable();
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('cascade');
            $table->string('product_company')->nullable();
            $table->string('hsn_code')->nullable();
            $table->decimal('minimum_rate', 15, 2)->nullable();
            $table->decimal('maximum_rate', 15, 2)->nullable();
            $table->integer('minimum_quantity')->default(0);
            $table->integer('reorder_quantity')->default(0);
            $table->foreignId('unit_id')->constrained('units')->onDelete('restrict');
            $table->string('product_photo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
