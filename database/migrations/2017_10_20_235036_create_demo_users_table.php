<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDemoUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demo_users', function (Blueprint $table) {
            $table->increments('du_id');
            $table->string('du_name', 100);
            $table->integer('du_time');
            $table->integer('du_place')->nullable();
            $table->integer('du_money')->default(\Casino\Classes\Game\Players::MONEY);
            $table->integer('du_bet')->default(0);
            $table->integer('du_dealer')->default(0);
            $table->integer('du_current_better')->default(0);
            $table->integer('du_last_better')->default(0);
            $table->integer('du_fold')->default(0);
            $table->integer('du_dealer_card')->nullable();
            $table->integer('du_table_time')->nullable();
            $table->integer('du_table_money')->default(0);
            $table->integer('du_table_flop1')->nullable();
            $table->integer('du_table_flop2')->nullable();
            $table->integer('du_table_flop3')->nullable();
            $table->integer('du_table_turn')->nullable();
            $table->integer('du_table_river')->nullable();
            $table->integer('du_table_open')->default(0);
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
        Schema::dropIfExists('demo_users');
    }
}
