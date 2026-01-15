<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDealerRateFixesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dealer_rate_fixes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dealer_id')->constrained();
            $table->foreignId('fixed_by')->constrained('users');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->date('drf_date');
            $table->decimal('quantity', 10, 3);
            $table->decimal('rate', 10, 3);
            $table->decimal('amount', 13, 3);
            $table->string('remark')->nullable();
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
        Schema::dropIfExists('dealer_rate_fixes');
    }
}
