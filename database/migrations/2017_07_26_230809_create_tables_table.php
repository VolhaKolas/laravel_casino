<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tables', function (Blueprint $table) {
            $table->increments("t_id");
            $table->integer('t_time')->nullable();
            $table->integer('t_money')->default(0);
            $table->integer('t_flop1')->nullable();
            $table->integer('t_flop2')->nullable();
            $table->integer('t_flop3')->nullable();
            $table->integer('t_turn')->nullable();
            $table->integer('t_river')->nullable();
            $table->integer('t_open')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tables');
    }
}
