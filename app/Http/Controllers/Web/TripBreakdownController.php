<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Servers\Trip;

class TripBreakdownController extends Controller
{
  public function newTripBreakdown(Request $request){
    $info = [];
    return (new Trip)->__newTripBreakdown($info);
  }

  public function updateTripBreakdown(Request $request){
    $info = [];
    return (new Trip)->__updateTripBreakdown($info);
  }

  



}
