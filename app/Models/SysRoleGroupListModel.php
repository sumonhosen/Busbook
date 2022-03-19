<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SysRoleGroupListModel extends Model
{
  use HasFactory;

  public $timestamps = false;
  protected $table = 'sys_role_group_list';
  protected $fillable = ['id', 'token', 'title', 'remark'];

}
