<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIdeasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ideas', function (Blueprint $table) {
            $table->id();
            $table->string('figi');
            $table->string('name');
            $table->enum('action', ['long', 'short'])->default('long');
            $table->integer('min_period');
            $table->float('aim_price');
            $table->json('data')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['draft', 'research', 'open', 'close'])->default('draft');
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
        Schema::dropIfExists('ideas');
    }
}
