<?php

namespace App\Http\Controllers\Servers;

use App\Models\UserListModel;
use App\Http\Controllers\Servers\Helpers\Helper;

class User {
  use Helper;

  public function __newUser($info){
    $newRow = new UserListModel;
    $newRow->name = $info['name'];
    $newRow->phone = $info['phone'];
    $newRow->email = $info['email'];
    $newRow->type = $info['type'];
    $newRow->address = $info['address'];
    $newRow->password = $this->nativeHash($info['password']);
    $newRow->status = $info['status'];
    $newRow->existence = $info['existence'];
    $newRow->added_by = $info['addedBy'];
    $newRow->activity_token = $info['activityToken'];
    $newRow->save();

    if ($newRow->save()) {
      $token = $this->nativeRand().'__aul'.mt_rand(100, 999).$newRow->id.$this->nativeRand();
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


  public function __updateUser($info){

    // Check If Row Exists
    $oldRow = $this->rowExists('app_user_list', 'token', $info['userToken']);
    if($oldRow['status'] == true){
      // Take Old Row Object
      $oldRow = $oldRow['row'];
      $newRow = UserListModel::where('token', $info['userToken'])->first();
      $newRow->name = $info['name'];
      $newRow->phone = $info['phone'];
      $newRow->email = $info['email'];
      $newRow->address = $info['address'];
      $newRow->status = $info['status'];
      $newRow->existence = $info['existence'];
      $newRow->added_by = $info['addedBy'];
      $newRow->activity_token = $info['activityToken'];

      if($newRow->save()){
        // Make Update Logger Data Format
        $logData = $this->updateLoggerData($oldRow->id, 'app_user_list', $oldRow, $newRow, $info['remark'], $info['addedBy'], $info['activityToken']);
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

  public function __updatePassword($info){
    $newRow = UserListModel::where('token', $info['userToken'])->first();
    $newRow->password = $this->nativeHash($info['password']);
    if ($newRow->save()) {
      $token = $this->nativeRand().'__aul'.mt_rand(100, 999).$newRow->id.$this->nativeRand();
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

  
  public function __deleteUser($info){
    // Check If Row Exists
    $oldRow = $this->rowExists('app_user_list', 'token', $info['userToken']);
    if($oldRow['status'] == true){
      // Take Old Row Object
      $oldRow = $oldRow['row'];
      $newRow = UserListModel::where('token', $info['userToken'])->first();      
      $newRow->existence = $info['existence'];
      $newRow->added_by = $info['addedBy'];
      $newRow->activity_token = $info['activityToken'];

      if($newRow->save()){
        // Make Update Logger Data Format
        $logData = $this->updateLoggerData($oldRow->id, 'app_user_list', $oldRow, $newRow, $info['remark'], $info['addedBy'], $info['activityToken']);
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

 

}