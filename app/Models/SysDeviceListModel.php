<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SysDeviceListModel extends Model
{
  use HasFactory;
  protected $table = 'sys_device_list';
  protected $fillable = ['id', 'token', 'app_token', 'user_agent', 'ip_address', 'source', 'device_id', 'device_info', 'status', 'existence', 'added_by', 'activity_token', ];

}
