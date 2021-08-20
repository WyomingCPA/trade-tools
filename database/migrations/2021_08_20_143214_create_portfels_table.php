<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePortfelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('portfels', function (Blueprint $table) {
            $table->id();
            $table->enum('tools_type', ['bond', 'stock', 'etf', 'currency']);
            $table->string('figi');
            $table->string('ticker');
            $table->string('isin');
            $table->string('currency');
            $table->string('name');
            $table->integer('lots');
            $table->float('expectedYieldValue', 10, 6);
            $table->float('averagePositionPrice', 10, 6);
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
        Schema::dropIfExists('portfels');
    }
}
