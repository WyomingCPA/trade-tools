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
            $table->enum('tools_type', ['bond', 'stock', 'etf', 'coins']);
            $table->float('open', 8, 6);
            $table->float('close', 8, 6);
            $table->float('high', 8, 6);
            $table->float('low', 8, 6);
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
