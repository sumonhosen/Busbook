<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Servers\System;
use App\Http\Controllers\Servers\Helpers\Helper;

class SystemManagement extends Controller
{
	use Helper;
    public function newDeviceToken(Request $request){

    	$proceed = true;
    	if ($proceed == true) {
    		# Make Array
    		$info = [];
    		$info['appToken'] = $request->header('AppToken');
    		$info['userAgent'] = $request->header('User-Agent');
    		$info['ipAddress'] = $request->ip();
    		$info['source']   = $request->header('Source');
    		$info['deviceId'] = $request->deviceId;
    		$info['deviceInfo'] = $request->deviceInfo;

    		$info = array_merge($info, $this->commonColumns($request));
    		$newRow = new System;
    		$newRow = $newRow->__newDeviceToken($info);

            $data = [];
            $data['status'] = 'Success';
            $data['guideLine'] = 'Save deviceToken in Device Storage and Send it Everytime in Header from the next requests';
            $data['deviceToken'] = $newRow['data']['token'];

            return response()->json($this->apiResponse('Success', 'LIST_LOADED', $data, null));


    	}
    }
}
