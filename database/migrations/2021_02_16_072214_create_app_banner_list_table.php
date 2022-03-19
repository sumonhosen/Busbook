<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppBannerListTable extends Migration
{
    /**
     * Stores All Banner Information
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_banner_list', function (Blueprint $table) {
            
            $table->bigIncrements('id');
            $table->text('token')->nullable();

            $table->string('title')->nullable();
            $table->string('title_bangla')->nullable();


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
        Schema::dropIfExists('app_banner_list');
    }
}
