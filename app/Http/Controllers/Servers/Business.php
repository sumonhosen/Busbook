<?php

namespace App\Http\Controllers\Servers;

use App\Models\BusinessListModel;
use App\Models\BusinessAndUserRelModel;
use App\Models\BusListModel;
use App\Http\Controllers\Servers\Helpers\Helper;

class Business {
  use Helper;

  public function __newBusiness($info){
    $newRow = new BusinessListModel;
    $newRow->title = $info['title'];
    $newRow->details = $info['details'];
    $newRow->status = $info['status'];
    $newRow->existence = $info['existence'];
    $newRow->added_by = $info['addedBy'];
    $newRow->activity_token = $info['activityToken'];

    if ($newRow->save()) {
      $token = $this->nativeRand().'__abl'.mt_rand(100, 999).$newRow->id.$this->nativeRand();
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

  public function __updateBus($info){
    $newRow = new BusListModel;
    $newRow->bl_id = $info['blId'];
    $newRow->bbl_id = $info['bblId'];
    $newRow->sll_id = $info['sllId'];
    $newRow->banner = $info['banner'];
    $newRow->banner_bn = $info['bannerBn'];
    $newRow->call_name = $info['callName'];
    $newRow->model = $info['model'];
    $newRow->storey = $info['storey'];
    $newRow->ac = $info['ac'];
    $newRow->seats = $info['seats'];
    $newRow->status = $info['status'];
    $newRow->existence = $info['existence'];
    $newRow->local_dt = $info['localDt'];
    $newRow->created_by = $info['createdBy'];
    $newRow->arll_id = $info['arllId'];
  
    if ($newRow->save()) {
      $token = $this->nativeRand().'__abl'.mt_rand(100, 999).$newRow->id.$this->nativeRand();
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

  public function __relateBusAndUser($info){
    $newRow = new BusListModel;
    $newRow->bl_id = $info['blId'];
    $newRow->bbl_id = $info['bblId'];
    $newRow->sll_id = $info['sllId'];
    $newRow->banner = $info['banner'];
    $newRow->banner_bn = $info['bannerBn'];
    $newRow->call_name = $info['callName'];
    $newRow->model = $info['model'];
    $newRow->storey = $info['storey'];
    $newRow->ac = $info['ac'];
    $newRow->seats = $info['seats'];
    $newRow->status = $info['status'];
    $newRow->existence = $info['existence'];
    $newRow->local_dt = $info['localDt'];
    $newRow->created_by = $info['createdBy'];
    $newRow->arll_id = $info['arllId'];
  
    if ($newRow->save()) {
      $token = $this->nativeRand().'__abl'.mt_rand(100, 999).$newRow->id.$this->nativeRand();
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

  public function __newBusBusiness($info){
    $newRow = new BusBusinessListModel;
    $newRow->bbl_id = $info['bblId'];
    $newRow->title = $info['title'];
    $newRow->status = $info['status'];
    $newRow->existence = $info['existence'];
    $newRow->local_dt = $info['localDt'];
    $newRow->created_by = $info['createdBy'];
    $newRow->arll_id = $info['arllId'];
  
    if ($newRow->save()) {
      $token = $this->nativeRand().'__abbl'.mt_rand(100, 999).$newRow->id.$this->nativeRand();
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


  public function __updateBusiness($info){

    // Check If Row Exists
    $oldRow = $this->rowExists('app_business_list', 'token', $info['businessToken']);
    if($oldRow['status'] == true){
      // Take Old Row Object
      $oldRow = $oldRow['row'];
      $newRow = BusinessListModel::where('token', $info['businessToken'])->first();
      $newRow->title = $info['title'];
      $newRow->details = $info['details'];      
      $newRow->added_by = $info['addedBy'];
      $newRow->activity_token = $info['activityToken'];   

      if($newRow->save()){
        // Make Update Logger Data Format
        $logData = $this->updateLoggerData($oldRow->id, 'app_business_list', $oldRow, $newRow, $info['remark'], $info['addedBy'], $info['activityToken']);
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

  public function __relateBusBusinessAndUser($info){
    $newRow = new BusBusinessAndUserRelModel;
    $newRow->bbaur_id = $info['bbaurId'];
    $newRow->bbl_id = $info['bblId'];
    $newRow->user_token = $info['userToken'];
    $newRow->url_id = $info['urlId'];
    $newRow->status = $info['status'];
    $newRow->existence = $info['existence'];
    $newRow->local_dt = $info['localDt'];
    $newRow->created_by = $info['createdBy'];
    $newRow->arll_id = $info['arllId'];
  
    if ($newRow->save()) {
      $token = $this->nativeRand().'__abbaur'.mt_rand(100, 999).$newRow->id.$this->nativeRand();
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

  public function __deleteBusiness($info){
    // Check If Row Exists
    $oldRow = $this->rowExists('app_business_list', 'token', $info['businessToken']);
    if($oldRow['status'] == true){
      // Take Old Row Object
      $oldRow = $oldRow['row'];
      $newRow = BusinessListModel::where('token', $info['businessToken'])->first();      
      $newRow->existence = $info['existence'];
      $newRow->added_by = $info['addedBy'];
      $newRow->activity_token = $info['activityToken'];

      if($newRow->save()){
        // Make Update Logger Data Format
        $logData = $this->updateLoggerData($oldRow->id, 'app_business_list', $oldRow, $newRow, $info['remark'], $info['addedBy'], $info['activityToken']);
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