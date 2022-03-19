<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppBusListTable extends Migration
{
    /**
     * Stores Bus Information
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_bus_list', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->text('token')->nullable();

            $table->string('registration_number')->nullable();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();


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
        Schema::dropIfExists('app_bus_list');
    }
}
