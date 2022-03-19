<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppOtpListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_otp_list', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('user_token')->nullable();
            $table->string('phone')->nullable();
            $table->string('otp')->nullable();
            $table->string('status', 50)->nullable()->comment('Queued/Confirmed');
            $table->text('queued_activity_token')->nullable();
            $table->text('confirmed_activity_token')->nullable();
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
        Schema::dropIfExists('app_otp_list');
    }
}
