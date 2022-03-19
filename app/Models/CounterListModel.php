<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CounterListModel extends Model
{
  use HasFactory;
  protected $table = 'app_counter_list';
  protected $fillable = ['id', 'token','banner_token', 'title', 'address', 'note', 'type', 'status', 'existence', 'added_by', 'activity_token'];
  public function banner(){
    return $this->belongsTo(BannerListModel::class, 'banner_token', 'token');
  }

}
