<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCandlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tools_id')->unsigned();
            $table->enum('tools_type', ['bond', 'stock']);
            $table->foreign('tools_id')->references('id')->on('bonds');
            $table->float('open');
            $table->float('close');
            $table->float('high');
            $table->float('low');
            $table->integer('volume');
            $table->dateTime('time', $precision = 0);
            $table->string('interval');          
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
        Schema::dropIfExists('candles');
    }
}
