<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Servers\Counter;
use App\Models\CounterListModel;
use App\Models\BannerListModel;
use App\Models\TripListModel;
use App\Models\UserListModel;
use App\Models\CounterAndTripRelModel;
use App\Models\CounterAndUserRelModel;
use App\Http\Controllers\Servers\Helpers\Helper;

class CounterController extends Controller
{
  use Helper;

  public function newCounterView(Request $request){
    $list = CounterListModel::where('existence', 1)->orderBy('id', 'desc')->get();
    $banner = BannerListModel::where('existence', 1)->orderBy('id', 'desc')->get();
    return view('pages.counter.new', compact('list','banner'));
  }

  public function newCounter(Request $request){
    $info = [];
    $info['banner_token'] = $request->banner_token;
    $info['title']        = $request->title;
    $info['address']      = $request->address;
    $info['note']         = $request->note;
    $info['type']         = $request->type;

    $info = array_merge($info, $this->commonColumns($request->all()));
    (new Counter)->__newCounter($info);
    return redirect()->back()->with('status', 'New Counter Added');
  }

  // public function counterList(){
  //   $List = CounterListModel::where('existence', 1)->get();

  //   return view('pages.counter.list', compact('List'));
  // }

  // public function counterView($counterToken){
  //   $Item = CounterListModel::where('token', $counterToken)->first();
  //   return view('pages.counter.update', compact('Item'));
  // }

  public function updateCounter(Request $request, $counterToken){
    $info = [];
    $info['counterToken'] = $counterToken;
    $info['banner_token'] = $request->banner_token;
    $info['title'] = $request->title;
    $info['address'] = $request->address;
    $info['note'] = $request->note;
    $info['type'] = $request->type;
    $info['remark'] = 'UPDATE';
    $info = array_merge($info, $this->commonColumns($request->all()));

    (new Counter)->__updateCounter($info);

    return back()->with('status', 'Counter Has Been Updated');
  }

  public function deleteCounter(Request $request, $counterToken){
    $info = [];
    $info['counterToken'] = $counterToken;
    $info['existence'] = 0;
    $info['addedBy'] = 'Saikat';
    $info['activityToken'] = 'ajjkdkdddkdinrhr';
    $info['remark'] = "DELETE";

    (new Counter)->__deleteCounter($info);
    return back()->with('status', 'Counter Has Been Deleted');
  }

  public function relateCounterAndTripView($counterToken){

    $counter = CounterListModel::where('token', $counterToken)->first();
    $bannerList = BannerListModel::where('token',$counter->banner_token)->orderBy('id', 'desc')->get();

    $list = CounterAndTripRelModel::where('counter_token', $counterToken)->where('existence', 1)->orderBy('id', 'desc')->get();


    foreach($bannerList as $item){
      $item->tripList = TripListModel::where('banner_token', $item->token)->orderBy('id', 'asc')->get();
    }

    return view('pages.counter.relateCounterAndTripView', compact('bannerList',  'counter', 'list'));
  }

  public function relateCounterAndUserView($counterToken){

    $counter = CounterListModel::where('token', $counterToken)->first();
    $userList = UserListModel::where('existence', 1)->where('type', 'Counter Related')->orderBy('id', 'desc')->get();
    $list = CounterAndUserRelModel::where('counter_token', $counterToken)->where('existence', 1)->orderBy('id', 'desc')->get();



    return view('pages.counter.relateCounterAndUserView', compact('userList',  'counter', 'list'));
  }


  public function relateCounterAndTrip(Request $request){

        $newRow = new CounterAndTripRelModel;
        $newRow->counter_token = $request->counter_token;
        $newRow->trip_token    = $request->trip_token;
        $newRow->counter_type    = $request->counter_type;
        $newRow->status    = 'Active';
        $newRow->existence    = 1;
        $newRow->save();

        $token = $this->nativeRand().'__acatr'.mt_rand(100, 999).$newRow->id.$this->nativeRand();
        $newRow->token = $token;
        $newRow->save();
        return back()->with('status', 'Counter And Trip Has Been Related');

  }

  public function relateCounterAndUser(Request $request){

            $newRow = new CounterAndUserRelModel;
            $newRow->counter_token = $request->counter_token;
            $newRow->user_token    = $request->user_token;
            $newRow->role_token    = $request->role_token;
            $newRow->status        = 'Active';
            $newRow->existence     = 1;
            $newRow->save();

            $token = $this->nativeRand().'__acaur'.mt_rand(100, 999).$newRow->id.$this->nativeRand();
            $newRow->token = $token;
            $newRow->save();

            return back()->with('status', 'Counter And User Has Been Related');

  }

  public function deleteCounterAndTrip($token){
    $deleteRow = CounterAndTripRelModel::where('token', $token)->first();
    $deleteRow->existence = 0;
    $deleteRow->save();

    return back()->with('status', 'Counter And Trip Has Been Deleted');
  }

  public function deleteCounterAndUser($token){
    $deleteRow = CounterAndUserRelModel::where('token', $token)->first();
    $deleteRow->existence = 0;
    $deleteRow->save();

    return back()->with('status', 'Counter And User Has Been Deleted');
  }


}
