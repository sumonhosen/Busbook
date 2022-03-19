<?php

namespace App\Http\Controllers\Servers;

use App\Models\SeatLayoutListModel;
use App\Http\Controllers\Server\Helpers\Helper;

class Seat {
  use Helper;

  public function __newSeatLayout($info){
    $newRow = new SeatLayoutListModel;
    $newRow->sll_id = $info['sllId'];
    $newRow->title = $info['title'];
    $newRow->status = $info['status'];
    $newRow->existence = $info['existence'];
    $newRow->local_dt = $info['localDt'];
    $newRow->created_by = $info['createdBy'];
    $newRow->arll_id = $info['arllId'];
  
    if ($newRow->save()) {
      $token = $this->nativeRand().'__asll'.mt_rand(100, 999).$newRow->id.$this->nativeRand();
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

  public function __updateSeatLayout($info){
    $newRow = new SeatLayoutListModel;
    $newRow->sll_id = $info['sllId'];
    $newRow->title = $info['title'];
    $newRow->status = $info['status'];
    $newRow->existence = $info['existence'];
    $newRow->local_dt = $info['localDt'];
    $newRow->created_by = $info['createdBy'];
    $newRow->arll_id = $info['arllId'];
  
    if ($newRow->save()) {
      $token = $this->nativeRand().'__asll'.mt_rand(100, 999).$newRow->id.$this->nativeRand();
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