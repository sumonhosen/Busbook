<?php

namespace App\Http\Controllers\Servers;

use App\Models\PlaceListModel;
use App\Models\AppPlaceRelatedKeywordListModel;
use App\Http\Controllers\Servers\Helpers\Helper;


class Place {
  use Helper;

  public function __newPlace($info){
    $newRow = new PlaceListModel;
    $newRow->title = $info['title'];
    $newRow->title_bn = $info['titleBn'];
    $newRow->status = $info['status'];
    $newRow->existence = $info['existence'];
    $newRow->added_by = $info['addedBy'];
    $newRow->activity_token = $info['activityToken'];
       
    if ($newRow->save()) {
      $token = $this->nativeRand().'__apl'.mt_rand(100, 999).$newRow->id.$this->nativeRand();
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

  public function __updatePlace($info){
    // Check If Row Exists
    $oldRow = $this->rowExists('app_place_list', 'token', $info['token']);
    if($oldRow['status'] == true){
      // Take Old Row Object
      $oldRow = $oldRow['row'];
      $newRow = PlaceListModel::where('token', $info['token'])->first();
      $newRow->title = $info['title'];
      $newRow->title_bn = $info['titleBn'];
      $newRow->status = $info['status'];
      $newRow->existence = $info['existence'];
      $newRow->added_by = $info['addedBy'];
      $newRow->activity_token = $info['activityToken'];

      if($newRow->save()){
        // Make Update Logger Data Format
        $logData = $this->updateLoggerData($oldRow->id, 'app_place_list', $oldRow, $newRow, $info['remark'], $info['addedBy'], $info['activityToken']);
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

  public function __deletePlace($info){
    // Check If Row Exists
    $oldRow = $this->rowExists('app_place_list', 'token', $info['token']);
    if($oldRow['status'] == true){
      // Take Old Row Object
      $oldRow = $oldRow['row'];
      $newRow = PlaceListModel::where('token', $info['token'])->first();      
      $newRow->existence = $info['existence'];
      $newRow->added_by = $info['addedBy'];
      $newRow->activity_token = $info['activityToken'];

      if($newRow->save()){
        // Make Update Logger Data Format
        $logData = $this->updateLoggerData($oldRow->id, 'app_place_list', $oldRow, $newRow, $info['remark'], $info['addedBy'], $info['activityToken']);
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



  

  public function __newPlaceRelatedKeyword($info){
    $newRow = new AppPlaceRelatedKeywordListModel;
    $newRow->place_token = $info['placeToken'];
    $newRow->keyword = $info['keyword'];
    $newRow->status = $info['status'];
  
    if ($newRow->save()) {
      $token = $this->nativeRand().'__aprkl'.mt_rand(100, 999).$newRow->id.$this->nativeRand();
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