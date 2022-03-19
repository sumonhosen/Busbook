<?php

namespace App\Http\Controllers\Servers;

use App\Models\TripListModel;
use App\Models\TripBreakdownListModel;
use App\Models\TripBreakdownModel;
use App\Http\Controllers\Servers\Helpers\Helper;

class Trip {
  use Helper;

  public function __newTrip($info){

    $newRow = new TripListModel;
    $newRow->banner_token = $info['bannerToken'];
    $newRow->bus_token    = $info['busToken'];
    $newRow->trip_number  = $info['tripNumber'];
    $newRow->departure_point = $info['departurePoint'];
    $newRow->related_departure_counter = $info['relatedDepartureCounter'];
    $newRow->destination_point = $info['destinationPoint'];
    $newRow->related_destination_counter = $info['relatedDestinationCounter'];
    $newRow->departure_time = $info['departureTime'];
    $newRow->destination_time = $info['destinationTime'];
	$newRow->seat_access = $info['seatAccess'];
    $newRow->status = $info['status'];
    $newRow->existence = $info['existence'];
    $newRow->added_by = $info['addedBy'];
    $newRow->activity_token = $info['activityToken'];

    if ($newRow->save()) {
      $token = $this->nativeRand().'__atl'.mt_rand(100, 999).$newRow->id.$this->nativeRand();
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

  public function __updateTrip($info){
    // Check If Row Exists
    $oldRow = $this->rowExists('app_trip_list', 'token', $info['tripToken']);
    if($oldRow['status'] == true){
      // Take Old Row Object
      $oldRow = $oldRow['row'];
      $newRow = TripListModel::where('token', $info['tripToken'])->first();
      $newRow->banner_token = $info['bannerToken'];
      $newRow->bus_token = $info['busToken'];
      $newRow->trip_number = $info['tripNumber'];
      $newRow->departure_point = $info['departurePoint'];
      $newRow->related_departure_counter = $info['relatedDepartureCounter'];
      $newRow->destination_point = $info['destinationPoint'];
      $newRow->related_destination_counter = $info['relatedDestinationCounter'];
      $newRow->departure_time = $info['departureTime'];
      $newRow->destination_time = $info['destinationTime'];
      $newRow->seat_access           = $info['seatAccess'];
      $newRow->status = $info['status'];
      $newRow->existence = $info['existence'];
      $newRow->added_by = $info['addedBy'];
      $newRow->activity_token = $info['activityToken'];

      if($newRow->save()){
        // Make Update Logger Data Format
        $logData = $this->updateLoggerData($oldRow->id, 'app_trip_list', $oldRow, $newRow, $info['remark'], $info['addedBy'], $info['activityToken']);
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

  public function __newTripBreakdown($info){

    $newRow = new TripBreakdownModel;
    $newRow->banner_token = $info['bannerToken'];
    $newRow->bus_token = $info['busToken'];
    $newRow->trip_token = $info['tripToken'];
    $newRow->departure_point = $info['departurePoint'];
    $newRow->related_departure_counter = $info['relatedDepartureCounter'];
    $newRow->destination_point = $info['destinationPoint'];
    $newRow->related_destination_counter = $info['relatedDestinationCounter'];
    $newRow->departure_time = $info['departureTime'];
    $newRow->destination_time = $info['destinationTime'];
    $newRow->fare = $info['fare'] ?? 0;
    $newRow->online_charge = $info['onlineCharge'] ?? 0;
    $newRow->status = $info['status'];
    $newRow->existence = $info['existence'];
    $newRow->added_by = $info['addedBy'];
    $newRow->activity_token = $info['activityToken'];

    if ($newRow->save()) {
      $token = $this->nativeRand().'__atbl'.mt_rand(100, 999).$newRow->id.$this->nativeRand();
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



  public function __deleteTrip($info){
    // Check If Row Exists
    $oldRow = $this->rowExists('app_trip_list', 'token', $info['tripToken']);
    if($oldRow['status'] == true){
      // Take Old Row Object
      $oldRow = $oldRow['row'];
      $newRow = TripListModel::where('token', $info['tripToken'])->first();
      $newRow->existence = $info['existence'];
      $newRow->added_by = $info['addedBy'];
      $newRow->activity_token = $info['activityToken'];

      if($newRow->save()){
        // Make Update Logger Data Format
        $logData = $this->updateLoggerData($oldRow->id, 'app_trip_list', $oldRow, $newRow, $info['remark'], $info['addedBy'], $info['activityToken']);
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

  public function __newBreakdown($info){
    $newRow = new TripBreakdownModel;
    $newRow->banner_token = $info['bannerToken'];
    $newRow->bus_token = $info['busToken'];
    $newRow->trip_token = $info['tripToken'];
    $newRow->departure_point = $info['departurePoint'];
    $newRow->related_departure_counter = $info['relatedDepartureCounter'];
    $newRow->destination_point = $info['destinationPoint'];
    $newRow->related_destination_counter = $info['relatedDestinationCounter'];
    $newRow->departure_time = $info['departureTime'];
    $newRow->destination_time = $info['destinationTime'];
    $newRow->fare = $info['fare'];
    $newRow->online_charge = $info['onlineCharge'];
    $newRow->status = $info['status'];
    $newRow->existence = $info['existence'];
    $newRow->added_by = $info['addedBy'];
    $newRow->activity_token = $info['activityToken'];

    if ($newRow->save()) {
      $token = $this->nativeRand().'__atbl'.mt_rand(100, 999).$newRow->id.$this->nativeRand();
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

  public function __updateTripBreakdown($info){
    // Check If Row Exists
    $oldRow = $this->rowExists('app_trip_breakdown_list', 'token', $info['tripBreakdownToken']);
    if($oldRow['status'] == true){
      // Take Old Row Object
      $oldRow = $oldRow['row'];
      $newRow = TripBreakdownModel::where('token', $info['tripBreakdownToken'])->first();
      $newRow->departure_point = $info['departurePoint'];
      $newRow->related_departure_counter = $info['relatedDepartureCounter'];
      $newRow->destination_point = $info['destinationPoint'];
      $newRow->related_destination_counter = $info['relatedDestinationCounter'];
      $newRow->departure_time = $info['departureTime'];
      $newRow->destination_time = $info['destinationTime'];
      $newRow->fare = $info['fare'];
      $newRow->online_charge = $info['onlineCharge'];
      $newRow->status = $info['status'];
      $newRow->existence = $info['existence'];
      $newRow->added_by = $info['addedBy'];
      $newRow->activity_token = $info['activityToken'];

      if($newRow->save()){
        // Make Update Logger Data Format
        $logData = $this->updateLoggerData($oldRow->id, 'app_trip_breakdown_list', $oldRow, $newRow, $info['remark'], $info['addedBy'], $info['activityToken']);
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





}
