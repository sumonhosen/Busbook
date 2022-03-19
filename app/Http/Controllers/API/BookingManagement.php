<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\UserListModel;
use App\Models\TripBookingListModel;
use App\Models\BusListModel;
use App\Models\TripBookingDetailsModel;
use App\Models\TripBreakdownModel;
use App\Models\CounterAndTripRelModel;
use App\Models\PlaceListModel;
use App\Models\StaticSeatLayoutListModel;
use App\Models\StaticSeatLayoutRowListModel;
use App\Models\StaticSeatLayoutRowColumnListModel;
use App\Models\CountdownTime;
use App\Http\Controllers\Servers\System;
use App\Http\Controllers\Servers\Helpers\Helper;
use App\Http\Controllers\Servers\Helpers\CamelCase;

class BookingManagement extends Controller
{
    use Helper;
    public function initiateBooking(Request $request){
        $proceed = true;
        if ($proceed == true) {
            # CHECK USER
            $user = UserListModel::where('phone', $request->phone)->where('type', 'App User');
            $userCount = $user->count();
            if ($userCount == 1) {
                $user = $user->first();
                $userToken = $user->token;
                $userName = $user->name;
            }
            else{
                # CREATE NEW USER
                $newUser = new UserListModel;
                $newUser->phone = $request->phone;
                $newUser->name = $request->name;
                $newUser->type = "App User";
                $newUser->status = "Active";
                $newUser->existence = 1;
                $newUser->save();
                $token = $this->nativeRand().'__aul'.mt_rand(100, 999).$newUser->id.$this->nativeRand();
                $newUser->token = $token;
                $newUser->save();
                $userToken = $newUser->token;
                $userName = $newUser->name;
            }

            # NOW INSERT A NEW BOOKING
            $newBooking = new TripBookingListModel;
            $newBooking->banner_token = $request->bannerToken;
            $newBooking->booking_counter = $request->header('counterToken');
            $newBooking->trip_token = $request->tripToken;
            $newBooking->trip_breakdown_token = $request->tripBreakdownToken;
            $newBooking->journey_time = $request->journeyTime;
            /* BRING JOURNEY TIME */
            $newBooking->departure_point = $request->from;
            $newBooking->destination_point = $request->to;
            $newBooking->booked_for = $userToken;
            $newBooking->name_on_ticket = $userName;
            $newBooking->journey_date = $request->journeyDate;
            $newBooking->status = 'Active';
            $newBooking->existence = 1;
            $newBooking->added_by = $request->header('UserToken');
            $newBooking->activity_token = $request->activityToken;
            $newBooking->save();
            $token = $this->nativeRand().'BookingToken'.mt_rand(100, 999).$newBooking->id.$this->nativeRand();
            $newBooking->token = $token;
            $newBooking->booking_number = mt_rand(100,999).$newBooking->id;
            $newBooking->save();

            $data = [];
            $data['bookingToken'] = $token;
            $data['tripBreakdownToken'] = $newBooking->trip_breakdown_token;
            $data['genToken'] = $token;
            if ($request->header('counterToken') == 'ONLINE_COUNTER') {
                $data['timerTime'] = 3;
            }
            else{
                $data['timerTime'] = 5;
            }
            $data['seatRowColumnCount'] = 5;
            return response()->json($this->apiResponse('Success', 'LIST_LOADED', $data, null));
        }
    }

    public function historyBookingList(Request $request){
        # if ... else - for counter todo
        if ($request->mode == 'user') {
            $list = TripBookingListModel::where('added_by', $request->header('UserToken'))->where('status', 'Active')->where('booking_status', 'Completed')->where('existence', 1)->get();
        }
        else{
            $list = TripBookingListModel::where('booking_counter', $request->header('counterToken'))->where('status', 'Active')->where('booking_status', 'Completed')->where('existence', 1)->get();
        }

        foreach ($list as $key => $value) {
            if ($value->booked_for != $request->header('UserToken')) {
                $value->bookedFor = 'Other - '.UserListModel::where('token', $value->booked_for)->first()->name;
            }
            else{
                $value->bookedFor = 'Own';
            }
            $value->bannerTitle = $value->banner->title;
            $value->tripNumber = $value->trip->trip_number;
            $value->departurePoint = $value->departurePoint;
            $value->destinationPoint = $value->destinationPoint;
            $value->journeyDate = date('d/m/Y', strtotime($value->journey_date));
            $value->journeyTime = date("h:i A", strtotime($value->journey_time));
        }

        $data = [];
        $data['bookingHistory'] = CamelCase::camelCaseObject($list);
        return response()->json($this->apiResponse('Success', 'LIST_LOADED', $data, null));
    }

