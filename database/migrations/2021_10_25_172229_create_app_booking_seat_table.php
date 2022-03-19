<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppBookingSeatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_booking_seat', function (Blueprint $table) {
            $table->id();
            $table->string('token');
            $table->string('bus_token');
            $table->string('booking_token');
            $table->string('trip_breakdown_token');
            $table->string('seat_details');
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
        Schema::dropIfExists('app_booking_seat');
    }
}
