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
        // Rename old table to new table name
        if (Schema::hasTable('payments') && !Schema::hasTable('newpayments')) {
            Schema::rename('payments', 'newpayments');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert rename (if needed)
        if (Schema::hasTable('newpayments') && !Schema::hasTable('payments')) {
            Schema::rename('newpayments', 'payments');
        }
    }
};
