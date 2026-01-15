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
        Schema::create('tool_assigns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('d_id')->nullable();
            $table->date('date')->nullable();
            $table->timestamps();

            $table->foreign('d_id')->references('id')->on('departments')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tool_assigns');
    }
};
