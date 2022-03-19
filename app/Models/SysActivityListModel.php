<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SysActivityListModel extends Model
{
    use HasFactory;
    protected $table = 'sys_activity_list';
    protected $fillable = ['id', 'token', 'device_token', 'app_token', 'user_token', 'user_agent', 'ip_address', 'source', 'request_method', 'request_to', 'endpoint', 'role_token', 'request', 'crud_info', 'message', 'response', 'request_dt', 'response_dt', 'status', 'existence', 'added_by' ];

}
