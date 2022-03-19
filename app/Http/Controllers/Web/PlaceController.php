<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Servers\Place;

use App\Http\Controllers\Servers\Helpers\Helper;
use App\Models\PlaceListModel;

class PlaceController extends Controller
{
  use Helper;

	public function newPlaceView(){
    $list = PlaceListModel::where('existence', 1)->orderBy('id', 'desc')->get();
		return view('pages.place.new', compact('list'));
	}

  public function newPlace(Request $request){

    $info = [];
    $keyword = [];
    $info['title'] = $request->title;
    $info['titleBn'] = $request->titleBn;
    
    $info = array_merge($info, $this->commonColumns($request->all()));
    $placeToken = (new Place)->__newPlace($info);

    $keyword['placeToken'] = $placeToken['data']['token'];   
    $keyword['keyword'] = $request->title;   
    $keyword['status'] = 'Active';   
    (new Place)->__newPlaceRelatedKeyword($keyword);

    $keyword['keyword'] = $request->titleBn;   
    (new Place)->__newPlaceRelatedKeyword($keyword);

    return redirect()->back()->with('status', 'New Place Added');
  }

  // public function placeView($id){
  //   $Item = PlaceListModel::where('id', $id)->first();
  //   return view('pages.place.update', compact('Item'));
  // }

  public function updatePlace(Request $request, $placeToken){

    $info = [];
    $info['token'] = $placeToken;
    $info['title'] = $request->title;
    $info['titleBn'] = $request->titleBn;
    $info['remark'] = 'UPDATE';
    
    $info = array_merge($info, $this->commonColumns($request->all()));

    (new Place)->__updatePlace($info);

    return back()->with('status', 'New Place Updated');
  }

  // public function placeList(){
  //   $List = PlaceListModel::where('existence', 1)->get();
  //   return view('pages.place.list', compact('List'));
  // }

  public function deletePlace($placeToken){
    $info = [];
    $info['token'] = $placeToken;
    $info['existence'] = 0;
    $info['addedBy'] = 'Saikat';
    $info['activityToken'] = 'ajjkdkdddkdinrhr';
    $info['remark'] = "DELETE";

    (new Place)->__deletePlace($info);
    
    return back()->with('status', 'New Place Deleted');
  }


}