      public function holdSeat(Request $request){
       
        $break_down  = TripBreakdownModel::where('token',$request->header('breakdownToken'))->first();
        if(!empty($break_down)){

            $counterToken = CounterAndTripRelModel::where('trip_token', $break_down->trip_token)
            ->where('counter_token',$request->header('counterToken'));

            $submit_seat =  $request->seat;

          $check_user = CountdownTime::where('seat_details',$submit_seat)->where('trip_token',$request->header('tripToken'))->whereDate('journey_date', $request->journeyDate)->whereBetween('created_at', [now()->subMinute(3),now()])->first();
		
			//dd($check_user);
          
            if(!isset($check_user->id)){
				$checked_seat                       = new CountdownTime;
                $checked_seat->seat_details         = $submit_seat;
				$checked_seat->journey_date         = $request->journeyDate;
				$checked_seat->trip_token           = $request->header('tripToken');
                $checked_seat->user_token           = $request->header('userToken');
                $checked_seat->set_time             = "5";
              
                $checked_seat->status               = 1;
                $checked_seat->save();

                $countToken = $this->nativeRand().'__aul'.mt_rand(100, 999).$checked_seat->id.$this->nativeRand();
                $checked_seat->token = $countToken;
                $checked_seat->save();
				$data = [];
                $data['seat']          = $checked_seat;
				$data['hold']		   = 1;
				$data['status']        = 1;
				$data['message']       = "Hold";
                return response()->json($data);

			}else{
              // dd($check_user);
					$hold_tiket = CountdownTime::where('user_token',$request->header('userToken'))->where('seat_details',$submit_seat)->where('trip_token',$request->header('tripToken'))->whereDate('journey_date', $request->journeyDate)->first();
              		$seat = $submit_seat;
					$data = [];
              
              		if(isset($hold_tiket->id)){
                      $hold_tiket->delete();				
                      $data['seat']          = $seat;
                      $data['hold']		   = 0;
                      $data['status']        = 1;
                      $data['message'] 	   = "Hold out";
                    }else{
                      $data['seat']          = $seat;
                      $data['hold']		   = 1;
                      $data['status']        = 1;
                      $data['message'] 	   = "Already Hold others";
                    }
     

					return response()->json($data);
				}
	        }
	  }
	public function delete_hold(Request $request){
		$delete_seat = CountdownTime::where('user_token',$request->header('userToken'))->delete();
      //->where('trip_token',$request->header('tripToken'))
		$data = [];
		$data['status']        = 1;
		$data['message'] 	   = "success";
		return response()->json($data);
	} 

