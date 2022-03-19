<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SysLoggerDelete extends Model
{
  use HasFactory;
  protected $table = 'sys_logger_delete';
  protected $fillable = ['id', 'effected_id', 'effected_table', 'remark', 'deleted_by', 'activity_token'];
}