<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppCounterAndTripRelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_counter_and_trip_rel', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->text('token')->nullable();

            $table->text('counter_token')->nullable();
            $table->text('trip_token')->nullable();
            $table->text('counter_type')->nullable();

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
        Schema::dropIfExists('app_counter_and_trip_rel');
    }
}
