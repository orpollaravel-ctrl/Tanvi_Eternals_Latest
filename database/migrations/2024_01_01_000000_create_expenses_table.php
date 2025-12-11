<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['travel expense', 'food expense', 'hotel expense', 'other expense']);
            $table->date('date');
            $table->decimal('amount', 10, 2);
            $table->text('remark')->nullable();
            $table->string('bill_upload')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('expenses');
    }
};