<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmaDayIndicatorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ema_day_indicator', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('stock_id')->unsigned();
            $table->enum('action', ['sell', 'buy', 'nothing']);
            $table->boolean('send_telegramm');
            $table->timestamps();
            $table->foreign('stock_id')
                    ->references('id')
                    ->on('stocks')
                    ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ema_day_indicator');
    }
}
