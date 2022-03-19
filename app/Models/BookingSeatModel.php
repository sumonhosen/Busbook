<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingSeatModel extends Model
{
    use HasFactory;
    protected $table = 'app_booking_seat';
    protected $fillable = ['id', 'token', 'unique_id','bus_token','customer_token', 'çounter_token','user_token','breakdown_token','seat_details','fare','online_charge'];

    public function book_info(){
        return $this->belongsTo(TripBookingListModel::class, 'user_token', 'token');
    }
}
