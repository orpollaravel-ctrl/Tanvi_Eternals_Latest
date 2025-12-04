<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bullion_rate_fix_id')->constrained();
            $table->foreignId('receipt_id')->constrained();
            $table->decimal('quantity', 10, 3);
            $table->timestamps();
        });

        Schema::create('payment_transaction', function (Blueprint $table) {            
            $table->foreignId('transaction_id')->constrained();
            $table->foreignId('payment_id')->constrained();
            $table->decimal('amount', 13, 3);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_transaction');
        Schema::dropIfExists('transactions');        
    }
}
