<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppOTPListModel extends Model
{
  use HasFactory;

  protected $table = 'app_otp_list';
  protected $fillable = ['id', 'user_token', 'phone', 'otp', 'status', 'queued_activity_token', 'confirmed_activity_token'];

}
