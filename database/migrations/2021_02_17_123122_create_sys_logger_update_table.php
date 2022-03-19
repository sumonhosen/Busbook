<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSysLoggerUpdateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_logger_update', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('effected_id')->nullable();
            $table->string('effected_table')->nullable();
            $table->longText('old_data')->nullable();
            $table->longText('new_data')->nullable();
            $table->text('remark')->nullable();
            $table->text('updated_by')->nullable();            
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
        Schema::dropIfExists('sys_logger_update');
    }
}