    public function selectSeat(Request $request){
        $proceed = true;
        if ($proceed == true) {
            $onlineCounterTime = 100;
            $offlineCounterTime = 600;
            $bookingToken = $request->bookingToken;
            $seatToken = $request->seatToken;
            $seatTitle = $request->seatTitle;
            $tripBreakdownToken = $request->tripBreakdownToken;
            # GET BOOKING DETAILS
            $bookingRow = TripBookingListModel::where('token', $bookingToken)->first();
            # GET BREAKDOWN DETAILS
            $tripBreakdownRow = TripBreakdownModel::where('token', $tripBreakdownToken)->first();
            # CHECK SEAT STATUS WITH SEAT, TRIP BREAKDOWNTOKEN AND DATE
            $seatStatus =  TripBookingDetailsModel::where('seat_token', $seatToken)->where('journey_date', $bookingRow['journey_date'])->where('journey_time', $bookingRow['journey_date'])->where('existence', 1);
            # Make Booking Procedure - Code More. 'BOOK_NEW', 'UNBOOK', 'NO_BOOK'
            if ($seatStatus->count() == 1) {
                $seatRow = $seatStatus->first();
                $bookingProcedure = 'UNBOOK';
            }
            else{
                $firstBookInBooking = TripBookingDetailsModel::where('booking_token', $bookingToken)->orderBy('id', 'asc')->limit(1)->first();
                # CHECK TIME ELAPSED ACCORDING TO COUNTER





                $bookingProcedure = 'BOOK_NEW';
            }

            # DEFINE ONLINE_CHARGE
            if($request->header('counterToken') != 'ONLINE_COUNTER'){
                $onlineCharge = 0;
            }
            else{
                $onlineCharge = $tripBreakdownRow['online_charge'];
            }

            if ($bookingProcedure == 'BOOK_NEW') {
                $bookNew = new TripBookingDetailsModel;
                $bookNew->banner_token = $tripBreakdownRow['banner_token'];
                $bookNew->trip_token = $tripBreakdownRow['trip_token'];
                $bookNew->trip_breakdown_token = $tripBreakdownRow['token'];
                $bookNew->booking_token = $bookingRow['token'];
                $bookNew->booking_number = $bookingRow['booking_number'];
                $bookNew->booking_counter = $request->header('counterToken');
                $bookNew->boarding_counter = $request->header('counterToken');
                $bookNew->departure_point = $tripBreakdownRow['departure_point'];
                $bookNew->related_departure_counter = $tripBreakdownRow['related_departure_counter'];
                $bookNew->destination_point = $tripBreakdownRow['destination_point'];
                $bookNew->related_destination_counter = $tripBreakdownRow['related_destination_counter'];
                $bookNew->seat_token = $seatToken;
                $bookNew->seat_title = $seatTitle;
                $bookNew->seat_identifier = mt_rand(100, 999);
                $bookNew->fare = $tripBreakdownRow['fare'];
                $bookNew->online_charge = $onlineCharge;
                $bookNew->final_fare = $tripBreakdownRow['fare'] + $onlineCharge;
                $bookNew->booked_for = $bookingRow['booked_for'];
                $bookNew->name_on_ticket = $bookingRow['name_on_ticket'];
                $bookNew->journey_date = $bookingRow['journey_date'];
                $bookNew->journey_time = $tripBreakdownRow['departure_time'];
                $bookNew->status = 'On Hold By You';
                $bookNew->existence = 1;
                $bookNew->added_by = $request->header('UserToken'); // todo
                // todo more

                $bookNew->save();

                $token = $this->nativeRand().'BookingDetailsToken'.mt_rand(100, 999).$bookNew->id.$this->nativeRand();
                $bookNew->token = $token;
                $bookNew->save();

                $data = [];
                $data['action'] = 'DATA_UPDATED';
                $data['reqBody'] = Array(
                    'bookingToken' => $bookingToken,
                    'tripBreakdownToken' => $tripBreakdownToken,
                    'seatToken' => $seatToken,
                    'seatTitle' => $seatTitle
                );
                $data['seatInfo'] = $bookNew;
                return response()->json($this->apiResponse('Success', 'LIST_LOADED', $data, null));

            }
            else if ($bookingProcedure == 'UNBOOK'){
                $seatRow->existence = 1;
                $seatRow->save();

                $data = [];
                $data['action'] = 'DATA_UPDATED';
                $data['reqBody'] = Array(
                    'bookingToken' => $bookingToken,
                    'tripBreakdownToken' => $tripBreakdownToken,
                    'seatToken' => $seatToken,
                    'seatTitle' => $seatTitle
                );
                $data['seatInfo'] = $seatRow;
                return response()->json($this->apiResponse('Success', 'LIST_LOADED', $data, null));
            }
            else{
                $data = [];
                $data['action'] = 'TIME_ELAPSED';
                $data['reqBody'] = Array(
                    'bookingToken' => $bookingToken,
                    'tripBreakdownToken' => $tripBreakdownToken,
                    'seatToken' => $seatToken,
                    'seatTitle' => $seatTitle
                );
                $data['seatInfo'] = [];
                return response()->json($this->apiResponse('ERROR', 'TIME_ELAPSED', $data, null));
            }
        }
    }

