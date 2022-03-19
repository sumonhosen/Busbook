<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Servers\Business;
use App\Models\BusinessListModel;
use App\Http\Controllers\Servers\Helpers\Helper;

class BusinessController extends Controller
{
  use Helper;

  public function newBusinessView(){
    $list = BusinessListModel::where('existence', 1)->orderBy('id', 'desc')->get();
    return view('pages.business.new', compact('list'));
  }

  public function newBusiness(Request $request){   
    
    $info = [];
    $info['title'] = $request->title;
    $info['details'] = $request->details;

    $info = array_merge($info, $this->commonColumns($request->all()));

    (new Business)->__newBusiness($info);

    return back()->with('status', 'New Business Added');
  }

  // public function businessView($businessToken){
  //   $Item = BusinessListModel::where('token', $businessToken)->first();
  //   return view('pages.business.update', compact('Item'));
  // }


  // public function businessList(){
  //   $List = BusinessListModel::where('existence', 1)->get();

  //   return view('pages.business.list', compact('List'));
  // }

  public function updateBusiness(Request $request, $businessToken){    
    $info = [];
    $info['businessToken'] = $businessToken;
    $info['title'] = $request->title;
    $info['details'] = $request->details;
    $info['remark'] = "UPDATE";
    
    $info = array_merge($info, $this->commonColumns($request->all()));
    (new Business)->__updateBusiness($info);

    return back()->with('status', 'Business Has Been Updated');
  } 

 

  public function deleteBusiness(Request $request, $businessToken){
    $info = [];
    $info['businessToken'] = $businessToken;
    $info['status'] = 1;
    $info['existence'] = 0;
    $info['addedBy'] = 'Saikat';
    $info['activityToken'] = 'X125DGPUL96R3T';
    $info['remark'] = "DELETE";
    (new Business)->__deleteBusiness($info);

    return back()->with('status', 'Business Has Been Deleted');
  }

  // public function relateBusinessAndUser(Request $request){
  //   $info = [];
  //   return (new Business)->__relateBusinessAndUser($info);
  // }

    


    
}
