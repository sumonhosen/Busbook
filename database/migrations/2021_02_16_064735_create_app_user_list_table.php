<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppUserListTable extends Migration
{
  /**
   * Stores All User Information
   *
   * @return void
   */
  public function up()
  {
    Schema::create('app_user_list', function (Blueprint $table) {

      $table->bigIncrements('id');
      $table->text('token')->nullable();

      $table->string('name')->nullable();
      $table->string('phone')->nullable();
      $table->string('email')->nullable();
      $table->text('password')->nullable();

      $table->string('type', 50)->nullable();
      $table->text('address')->nullable();
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
    Schema::dropIfExists('app_user_list');
  }
}
