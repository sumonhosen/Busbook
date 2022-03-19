<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripBookingDetailsModel extends Model
{
  use HasFactory;
  protected $table = 'app_trip_booking_details';
  protected $fillable = ['id', 'token', 'banner_token', 'trip_token', 'trip_breakdown_token', 'customer_token','counter_token','booked_by', 'booking_number', 'booking_counter', 'boarding_counter', 'departure_point', 'related_departure_counter', 'destination_point', 'related_destination_counter', 'bus_token','seat_token', 'seat_identifier', 'fare', 'online_charge', 'final_fare', 'booked_for','booked_type','ticket_type','name_on_ticket', 'emergency_contact', 'pickup_note', 'journey_date', 'journey_time', 'status', 'existence', 'added_by', 'activity_token' ];

  public function banner(){
    return $this->belongsTo('App\Models\BannerListModel', 'banner_token', 'token' );
  }

  public function trip(){
    return $this->belongsTo('App\Models\TripListModel', 'trip_token', 'token');
  }

  public function departurePoint(){
    return $this->belongsTo('App\Models\PlaceListModel', 'departure_point', 'token' );
  }

  public function destinationPoint(){
      return $this->belongsTo('App\Models\PlaceListModel', 'destination_point', 'token' );
  }

  public function forPerson(){
      return $this->belongsTo('App\Models\UserListModel', 'booked_for', 'token' );
  }

  public function addedBy(){
      return $this->belongsTo('App\Models\UserListModel', 'added_by', 'token' );
  }
  public function break_down(){
    return $this->belongsTo('App\Models\TripBreakdownModel', 'trip_breakdown_token', 'token' );
  }

}
