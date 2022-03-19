<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppPlaceRelatedKeywordListModel extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'app_place_related_keyword_list';
    protected $fillable = ['id', 'token', 'place_token', 'keyword', 'status' ];

}
