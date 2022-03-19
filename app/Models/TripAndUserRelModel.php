<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripAndUserRelModel extends Model
{
  use HasFactory;
  protected $table = 'app_trip_and_user_rel';
  protected $fillable = ['id', 'token', 'trip_token', 'user_token', 'role_token', 'status', 'existence', 'added_by', 'activity_token'];

}
