<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SysUserSessionListModel extends Model
{
  use HasFactory;
  protected $table = 'sys_user_session_list';
  protected $fillable = ['id', 'token', 'app_token', 'user_token', 'user_agent', 'ip_address', 'source', 'starting_device_token', 'started_at', 'ending_device_token', 'ended_at', 'status', 'existence', 'added_by', 'activity_token' ];  

}
