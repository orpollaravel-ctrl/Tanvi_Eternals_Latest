<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();

            // Basic info
            $table->string('name', 150);
            $table->string('code', 50)->unique();

            // Tax details
            $table->string('gst_number', 20)->nullable();
            $table->string('pan_number', 20)->nullable();
            $table->string('adhard_number', 20)->nullable();

            // Bank details
            $table->string('bank_account_number', 30)->nullable();
            $table->string('ifsc_code', 15)->nullable();

            // Contact info
            $table->longText('address', 255)->nullable();
            $table->string('party_name', 150)->nullable();
                
            $table->string('contact_no', 15)->nullable();
            $table->string('email', 150)->nullable();

            // Sales reference
            $table->string('salesman', 100)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
