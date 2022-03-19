<?php

namespace App\Http\Controllers\Servers;

use App\Models\SysDeviceListModel;

use App\Http\Controllers\Servers\Helpers\Helper;

class System {
  use Helper;

  public function __newDeviceToken($info){
    $newRow = new SysDeviceListModel;
    $newRow->app_token = $info['appToken'];
    $newRow->user_agent = $info['userAgent'];
    $newRow->ip_address = $info['ipAddress'];
    $newRow->source = $info['source'];
    $newRow->device_id = $info['deviceId'];
    $newRow->device_info = $info['deviceInfo'];
    $newRow->status = $info['status'];
    $newRow->existence = $info['existence'];
    $newRow->added_by = $info['addedBy'];
    $newRow->activity_token = $info['activityToken'];

    if ($newRow->save()) {
      $token = $this->nativeRand().'__dt'.mt_rand(100, 999).$newRow->id.$this->nativeRand();
      $newRow->token = $token;
      if ($newRow->save()) {
          return $this->inResponse('success', 'A New Row Has Been Inserted With A Token', $newRow);
      }
      else{
          return $this->inResponse('server-error', 'A New Row Has Been Inserted But Could Not Update The Token', $newRow);
      }
    }
    else{
      return $this->inResponse('server-error', 'System Could Not Insert A Row', []);
    }

    return $newRow;
  }











}
