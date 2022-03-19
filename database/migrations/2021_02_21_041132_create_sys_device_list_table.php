<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSysDeviceListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_device_list', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('token')->nullable();
            $table->text('app_token')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('source')->nullable();

            $table->text('device_id')->nullable()->comment('Unique Device ID Sent From Mobile App');
            $table->text('device_info')->nullable()->comment('Other Device Info Sent From Mobile App');

            $table->string('status', 50)->nullable();
            $table->boolean('existence')->nullable();
            $table->string('added_by')->nullable();     
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
        Schema::dropIfExists('sys_device_list');
    }
}
