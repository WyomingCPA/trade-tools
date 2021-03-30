<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBondDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bond_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bond_id');

            $table->float('current_yield');
            $table->float('maturity_yield');
            $table->dateTime('maturity_date');
            $table->float('maturity_calloption');
            $table->dateTime('date_calloption');
            $table->dateTime('paymant_date');
            $table->dateTime('date_payment_coupon');
            $table->float('accumulated_coupon');
            $table->float('amount_coupon');
            $table->float('nominal');
            $table->integer('period_payment');
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
        Schema::dropIfExists('bond_details');
    }
}
