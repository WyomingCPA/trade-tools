<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestStrategiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_strategies', function (Blueprint $table) {
            $table->id();
            $table->dateTime('start_period', $precision = 0);
            $table->dateTime('end_period', $precision = 0);  
            $table->string('figi');
            $table->string('strategy_name');
            $table->string('time_frame');
            $table->float('balance');
            $table->enum('status', ['success', 'nothing', 'fail', 'empty'])->default('empty');
            $table->boolean('is_completed')->default(0);
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
        Schema::dropIfExists('test_strategies');
    }
}
