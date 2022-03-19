<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppPlaceRelatedKeywordListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_place_related_keyword_list', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->text('token')->nullable();
          $table->text('place_token')->nullable();
          $table->text('keyword')->nullable();
          $table->string('status', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('app_place_related_keyword_list');
    }
}
