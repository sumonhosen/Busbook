<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BannerAndBannerRelModel extends Model
{
  use HasFactory;
  protected $table = 'app_banner_and_banner_rel';
  protected $fillable = ['id', 'token', 'parent_banner_token', 'child_banner_token', 'status', 'existence', 'added_by', 'activity_token',  ];

  

}
