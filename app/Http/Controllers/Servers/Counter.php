<?php

namespace App\Http\Controllers\Servers;

use App\Models\CounterAndUserRelModel;
use App\Models\CounterListModel;
use App\Http\Controllers\Servers\Helpers\Helper;

class Counter {

  use Helper;

  public function __newCounter($info){
    $newRow = new CounterListModel;
    $newRow->banner_token = $info['banner_token'];
    $newRow->title = $info['title'];
    $newRow->address = $info['address'];
    $newRow->note = $info['note'];
    $newRow->type = $info['type'];
    $newRow->status = $info['status'];
    $newRow->existence = $info['existence'];
    $newRow->added_by = $info['addedBy'];
    $newRow->activity_token = $info['activityToken'];

    if ($newRow->save()) {
      $token = $this->nativeRand().'__acl'.mt_rand(100, 999).$newRow->id.$this->nativeRand();
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


  public function __updateCounter($info){

    // Check If Row Exists
    $oldRow = $this->rowExists('app_counter_list', 'token', $info['counterToken']);
    if($oldRow['status'] == true){
      // Take Old Row Object
      $oldRow = $oldRow['row'];
      $newRow = CounterListModel::where('token', $info['counterToken'])->first();
      $newRow->title = $info['title'];
      $newRow->banner_token = $info['banner_token'];
      $newRow->address = $info['address'];
      $newRow->note = $info['note'];
      $newRow->type = $info['type'];
      $newRow->status = $info['status'];
      $newRow->existence = $info['existence'];
      $newRow->added_by = $info['addedBy'];
      $newRow->activity_token = $info['activityToken'];

      if($newRow->save()){
        // Make Update Logger Data Format
        $logData = $this->updateLoggerData($oldRow->id, 'app_counter_list', $oldRow, $newRow, $info['remark'], $info['addedBy'], $info['activityToken']);
        // Log This Update History
        $this->updateLogger($logData);
        return $this->inResponse('success','A Row Has Been Updated', $newRow);
      }
      else{
        return $this->inResponse('server-error', 'System Could Not Update A Row', []);
      }
    }
    else{
      return $this->inResponse('error','Row Does Not Exist', []);
    }
  }

  public function __deleteCounter($info){

    // Check If Row Exists
    $oldRow = $this->rowExists('app_counter_list', 'token', $info['counterToken']);
    if($oldRow['status'] == true){
      // Take Old Row Object
      $oldRow = $oldRow['row'];
      $newRow = CounterListModel::where('token', $info['counterToken'])->first();
      $newRow->existence = $info['existence'];
      $newRow->added_by = $info['addedBy'];
      $newRow->activity_token = $info['activityToken'];

      if($newRow->save()){
        // Make Update Logger Data Format
        $logData = $this->updateLoggerData($oldRow->id, 'app_counter_list', $oldRow, $newRow, $info['remark'], $info['addedBy'], $info['activityToken']);
        // Log This Update History
        $this->deleteLogger($logData);
        return $this->inResponse('success','A Row Has Been Updated', $newRow);
      }
      else{
        return $this->inResponse('server-error', 'System Could Not Update A Row', []);
      }
    }
    else{
      return $this->inResponse('error','Row Does Not Exist', []);
    }
  }

  public function __relateCounterAndUser($info){
    $newRow = new CounterAndUserRelModel;
    $newRow->caur_id = $info['caurId'];
    $newRow->cl_id = $info['clId'];
    $newRow->user_token = $info['userToken'];
    $newRow->url_id = $info['urlId'];
    $newRow->status = $info['status'];
    $newRow->existence = $info['existence'];
    $newRow->local_dt = $info['localDt'];
    $newRow->created_by = $info['createdBy'];
    $newRow->arll_id = $info['arllId'];

    if ($newRow->save()) {
      $token = $this->nativeRand().'__acaur'.mt_rand(100, 999).$newRow->id.$this->nativeRand();
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
