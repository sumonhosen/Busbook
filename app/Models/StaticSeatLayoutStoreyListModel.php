<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaticSeatLayoutStoreyListModel extends Model
{
  use HasFactory;
  protected $table = 'app_static_seat_layout_storey_list';
  protected $fillable = ['id', 'token', 'seat_layout_token', 'title', ];
	
}
