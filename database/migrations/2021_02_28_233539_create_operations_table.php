<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('operation_id');
            $table->string('status');
            $table->string('figi');
            $table->string('payment');
            $table->float('price');
            $table->float('commission');
            $table->string('currency');
            $table->string('instrumentType');
            $table->dateTime('date', $precision = 0); 
            $table->string('operationType');                               
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
        Schema::dropIfExists('operations');
    }
}
