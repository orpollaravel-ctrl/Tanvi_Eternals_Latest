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
        Schema::create('dsrs', function (Blueprint $table) {
            $table->id();

            $table->integer('client_id');

            $table->text('client_type');

            $table->integer('no_of_shops')->nullable();
            $table->string('visiting_card_photo')->nullable();
            $table->string('shop_photo')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dsrs');
    }
};
