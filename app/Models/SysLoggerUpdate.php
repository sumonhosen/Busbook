<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SysLoggerUpdate extends Model
{
  use HasFactory;
  protected $table = 'sys_logger_update';
  protected $fillable = ['id', 'effected_id', 'effected_table', 'old_data', 'new_data', 'remark', 'updated_by', 'activity_token' ];

}
