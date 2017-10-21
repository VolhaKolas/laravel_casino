<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComputersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('computers', function (Blueprint $table) {
            $table->increments('c_id');
            $table->integer('du_id')->length(10)->unsigned();
            $table->foreign('du_id')->references('du_id')->on('demo_users')->onDelete('cascade')->onUpdate('cascade');
            $table->string('c_name', 100);
            $table->integer('c_place')->nullable();
            $table->integer('c_money')->default(\Casino\Classes\Game\Players::MONEY);
            $table->integer('c_bet')->default(0);
            $table->integer('c_dealer')->default(0);
            $table->integer('c_current_better')->default(0);
            $table->integer('c_last_better')->default(0);
            $table->integer('c_fold')->default(0);
            $table->integer('c_dealer_card')->nullable();
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
        Schema::dropIfExists('computers');
    }
}
