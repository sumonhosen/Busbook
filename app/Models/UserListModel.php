<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserListModel extends Model
{
  use HasFactory;

  protected $table = 'app_user_list';
  protected $fillable = ['id', 'token', 'name', 'phone', 'email', 'password', 'type', 'address', 'status', 'existence', 'added_by', 'activity_token' ];

}
