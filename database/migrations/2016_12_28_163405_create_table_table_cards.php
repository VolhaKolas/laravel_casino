<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTableCards extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_cards', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('table_id')->unsigned();
            $table->integer('table_money')->default(0);
            $table->integer('flop1')->nullable();
            $table->integer('flop2')->nullable();
            $table->integer('flop3')->nullable();
            $table->integer('turn')->nullable();
            $table->integer('river')->nullable();
            $table->integer('open')->default(0);


            $table->foreign('table_id')->references('id')->on('tables')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_cards');
    }
}
