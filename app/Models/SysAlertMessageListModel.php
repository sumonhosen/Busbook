<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SysAlertMessageListModel extends Model
{
    use HasFactory;
    protected $table = 'sys_alert_message_list';
    protected $fillable = ['id', 'message_code', 'en', 'bn', 'type', 'showable', 'status', 'existence' ];
}
