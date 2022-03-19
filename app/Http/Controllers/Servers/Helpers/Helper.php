<?php

namespace App\Http\Controllers\Servers\Helpers;

use App\Models\SysLoggerUpdate;
use App\Models\SysLoggerDelete;
use Illuminate\Support\Facades\DB;

trait Helper {

  public function apiResponse($status, $messageCode, $data, $activityToken){
    $response = [];
    $response['status'] = $status;
    $response['messageCode'] = 'LIST_LOADED';
    $response['message'] = 'List Has Been Loaded';
    $response['data'] = $data;

    return $response;
  }    

  public function commonColumns($request){
    $commonColumns = [
      'status' => 'Active',
      'existence' => 1,
      'addedBy' => 'Developer',
      'activityToken' => 'Developer'
    ];
    return $commonColumns;
  }

  public function nativeRand($length = 4) {
    $characters = 'abcdefghijklmnopqrstuvwxyz';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
	}

	public function nativeHash($password){
		/*$prefix = "ISTIAQ";
		$pass = $prefix.$password;
		$pass = bcrypt($pass);*/
		$pass = md5($password);
		return $pass;
	}

  public function inResponse($type, $msg, $data){
    $response = array();
    $response['type'] = $type;
    $response['msg'] = $msg;
    $response['data'] = $data;
    return $response;
  }

  public function updateLoggerData($id, $table, $oldData, $newData, $remark, $updatedBy, $activityToken){
    
    $logData = array();
    $logData['id'] = $id;
    $logData['table'] = $table;
    $logData['oldData'] = $oldData;
    $logData['newData'] = $newData;
    $logData['remark'] = $remark;
    $logData['updatedBy'] = $updatedBy;
    $logData['activityToken'] = $activityToken;
    return $logData;
  }

  public function updateLogger($info){   
     // dd($info);
    $newRow = new SysLoggerUpdate;
    $newRow->effected_id = $info['id'];
    $newRow->effected_table = $info['table'];
    $newRow->old_data = json_encode(json_decode(json_encode($info['oldData']), true));
    $newRow->new_data = json_encode(json_decode(json_encode($info['newData']), true));
    $newRow->remark = $info['remark'];
    $newRow->updated_by = $info['updatedBy'];
    $newRow->activity_token = $info['activityToken'];
    $newRow->save();
    return true;
  }

  public function deleteLogger($info){
    $newRow = new SysLoggerDelete;
    $newRow->effected_id = $info['id'];
    $newRow->effected_table = $info['table'];   
    $newRow->remark = $info['remark'];
    $newRow->deleted_by = $info['updatedBy'];
    $newRow->activity_token = $info['activityToken'];
    $newRow->save();
    return true;
  }

  public function rowExists($table, $column, $value) {
    $response = array();
    $q = DB::table($table)->where($column, $value);
    if ($q->exists()) {
      $q = $q->first();
      $response['status'] = true;
      $response['row'] = $q;
      return $response;
    }
    else{
      $response['status'] = false;
      $response['row'] = [];
      return $response;
    }
  }

  public function sendSMS($phone, $sms, $type){
    $csmsid = $type.mt_rand(1000, 100000000);
    $phone = "88$phone";
    $M_SMS_SID = 'BUSBOOKBRAND';
    $M_SMS_API_TOKEN = 'BusBook-4d30f724-2d7a-46e0-aa0c-709a60dc89eb';
    # MASKING
    $curl = curl_init();
    curl_setopt_array($curl, 
      array( CURLOPT_RETURNTRANSFER => 1, 
        CURLOPT_URL =>
        'https://smsplus.sslwireless.com/api/v3/send-sms?api_token='.$M_SMS_API_TOKEN.'&sid='.$M_SMS_SID.'&sms='.urlencode("$sms").'&msisdn='.$phone.'&csms_id='.$csmsid.'',
        CURLOPT_USERAGENT => 'Impression Bangladesh / BusBook Brand / Signed By: Istiaq Hasan' ));
    $resp = curl_exec($curl);
    curl_close($curl);

    $ret = Array();
    $ret['csmsid'] = $csmsid;
    $ret['phone'] = $phone;
    $ret['$sms'] = $sms;
    $ret['M_SMS_SID'] = $M_SMS_SID;
    $ret['M_SMS_API_TOKEN'] = $M_SMS_API_TOKEN;

    return $ret;
  }




}