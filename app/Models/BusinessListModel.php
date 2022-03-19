<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessListModel extends Model
{
  use HasFactory;
  protected $table = 'app_business_list';
  protected $fillable = ['id', 'token', 'title', 'details', 'status', 'existence', 'added_by', 'activity_token'];
}
