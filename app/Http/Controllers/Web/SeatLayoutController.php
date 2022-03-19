<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Servers\Seat;

class SeatLayoutController extends Controller
{
  public function newSeatLayout(Request $request){
    $info = [];
    return (new Seat)->__newSeatLayout($info);
  }

  public function updateSeatLayout(Request $request, $id){
    $info = [];
    return (new Seat)->__newSeatLayout($info);
  }


}
