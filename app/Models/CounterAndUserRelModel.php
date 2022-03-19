<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CounterAndUserRelModel extends Model
{
  use HasFactory;

  protected $table = 'app_counter_and_user_rel';
  protected $fillable = ['id', 'token', 'counter_token', 'user_token', 'role_token', 'status', 'existence', 'added_by', 'activity_token' ];

  public function user()
  {
    return $this->belongsTo(UserListModel::class, 'user_token', 'token');
  }
  public function counter()
  {
    return $this->belongsTo(CounterListModel::class, 'counter_token', 'token');
  }
   public function trip_counter(){
   	  return $this->belongsTo(CounterAndTripRelModel::class,'counter_token','counter_token');
   }
 

}
