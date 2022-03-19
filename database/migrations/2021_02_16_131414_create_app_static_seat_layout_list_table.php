<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppStaticSeatLayoutListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_static_seat_layout_list', function (Blueprint $table) {
            
            $table->bigIncrements('id');
            $table->text('token')->nullable();

            $table->string('title')->nullable();
            $table->tinyInteger('storey')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('app_static_seat_layout_list');
    }
}
