<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusAndTripRelModel extends Model
{
  use HasFactory;

  protected $table = 'app_bus_and_trip_rel';
  protected $fillable = ['id', 'token', 'banner_token', 'bus_token', 'trip_token', 'in_date', 'status', 'existence', 'added_by', 'activity_token', ];
}
