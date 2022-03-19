<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BannerListModel extends Model
{
  use HasFactory;
  protected $table = 'app_banner_list';
  protected $fillable = ['id', 'token', 'title', 'title_bangla', 'status', 'existence', 'added_by', 'activity_token', 'created_at', 'updated_at', ];

  public function trip(){
    return $this->hasMany(TripListModel::class, 'banner_token', 'token');
  }

}
