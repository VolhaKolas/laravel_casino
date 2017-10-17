<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string("login", 50);
            $table->string('name', 100);
            $table->string('lastname', 100);
            $table->string('email', 100)->unique();
            $table->string('password', 100);
            $table->integer('u_time');
            $table->integer('u_place')->nullable();
            $table->integer('u_money')->default(\Casino\Classes\Game\Players::MONEY);
            $table->integer('u_bet')->default(0);
            $table->integer('u_dealer')->default(0);
            $table->integer('u_current_better')->default(0);
            $table->integer('u_last_better')->default(0);
            $table->integer('u_fold')->default(0);
            $table->string('u_photo', 100)->nullable();
            $table->integer('u_offer')->default(0);
            $table->integer('u_answer')->default(0);
            $table->string('u_socket', 15)->nullable();
            $table->integer('u_dealer_card')->nullable();
            $table->rememberToken();
            $table->timestamps();
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
        Schema::dropIfExists('users');
    }
}
