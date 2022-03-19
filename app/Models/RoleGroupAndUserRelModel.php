<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleGroupAndUserRelModel extends Model
{
    use HasFactory;
    protected $table = 'app_role_group_and_user_rel';
    protected $fillable = ['id', 'token', 'role_group_token', 'user_token', 'remark', 'status', 'existence', 'added_by', 'activity_token' ];
    
}
