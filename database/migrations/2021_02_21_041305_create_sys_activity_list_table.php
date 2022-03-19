<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSysActivityListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_activity_list', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('token')->nullable();
            $table->text('device_token')->nullable();
            $table->text('app_token')->nullable();
            $table->text('user_token')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('source')->nullable();
            $table->string('request_method')->nullable();
            $table->string('request_to')->nullable()->comment('Web/API');
            $table->text('endpoint')->nullable();
            $table->text('role_token')->nullable();
            $table->longText('request')->nullable();
            $table->text('crud_info')->nullable();
            $table->text('message')->nullable();
            $table->longText('response')->nullable();
            $table->dateTime('request_dt')->nullable();
            $table->dateTime('response_dt')->nullable();
            $table->string('status', 50)->nullable();
            $table->boolean('existence')->nullable();
            $table->string('added_by')->nullable();     
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
        Schema::dropIfExists('sys_activity_list');
    }
}
