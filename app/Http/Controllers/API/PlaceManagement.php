<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PlaceListModel;
use App\Http\Controllers\Servers\Helpers\Helper;
use App\Http\Controllers\Servers\Helpers\CamelCase;

class PlaceManagement extends Controller
{
	use Helper;
    public function placeList(Request $request){
    	$list = PlaceListModel::orderBy('title', 'asc')->get();
    	foreach ($list as $key => $listItem) {
    		$listItem->keywords = $listItem->keywords;
    	}
    	$data = [];
    	$data['placeList'] = CamelCase::camelCaseObject($list);
    	return response()->json($this->apiResponse('Success', 'LIST_LOADED', $data, null));
    }
}
