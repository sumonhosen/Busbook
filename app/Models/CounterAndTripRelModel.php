<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TripListModel;


class CounterAndTripRelModel extends Model
{
  use HasFactory;
  protected $table = 'app_counter_and_trip_rel';
  protected $fillable = ['id', 'token', 'counter_token', 'trip_token', 'counter_type', 'status', 'existence', 'added_by', 'activity_token'];

  public function trip()
  {
    return $this->belongsTo(TripListModel::class, 'trip_token', 'token');
  }
		
  public function counter_user(){
    return $this->belongsTo(CounterAndUserRelModel::class, 'counter_token', 'counter_token');
  }	
	
}
