<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripBookingListModel extends Model
{
  use HasFactory;
  protected $table = 'app_trip_booking_list';
  protected $fillable = ['id', 'token', 'banner_token', 'trip_token', 'trip_breakdown_token', 'booking_number', 'booking_counter', 'boarding_counter', 'departure_point', 'related_departure_counter', 'destination_point', 'related_destination_counter', 'booked_for', 'booked_type','ticket_type','name_on_ticket', 'emergency_contact', 'pickup_note', 'journey_date', 'journey_time', 'status', 'booking_status', 'existence', 'added_by', 'activity_token'];

  public function banner(){
    return $this->belongsTo('App\Models\BannerListModel', 'banner_token', 'token' );
  }

  public function trip(){
    return $this->belongsTo('App\Models\TripListModel', 'trip_token', 'token' );
  }

  public function departurePoint(){
    return $this->belongsTo('App\Models\PlaceListModel', 'departure_point', 'token' );
  }

  public function destinationPoint(){
      return $this->belongsTo('App\Models\PlaceListModel', 'destination_point', 'token' );
  }

  public function bookedForPerson(){
      return $this->belongsTo('App\Models\UserListModel', 'booked_for', 'token' );
  }

  public function bookedCounter(){
    return $this->belongsTo('App\Models\TripListModel', 'booked_counter', 'token' );
  }

}
