<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Servers\Bus;
use App\Models\BusListModel;
use App\Models\BannerListModel;
use App\Models\StaticSeatLayoutModel;
use App\Http\Controllers\Servers\Helpers\Helper;

class BusController extends Controller
{
  use Helper;

   public function newBusView(){
    $list = BusListModel::where('existence', 1)->orderBy('id', 'desc')->get();
    $seat_list = StaticSeatLayoutModel::where('status',1)->get();

    $banner = BannerListModel::where('existence', 1)->orderBy('id', 'desc')->get();
    return view('pages.bus.new', compact('list','banner','seat_list'));
  }


  public function newBus(Request $request){
    $info = [];
    $info['registrationNumber'] = $request->registrationNumber;
    $info['brand'] = $request->brand;
    $info['model'] = $request->model;
    $info['num_of_seat'] = $request->num_of_seat;
    $info['banner_token'] = $request->banner_token;

    $info = array_merge($info, $this->commonColumns($request->all()));

    (new Bus)->__newBus($info);

    return back()->with('status', 'New Bus Added');

  }

  // public function busList(){
  //   $List = BusListModel::where('existence', 1)->get();
  //   return view('pages.bus.list', compact('List') );
  // }

  public function updateBus(Request $request, $busToken){

    $info = [];
    $info['busToken'] = $busToken;
    $info['banner_token'] = $request->banner_token;
    $info['registrationNumber'] = $request->registrationNumber;
    $info['brand'] = $request->brand;
    $info['model'] = $request->model;
    $info['remark'] = "UPDATE";

    $info = array_merge($info, $this->commonColumns($request->all()));
    (new Bus)->__updateBus($info);

    return back()->with('status', 'Bus Has Been Updated');

  }

  public function deleteBus($busToken){
    $info = [];
    $info['busToken'] = $busToken;
    $info['existence'] = 0;
    $info['addedBy'] = 'Saikat';
    $info['activityToken'] = 'ajjkdkdddkdinrhr';
    $info['remark'] = "DELETE";

    (new Bus)->__deleteBus($info);
    return back()->with('status', 'Bus Has Been Deleted');
  }

  public function relateBusAndUser(Request $request){
    $info = [];
    return (new Bus)->__relateBusAndUser($info);
  }

  // public function busView($busToken){
  //   $Item = BusListModel::where('token', $busToken)->first();
  //   return view('pages.bus.update', compact('Item'));
  // }



}