    public function bookingDetails(Request $request, $bookingToken){
        $bookingRow = TripBookingListModel::where('token', $bookingToken)->first();
        $bookingDetails = Array();
        $bookingDetails['bookingNumber'] = $bookingRow['booking_number'];
        $bookingDetails['banner'] = $bookingRow->banner;
        $bookingDetails['trip'] = $bookingRow->trip;
        $bookingDetails['departurePoint'] = $bookingRow->departurePoint;
        $bookingDetails['destinationPoint'] = $bookingRow->destinationPoint;
        $bookingDetails['bookedFor'] = $bookingRow->forPerson;
        $bookingDetails['nameOnTicket'] = $bookingRow['name_on_ticket'];

        $journeyDate = $bookingRow['journey_date'];
        $journeyTime = $bookingRow['journey_time'];
        $bookingDetails['journeyDate'] = $journeyDate;
        $bookingDetails['journeyTime'] = $journeyTime;
        $bookingDetails['bookingBy'] = $bookingRow->addedBy;

        $bookedSeats = TripBookingDetailsModel::where('booking_token', $bookingToken)->where('status', 'On Hold By You')->where('existence', 1)->get();

        $totalFare = 0;
        $totalCharge = 0;

        $smsSeats = [];
        $smsSeatRandoms = [];

        foreach ($bookedSeats as $key => $bookedSeat) {
            $totalFare = $totalFare + $bookedSeat['fare'];
            $totalCharge = $totalCharge + $bookedSeat['online_charge'];
            array_push($smsSeats, $bookedSeat['seat_title']);
            array_push($smsSeatRandoms, $bookedSeat['seat_identifier']);
        }

        $subTotal = $totalFare + $totalCharge;

        $textSMSSeats = implode(',', $smsSeats);
        $textSMSRandoms = implode(',', $smsSeatRandoms);
        $textBus = $bookingRow->banner->title;
        $textTrip = $bookingRow->trip->trip_number;

        $data = [];
        $data['totalFare'] = $totalFare;
        $data['totalCharge'] = $totalCharge;
        $data['subTotal'] = $totalFare + $totalCharge;
        $data['bookingToken'] = $bookingToken;
        $data['bookedSeats'] = CamelCase::camelCaseObject($bookedSeats);
        $data['bookingDetails'] = CamelCase::camelCaseObject($bookingDetails);
        $data['sms'] = "Booking Successful! Your booked seats: $textSMSSeats. seat uniques: $textSMSRandoms. Bus: $textBus, Trip: $textTrip. In: $journeyDate. At: $journeyTime. SubTotal: $subTotal. Paid Via sslcommerz. Wear Mask & Stay Safe. BusBook Team.";
        return response()->json($this->apiResponse('Success', 'LIST_LOADED', $data, null));

    }

    public function confirmBookingCounter(Request $request){
        $sms = $request->sms;
        $proceed = true;
        if ($proceed == true) {

            $bookingRow = TripBookingListModel::where('token', $request->bookingToken)->first();
            $bookingRow->booking_status = "Completed";
            $bookingRow->save();

            $phone = $bookingRow->bookedForPerson->phone;


            $bookingDetails = TripBookingDetailsModel::where('token', $request->bookingToken)->where('status', 'On Hold By You')->where('added_by', $request->header('UserToken'))->where('existence', 1)->get();
            foreach ($bookingDetails as $key => $bookingDetail) {
                $bookingDetails->status = 'Sold';
                $bookingDetails->save();
            }
            # SEND SMS
            $sendSMS = $this->sendSMS($phone, $sms, 'OTP');
            $data = [];
            $data['action'] = 'Booking Completed';
            return response()->json($this->apiResponse('Success', 'LIST_LOADED', $data, null));

        }
    }

    public function confirmBookingUser(Request $request){
        $sms = $request->sms;
        $proceed = true;
        if ($proceed == true) {

            $bookingRow = TripBookingListModel::where('token', $request->bookingToken)->first();
            $bookingRow->booking_status = "Payment Processing";
            $bookingRow->save();

            $bannerToken = $bookingRow->banner_token;
            $tripToken = $bookingRow->trip_token;
            $bookingToken = $bookingRow->token;

            $storeID = "impre5f3af6b7eea1c";
            $storePassword = "impre5f3af6b7eea1c@ssl";
            $apiURL = "https://sandbox.sslcommerz.com/gwprocess/v4/api.php";
            $successURL = "";
            $failURL = "";
            $cancelURL = "";
            $ipnURL = "https://impressionbd.com/app/busbook/ipn/index.php?bannerToken=$bannerToken&tripToken=$tripToken&bookingToken=$bookingToken&status=success";

            //$bookingDetails = TripBookingDetailsModel::where('token', $request->bookingToken)->where('status', 'On Hold By You')->where('added_by', $request->header('UserToken'))->where('existence', 1)->get();
            //foreach ($bookingDetails as $key => $bookingDetail) {
                //$bookingDetails->status = 'Sold';
               // $bookingDetails->save();
           // }
            //$data = [];
            //$data['action'] = 'Booking Completed';
            //return response()->json($this->apiResponse('Success', 'LIST_LOADED', $data, null));

        }
    }



}
