<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    use HasFactory;
    protected $table = 'orders';
    protected $fillable = [ 'id', 'name', 'email', 'phone', 'amount', 'address', 'status', 'transaction_id', 'currency'];

}
