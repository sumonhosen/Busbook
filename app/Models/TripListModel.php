<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripListModel extends Model
{
  use HasFactory;

  protected $table = 'app_trip_list';
  protected $fillable = ['id', 'token', 'banner_token', 'bus_token','trip_number', 'departure_point', 'related_departure_counter', 'destination_point', 'related_destination_counter', 'departure_time', 'destination_time', 'seat_access','status', 'existence', 'added_by', 'activity_token'];

    public function departurePoint(){
        return $this->belongsTo('App\Models\PlaceListModel', 'departure_point', 'token' );
    }

    public function destinationPoint(){
        return $this->belongsTo('App\Models\PlaceListModel', 'destination_point', 'token' );
    }

    public function relatedDepartureCounter(){
        return $this->belongsTo('App\Models\CounterListModel', 'related_departure_counter', 'token' );
    }
    public function relatedDestinationCounter(){
        return $this->belongsTo('App\Models\CounterListModel', 'related_destination_counter', 'token' );
    }
    public function banner(){
        return $this->belongsTo('App\Models\BannerListModel', 'banner_token', 'token' );
    }
    public function bus(){
        return $this->belongsTo('App\Models\BusListModel', 'bus_token', 'token' );
    }
	public function counter(){
        return $this->belongsTo('App\Models\CounterAndTripRelModel', 'trip_token', 'token');
    }

}
