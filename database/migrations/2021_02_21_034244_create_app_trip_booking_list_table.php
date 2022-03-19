<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppTripBookingListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_trip_booking_list', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('token')->nullable();

            $table->text('banner_token')->nullable();
            $table->text('trip_token')->nullable();
            $table->text('trip_breakdown_token')->nullable();
            $table->string('booking_number')->nullable()->comment('Auto Generated Unique Identifier Serial');

            $table->text('booking_counter')->nullable()->comment('Counter Token From Where The User Has Booked The Ticket');
            $table->text('boarding_counter')->nullable()->comment('Counter Token From Where The User Will Be Boarded');
            $table->text('departure_point')->nullable()->comment('Foreign From app_place_list Table');
            $table->text('related_departure_counter')->nullable()->comment('Foreign From app_counter_list Table');
            $table->text('destination_point')->nullable()->comment('Foreign From app_place_list Table');
            $table->text('related_destination_counter')->nullable()->comment('Foreign From app_counter_list Table');

            $table->text('booked_for')->nullable()->comment('User Token');
            $table->string('name_on_ticket')->nullable()->comment('Full Name');
            $table->string('emergency_contact')->nullable()->comment('Phone Number');
            $table->string('pickup_note')->nullable()->comment('Ticket Booker Fillable');

            $table->date('journey_date')->nullable()->comment('Date of Journey');
            $table->time('journey_time')->nullable()->comment('Date of Journey');

            $table->string('status', 50)->nullable();
            $table->boolean('existence')->nullable();
            $table->text('added_by')->nullable();
            $table->text('activity_token')->nullable();
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
        Schema::dropIfExists('app_trip_booking_list');
    }
}
