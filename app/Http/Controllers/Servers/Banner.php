<?php

namespace App\Http\Controllers\Servers;

use App\Models\BannerListModel;
use App\Models\BannerAndBannerRelModel;
use App\Http\Controllers\Servers\Helpers\Helper;

class Banner {
  use Helper;


  public function __newBanner($info){
	  $newRow = new BannerListModel;
	  $newRow->title = $info['title'];
	  $newRow->title_bangla = $info['titleBangla'];
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

	public function __updateBanner($info){
		// Check If Row Exists
	    $oldRow = $this->rowExists('app_banner_list', 'token', $info['bannerToken']);
	    if($oldRow['status'] == true){
	      // Take Old Row Object
	      $oldRow = $oldRow['row'];
	      $newRow = BannerListModel::where('token', $info['bannerToken'])->first();
	      $newRow->title = $info['title'];
		  $newRow->title_bangla = $info['titleBangla'];
		  $newRow->status = $info['status'];
		  $newRow->existence = $info['existence'];
		  $newRow->added_by = $info['addedBy'];
		  $newRow->activity_token = $info['activityToken'];

	      if($newRow->save()){
	        // Make Update Logger Data Format
	        $logData = $this->updateLoggerData($oldRow->id, 'app_banner_list', $oldRow, $newRow, $info['remark'], $info['addedBy'], $info['activityToken']);
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

	public function __deleteBanner($info){
		// Check If Row Exists
	    $oldRow = $this->rowExists('app_banner_list', 'token', $info['bannerToken']);
	    if($oldRow['status'] == true){
	      // Take Old Row Object
	      $oldRow = $oldRow['row'];
	      $newRow = BannerListModel::where('token', $info['bannerToken'])->first();      
	      $newRow->existence = $info['existence'];
	      $newRow->added_by = $info['addedBy'];
	      $newRow->activity_token = $info['activityToken'];

	      if($newRow->save()){
	        // Make Update Logger Data Format
	        $logData = $this->updateLoggerData($oldRow->id, 'app_banner_list', $oldRow, $newRow, $info['remark'], $info['addedBy'], $info['activityToken']);
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