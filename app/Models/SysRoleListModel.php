<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SysRoleListModel extends Model
{
  use HasFactory;

  public $timestamps = false;
  protected $table = 'sys_role_list';
  protected $fillable = ['id', 'token', 'role_group_token', 'title', 'details', 'crud_type', 'entity', 'permission', 'remark'];

  
}
