<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSysRoleListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_role_list', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('token')->nullable()->comment('No Auto Generatable, Unique Token Created By Poster');
            $table->text('role_group_token')->nullable();
            $table->string('title')->nullable();
            $table->string('details')->nullable();
            $table->string('crud_type')->nullable()->comment('Create/Read/Update/Delete');
            $table->string('entity')->nullable()->comment('Business/Bus/Counter/Ticket etc.');
            $table->string('permission')->nullable()->comment('Regular/Wildcard');
            $table->text('remark')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sys_role_list');
    }
}
