<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Servers\Banner;
use App\Models\BannerListModel;
use App\Http\Controllers\Servers\Helpers\Helper;

class BannerController extends Controller
{
  use Helper;

  public function newBannerView(){
    $list = BannerListModel::where('existence', 1)->orderBy('id', 'desc')->get();
    return view('pages.banner.new', compact('list'));
  }

  public function newBanner(Request $request){
    $info = [];
    $info['title'] = $request->title;
    $info['titleBangla'] = $request->titleBangla;	    

    $info = array_merge($info, $this->commonColumns($request->all()));
    (new Banner)->__newBanner($info);

    return redirect()->back()->with('status', 'New Banner Added');
  }

  // public function bannerList(){
  // 	$List = BannerListModel::where('existence', 1)->get();
  // 	return view('pages.banner.list', compact('List'));
  // }

  // public function bannerView($bannerToken){
  // 	$Item = BannerListModel::where('token', $bannerToken)->first();
  // 	return view('pages.banner.update', compact('Item'));
  // }

  public function bannerUpdate(Request $request, $bannerToken){
    $info = [];
    $info['bannerToken'] = $bannerToken;
    $info['title'] = $request->title;
    $info['titleBangla'] = $request->titleBangla;
    $info['remark'] = "UPDATE";

    $info = array_merge($info, $this->commonColumns($request->all()));
    (new Banner)->__updateBanner($info);

    return back()->with('status', 'Banner Has Been Updated');
  }

  public function deleteBanner($bannerToken){
    $info = [];
    $info['bannerToken'] = $bannerToken;
    $info['existence'] = 0;
    $info['addedBy'] = 'Saikat';
    $info['activityToken'] = 'ajjkdkdddkdinrhr';
    $info['remark'] = "DELETE";

    (new Banner)->__deleteBanner($info);
    return back()->with('status', 'Banner Has Been Deleted');

  }

}
