<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountdownTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_countdown_times', function (Blueprint $table) {
            $table->id();
            $table->string('token');
            $table->string('bus_token');
            $table->string('user_token');
            $table->string('counter_token');
            $table->string('breakdown_token');
            $table->string('seat_details');
            $table->string('set_time');
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
        Schema::dropIfExists('countdown_times');
    }
}
