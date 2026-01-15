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
        Schema::table('dealer_rate_fixes', function (Blueprint $table) {
             $table->dropColumn('dealer_id');
            $table->foreignId('client_id')->after('id')->constrained();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dealer_rate_fixes', function (Blueprint $table) {
            //
        });
    }
};
