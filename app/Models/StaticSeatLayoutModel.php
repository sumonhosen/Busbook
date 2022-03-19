<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaticSeatLayoutModel extends Model
{
    use HasFactory;
    protected $table = 'app_static_bus_seat';
    protected $fillable = ['id', 'title', 'status'] ;


	public function seat(){
		return $this->belongsTo(BusListModel::class, 'num_of_seat', 'id');
	 }
}
