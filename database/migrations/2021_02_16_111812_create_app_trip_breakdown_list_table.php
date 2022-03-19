<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppTripBreakdownListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_trip_breakdown_list', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->text('token')->nullable();

            $table->text('banner_token')->nullable();
            $table->text('trip_token')->nullable();
            
            $table->text('departure_point')->nullable()->comment('Foreign From app_place_list Table');
            $table->text('related_departure_counter')->nullable()->comment('Foreign From app_counter_list Table');
            $table->text('destination_point')->nullable()->comment('Foreign From app_place_list Table');
            $table->text('related_destination_counter')->nullable()->comment('Foreign From app_counter_list Table');
            $table->time('departure_time')->nullable();
            $table->time('destination_time')->nullable();

            $table->string('fare')->nullable();
            $table->string('online_charge')->nullable();
            
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
        Schema::dropIfExists('app_trip_breakdown_list');
    }
}
