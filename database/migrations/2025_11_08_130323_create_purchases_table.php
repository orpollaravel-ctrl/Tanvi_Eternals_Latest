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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_party_id')->constrained('purchase_parties')->onDelete('cascade');
            $table->date('bill_date');
            $table->string('bill_number')->unique();
            $table->date('delivery_date');
            $table->decimal('total_invoice_amount', 12, 2);
            $table->string('bill_photo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
