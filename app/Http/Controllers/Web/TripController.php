<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Servers\Trip;
use App\Models\TripListModel;
use App\Models\PlaceListModel;
use App\Models\CounterListModel;
use App\Models\BannerListModel;
use App\Models\BusListModel;
use App\Models\TripBreakdownModel;

use App\Http\Controllers\Servers\Helpers\Helper;

class TripController extends Controller
{
  use Helper;

  public function newTripView(){
    $placeList = PlaceListModel::where('existence', 1)->orderBy('title', 'ASC')->get();
    $counterList = CounterListModel::where('existence', 1)->orderBy('title', 'ASC')->get();
    $bannerList = BannerListModel::where('existence', 1)->orderBy('title', 'ASC')->get();
    $list = TripListModel::where('existence', 1)->orderBy('id', 'DESC')->get();
    $bus_list = BusListModel::where('existence', 1)->orderBy('id', 'desc')->get();

    return view('pages.trip.new', compact('placeList', 'counterList', 'bannerList', 'bus_list','list'));
  }

  public function newBreakdown(Request $request,$tripToken){

    $info = [];
    $info['tripToken'] = $tripToken;
    $info['bannerToken'] = $request->bannerToken;
    $info['tripToken'] = $request->tripToken;
    $info['busToken'] = $request->busToken;
    $info['departurePoint'] = $request->departurePoint;
    $info['relatedDepartureCounter'] = $request->relatedDepartureCounter;
    $info['destinationPoint'] = $request->destinationPoint;
    $info['relatedDestinationCounter'] = $request->relatedDestinationCounter;
    $info['departureTime'] = $request->departureTime;
    $info['destinationTime'] = $request->destinationTime;
    $info['fare'] = $request->fare;
    $info['onlineCharge'] = $request->onlineCharge;

    $info = array_merge($info, $this->commonColumns($request->all()));
    (new Trip)->__newBreakdown($info);

    return back()->with('status', 'New Breakdown Added');

  }

  public function newTrip(Request $request){

    $info = [];
    $info['bannerToken'] = $request->bannerToken;
    $info['busToken'] = $request->busToken;
    $info['tripNumber'] = $request->tripNumber;
    $info['departurePoint'] = $request->departurePoint;
    $info['relatedDepartureCounter'] = $request->relatedDepartureCounter;
    $info['destinationPoint'] = $request->destinationPoint;
    $info['relatedDestinationCounter'] = $request->relatedDestinationCounter;
    $info['departureTime'] = $request->departureTime;
    $info['destinationTime'] = $request->destinationTime;
	$info['seatAccess'] = $request->seatAccess;

    $info = array_merge($info, $this->commonColumns($request->all()));

    $trip = (new Trip)->__newTrip($info);

    $info['tripToken'] = $trip['data']->token;

    (new Trip)->__newTripBreakdown($info);

    return back()->with('status', 'New Trip Added');

  }

  public function updateTrip(Request $request, $tripToken){


    $info = [];
    $info['tripToken'] = $tripToken;
    $info['bannerToken'] = $request->bannerToken;
    $info['busToken']   = $request->busToken;
    $info['tripNumber'] = $request->tripNumber;
    $info['departurePoint'] = $request->departurePoint;
    $info['relatedDepartureCounter'] = $request->relatedDepartureCounter;
    $info['destinationPoint'] = $request->destinationPoint;
    $info['relatedDestinationCounter'] = $request->relatedDestinationCounter;
    $info['departureTime'] = $request->departureTime;
    $info['destinationTime'] = $request->destinationTime;
	  $info['seatAccess'] = $request->seatAccess;
    $info['remark'] = "UPDATE";

    $info = array_merge($info, $this->commonColumns($request->all()));
    (new Trip)->__updateTrip($info);
   
    return back()->with('status', 'Trip Has Been Updated');
  }

  public function tripList(){
  	$List = TripListModel::where('existence', 1)->get();
  	return view('pages.trip.list', compact('List'));
  }
  public function newBusListView(Request $request){
    $bus_list = BusListModel::where('existence', 1)->where('banner_token',$request->banner_token)->orderBy('id', 'desc')->get();
    return $bus_list;
  }

  public function tripView($tripToken){
    $Item = TripListModel::where('token', $tripToken )->first();
    $PlaceList = PlaceListModel::where('existence', 1)->get();
    $CounterList = CounterListModel::where('existence', 1)->get();
    $BannerList = BannerListModel::where('existence', 1)->get();

    return view('pages.trip.update', compact('Item', 'PlaceList', 'CounterList', 'BannerList'));

  }

  public function deleteTrip($tripToken){
    $info = [];
    $info['tripToken'] = $tripToken;
    $info['existence'] = 0;
    $info['addedBy'] = 'Saikat';
    $info['activityToken'] = 'ajjkdkdddkdinrhr';
    $info['remark'] = "DELETE";

    (new Trip)->__deleteTrip($info);
    return redirect('trip/list');

  }

  public function breakdownList($tripToken){

    $trip = TripListModel::where('token', $tripToken)->first();
    $placeList = PlaceListModel::where('existence', 1)->get();
    $counterList = CounterListModel::where('existence', 1)->get();
    $list = TripBreakdownModel::where('trip_token', $tripToken)->where('existence', 1)->orderBy('id', 'desc')->get();
    
    return view('pages.trip.combined_breakdowns', compact('trip', 'placeList', 'counterList', 'list'));
  }

  public function breakdownEdit($tripBreakdownToken){
    $Item = TripBreakdownModel::where('token', $tripBreakdownToken)->first();
    $PlaceList = PlaceListModel::where('existence', 1)->get();
    $CounterList = CounterListModel::where('existence', 1)->get();

    return view('pages.trip.edit_combined_breakdowns', compact('Item', 'CounterList', 'PlaceList'));
  }

  public function breakdownEditAction(Request $request, $tripBreakdownToken){


    $info = [];
    $info['tripBreakdownToken'] = $tripBreakdownToken;
    $info['departurePoint'] = $request->departurePoint;
    $info['relatedDepartureCounter'] = $request->relatedDepartureCounter;
    $info['destinationPoint'] = $request->destinationPoint;
    $info['relatedDestinationCounter'] = $request->relatedDestinationCounter;
    $info['departureTime'] = $request->departureTime;
    $info['destinationTime'] = $request->destinationTime;
    $info['fare'] = $request->fare;
    $info['onlineCharge'] = $request->onlineCharge;
    $info['remark'] = "UPDATE";

    $info = array_merge($info, $this->commonColumns($request->all()));

    (new Trip)->__updateTripBreakdown($info);

    return back()->with('status', 'Trip Breakdown Updated');

  }
}
