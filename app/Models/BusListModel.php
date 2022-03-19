<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusListModel extends Model
{
  use HasFactory;
  protected $table = 'app_bus_list';
  protected $fillable = ['id', 'token','banner_token', 'registration_number', 'brand', 'model','num_of_seat', 'status', 'existence', 'added_by', 'activity_token' ];

  public function banner(){
    return $this->belongsTo(BannerListModel::class, 'banner_token', 'token');
  }
  public function booked_seat(){
    return $this->belongsTo(TripBookingDetailsModel::class, 'banner_token', 'banner_token');
  }

  public function seat(){
    return $this->belongsTo(StaticSeatLayoutModel::class, 'num_of_seat', 'id');
  }
}
