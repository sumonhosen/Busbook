<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSysAlertMessageListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_alert_message_list', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('message_code')->nullable();
            $table->text('en')->nullable();
            $table->text('bn')->nullable();
            $table->enum('type', ['Success', 'Error', 'Info', 'Warning'])->nullable();
            $table->boolean('showable')->nullable();            
            $table->string('status', 50)->nullable();
            $table->boolean('existence')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sys_alert_message_list');
    }
}
