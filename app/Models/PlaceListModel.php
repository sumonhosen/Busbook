<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlaceListModel extends Model
{
  use HasFactory;
  protected $table = 'app_place_list';
  protected $fillable = ['id', 'token', 'title', 'title_bn', 'status', 'existence', 'added_by',  'activity_token' ];
}
