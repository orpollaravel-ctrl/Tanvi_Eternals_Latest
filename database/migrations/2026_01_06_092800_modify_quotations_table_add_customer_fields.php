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
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropColumn('customer_id');
            $table->dropColumn('customer_code');
            $table->string('customer_name')->after('id');
            $table->string('pincode')->after('customer_name');
            $table->string('state')->after('pincode');
            $table->string('city')->after('state');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropColumn(['customer_name', 'pincode', 'state', 'city']);
            $table->unsignedBigInteger('customer_id')->after('id');
            $table->string('customer_code')->after('customer_id');
        });
    }
};
