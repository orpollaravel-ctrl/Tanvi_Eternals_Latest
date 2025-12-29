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
        Schema::table('targets', function (Blueprint $table) {
            $table->dropColumn('target_qty');
            $table->string('phone')->after('target_date')->nullable();
            $table->string('time')->after('phone')->nullable();
            $table->string('visit_card')->after('time')->nullable();
            $table->string('shop_photo')->after('time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('targets', function (Blueprint $table) {
            //
        });
    }
};
