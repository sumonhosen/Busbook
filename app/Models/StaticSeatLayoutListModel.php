<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaticSeatLayoutListModel extends Model
{
  use HasFactory;
  protected $table = 'app_static_seat_layout_list';
  protected $fillable = ['id', 'token', 'title', 'storey'];

}
