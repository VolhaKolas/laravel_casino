<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDemoUserCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demo_user_cards', function (Blueprint $table) {
            $table->increments('duc_id');
            $table->integer('du_id')->length(10)->unsigned();
            $table->foreign('du_id')->references('du_id')->on('demo_users')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('duc_card');
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
        Schema::dropIfExists('demo_user_cards');
    }
}
