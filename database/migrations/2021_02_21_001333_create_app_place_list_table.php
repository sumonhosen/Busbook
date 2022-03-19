<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppPlaceListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_place_list', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('token')->nullable();
            $table->string('title')->nullable();
            $table->string('title_bn')->nullable();
            $table->string('status', 50)->nullable();

            $table->boolean('existence')->nullable();
            $table->text('added_by')->nullable();
            $table->timestamps();
            $table->text('activity_token')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('app_place_list');
    }
}
