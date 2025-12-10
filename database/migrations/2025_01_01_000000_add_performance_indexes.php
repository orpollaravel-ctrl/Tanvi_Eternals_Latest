<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Add indexes for better performance
        Schema::table('users', function (Blueprint $table) {
            $table->index('email');
            $table->index('active');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->index('name');
            $table->index('category_id');
        });

        Schema::table('purchases', function (Blueprint $table) {
            $table->index('vendor_id');
            $table->index('created_at');
        });

        Schema::table('tool_assigns', function (Blueprint $table) {
            $table->index('employee_id');
            $table->index('department_id');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['email']);
            $table->dropIndex(['active']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['name']);
            $table->dropIndex(['category_id']);
        });

        Schema::table('purchases', function (Blueprint $table) {
            $table->dropIndex(['vendor_id']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('tool_assigns', function (Blueprint $table) {
            $table->dropIndex(['employee_id']);
            $table->dropIndex(['department_id']);
            $table->dropIndex(['created_at']);
        });
    }
};