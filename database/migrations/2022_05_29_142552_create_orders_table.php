<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('figi');
            $table->string('order_id');
            $table->integer('strategy_id')->unsigned();
            $table->string('strategy_name');
            $table->text('note');
            $table->enum('status', ['success', 'nothing', 'fail', 'empty'])->default('empty');
            $table->float('current_price');
            $table->integer('quantity');
            $table->string('direction');
            $table->string('order_type');
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
        Schema::dropIfExists('orders');
    }
}
