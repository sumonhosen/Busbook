<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsSwitchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_switches', function (Blueprint $table) {
            $table->id();
            $table->integer('counter_sms');
            $table->integer('app_sms');
            $table->string('admin_counter_sms');
            $table->string('admin_app_sms');
            $table->integer('is_admin_sms');
            $table->boolean('status');
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
        Schema::dropIfExists('sms_switches');
    }
}
