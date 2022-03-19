<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppBannerAndBannerRelTable extends Migration
{
    /**
     * Stores Parent Child Relation Between The Banners
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_banner_and_banner_rel', function (Blueprint $table) {
            
            $table->bigIncrements('id');
            $table->text('token')->nullable();

            $table->text('parent_banner_token')->nullable();
            $table->text('child_banner_token')->nullable();


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
        Schema::dropIfExists('app_banner_and_banner_rel');
    }
}
