<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountdownTime extends Model
{
    use HasFactory;
    protected $table = 'app_countdown_times';
    protected $fillable = ['id', 'token','bus_token', 'user_token','counter_token','breakdown_token','seat_details','trip_token'];

}
