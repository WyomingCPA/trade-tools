<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAimEtfEtfTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aim-etf_etf', function (Blueprint $table) {
            $table->primary(['etf_id','aim-etf_id']);
            $table->bigInteger('etf_id')->unsigned();
            $table->bigInteger('aim-etf_id')->unsigned();
            $table->timestamps();
            $table->foreign('etf_id')
                ->references('id')
                ->on('etfs');
             $table->foreign('aim-etf_id')
                ->references('id')
                ->on('aim-etf');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
