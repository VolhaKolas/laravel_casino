<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComputerCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('computer_cards', function (Blueprint $table) {
            $table->increments('cc_id');
            $table->integer('c_id')->length(10)->unsigned();
            $table->foreign('c_id')->references('c_id')->on('computers')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('cc_card');
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('computer_cards');
    }
}
