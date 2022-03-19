<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

use Illuminate\Http\Request;
use App\Models\UserListModel;
use App\Models\PlaceListModel;
use App\Models\SysDeviceListModel;
use App\Models\SysActivityListModel;
use App\Models\SysUserSessionListModel;
use App\Models\CounterAndUserRelModel;
use App\Models\TripBookingListModel;
use App\Models\TripBreakdownModel;
use App\Models\CounterAndTripRelModel;
use App\Models\BusListModel;
use App\Models\TripListModel;
use App\Models\TripBookingDetailsModel;
use App\Models\BookingSeatModel;
use App\Models\CountdownTime;
use App\Models\StaticSeatLayoutModel;
use App\Models\SmsSwitch;
use Session;
use Cache;
use App\Http\Controllers\Servers\System;
use App\Http\Controllers\Servers\Helpers\Helper;
use App\Http\Controllers\Servers\Helpers\CamelCase;

use Carbon\Carbon;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;

class BetaController extends Controller
{
    use Helper;

    public function ajaxLoginAction(Request $request){


        $check = UserListModel::where('phone', $request->phone)->where('password', $this->nativeHash($request->password))->where('type', 'Counter Related')->where('status', 'Active')->where('existence', 1);

        $count = $check->count();

        if($count == 1){
        $row = $check->first();

        $newRow = new SysUserSessionListModel;
        $newRow->app_token = $request->header('AppToken');
        $newRow->user_token = $row->token;
        $newRow->user_agent = $request->header('User-Agent');
        $newRow->ip_address = $request->ip();
        $newRow->source = $request->header('Source');
        $newRow->starting_device_token = $request->header('DeviceToken');
        $newRow->started_at = date('Y-m-d h:i:s');
        $newRow->ending_device_token = NULL;
        $newRow->status = 'Active';
        $newRow->existence = 1;
        $newRow->added_by = $row->token;
        $newRow->activity_token = $this->activityToken($request);
        $newRow->save();

        $token = $this->nativeRand().'__susl'.mt_rand(100, 999).$newRow->id.$this->nativeRand();
        $newRow->token = $token;
        $newRow->save();

        $relatedCounter = CounterAndUserRelModel::where('user_token', $row->token)->first();

        $data = [];
        $data['status'] = 'Success';
        $data['sessionToken'] = $newRow->token;
        $data['user'] = $row;
        $data['counter'] = $relatedCounter->counter;

        return response()->json($data);

        }else{
        $data['status'] = 'Error';

        return response()->json($data);

        }

    }

    public function ajaxDeviceToken(Request $request){
        $newRow = new SysDeviceListModel;
        $newRow->app_token = $request->header('AppToken');
        $newRow->user_agent = $request->header('User-Agent');
        $newRow->ip_address = $request->ip();
        $newRow->source = $request->header('Source');
        $newRow->status = 'Active';
        $newRow->existence = 1;
        $newRow->added_by = 0;
        $newRow->activity_token = $this->activityToken($request);
        $newRow->save();

        $token = $this->nativeRand().'__sdl'.mt_rand(100, 999).$newRow->id.$this->nativeRand();
        $newRow->token = $token;
        $newRow->save();

        $data = [];
        $data['DeviceToken'] = $newRow;
        $data['status'] = 'Success';

        return response()->json($data);
    }

    public function activityToken($request){
        $newRow = new SysActivityListModel;
        $newRow->device_token = $request->header('DeviceToken') ?? 0;
        $newRow->app_token = $request->header('AppToken');
        $newRow->user_token = $request->header('UserToken') ?? 0;
        $newRow->user_agent = $request->header('User-Agent') ;
        $newRow->ip_address = $request->ip();
        $newRow->source = $request->header('Source');
        $newRow->request_method = $request->method();
        $newRow->request_to = 'Web';
        $newRow->endpoint = $request->path();
        $newRow->role_token = NULL;
        $newRow->request = json_encode(json_decode(json_encode($request->all()), true));
        $newRow->crud_info = NULL;
        $newRow->message = NULL;
        $newRow->response = NULL;
        $newRow->request_dt = date('Y-m-d h:i:s');
        $newRow->response_dt = date('Y-m-d h:i:s');
        $newRow->status = 'Active';
        $newRow->existence = 1;
        $newRow->added_by = $request->header('UserToken') ?? 0;
        $newRow->save();

        $token = $this->nativeRand().'__sal'.mt_rand(100, 999).$newRow->id.$this->nativeRand();
        $newRow->token = $token;
        $newRow->save();
        return $newRow->token;
    }
    public function demoPassword($pass){
        return $this->nativeHash($pass);
    }


    public function ajaxPlacesList(){
        $list = PlaceListModel::where('existence', 1)->where('status', 'Active')->get();

        $data = [];
        $data['status'] = 'Success';
        $data['placeList'] = $list;

        return response()->json($data);
    }

    public function ajaxTripBookingAction(Request $request){
	    $user = UserListModel::where('phone', $request->phone);
        $checkUser = $user->count();

        if($checkUser != 1){

        $newRow = new UserListModel;
        $newRow->name = $request->name;
        $newRow->phone = $request->phone;
        $newRow->type = 'App User';
        $newRow->status = 'Active';
        $newRow->existence = 1;
        $newRow->added_by = $request->header('userToken') ?? 0;
        $newRow->activity_token = $this->activityToken($request);
        $newRow->save();

        $token = $this->nativeRand().'__aul'.mt_rand(100, 999).$newRow->id.$this->nativeRand();
        $newRow->token = $token;
        $newRow->save();

        $newTripBookingRow = new TripBookingListModel;
        $newTripBookingRow->booking_number = '#'.mt_rand(100, 99999);
        $newTripBookingRow->departure_point = $request->from;
        $newTripBookingRow->destination_point = $request->to;
        $newTripBookingRow->booked_for = $newRow->token;
        $newTripBookingRow->name_on_ticket = $newRow->name;
        $newTripBookingRow->emergency_contact = $newRow->phone;
        $newTripBookingRow->journey_date = $request->date;
		$newTripBookingRow->booked_type ="Counter User";
		$newTripBookingRow->ticket_type ="Customer";
        $newTripBookingRow->status = 'Active';
        $newTripBookingRow->existence = 1;
        $newTripBookingRow->added_by = $request->header('userToken') ?? 0;
        $newTripBookingRow->activity_token = $this->activityToken($request);
        $newTripBookingRow->save();

        $bookingToken = $this->nativeRand().'__aul'.mt_rand(100, 999).$newTripBookingRow->id.$this->nativeRand();
        $newTripBookingRow->token = $bookingToken;
        $newTripBookingRow->save();
        $data = [];
        $data['status'] = "Success";
    	$data['customerToken'] = $bookingToken;
        return response()->json($data);
        }
        else{

        $userData = $user->first();

        $newTripBookingRow = new TripBookingListModel;
        $newTripBookingRow->booking_number = '#'.mt_rand(100, 99999);
        $newTripBookingRow->departure_point = $request->from;
        $newTripBookingRow->destination_point = $request->to;
        $newTripBookingRow->booked_for = $userData->token;
        $newTripBookingRow->name_on_ticket = $userData->name;
        $newTripBookingRow->emergency_contact = $userData->phone;
        $newTripBookingRow->journey_date = $request->date;
		$newTripBookingRow->booked_type ="Counter User";
		$newTripBookingRow->ticket_type ="Customer";
        $newTripBookingRow->status = 'Active';
        $newTripBookingRow->existence = 1;
        $newTripBookingRow->added_by = $request->header('userToken') ?? 0;
        $newTripBookingRow->activity_token = $this->activityToken($request);
        $newTripBookingRow->save();

        $bookingToken = $this->nativeRand().'__aul'.mt_rand(100, 999).$newTripBookingRow->id.$this->nativeRand();
        $newTripBookingRow->token = $bookingToken;

        $newTripBookingRow->save();

        $data = [];
        $data['status'] = "Success";
        $data['customerToken'] = $bookingToken;
        	return response()->json($data);
        }
    }
	
  	//trip list and seat count
    public function ajaxTripsList(Request $request){

        $user = CounterAndUserRelModel::where('user_token',$request->header('userToken'))->get()->pluck('counter_token')->toArray();
        $trip_token = CounterAndTripRelModel::whereIn('counter_token',$user)->get()->pluck('trip_token')->toArray();

        $list = TripBreakdownModel::with('bus_name')->where('departure_point', $request->from)
			->where('destination_point', $request->to)->whereIn('trip_token',$trip_token)->get();

				if($list->isNotEmpty()){
					foreach($list as $key => $item){
						$available_seat = TripBookingDetailsModel::where('trip_token',$item->trip_token)->where('journey_date',$request->journeyDate)->where('status','book')->count();
                  
							$total_seats = json_decode($item->bus->seat->title,true);
								$total_seat_count = 0;
								foreach($total_seats as $total){
									foreach($total as $i){
										if($i['name']){
											$total_seat_count++;
										}
									}
								}
					$item->start  = date('h:i A', strtotime($item->departure_time));
						if($item->trip->seat_access!=2){
							 $todaytime = Carbon::now()->format('h:i: A');
								if($item->start!=$todaytime){
									$item->from   = $item->departurePoint->title;
									$item->to     = $item->destinationPoint->title;
									$item->active = 1;
									$item->available_seat = $total_seat_count-$available_seat;
									$item->beakdownToken = $item->token;
									$item->tripToken = $item->trip->token;
				
									$item->start  = date('h:i A', strtotime($item->departure_time));
									$item->end    = date('h:i A', strtotime($item->destination_time));
								}
							}else{
								$from['from'] = PlaceListModel::where('token', $request->from)->first()->title;
								$to['to']     = PlaceListModel::where('token', $request->to)->first()->title;

								$data = [];
								$data['customerToken']      = "";
								$data['breakdownToken'] = "";
								$data['from']           = $from['from'];
								$data['to']             = $to['to'];
								$data['time']           = "";
								$data['status']         = "error";
								$data['trips']          = [];
								return response()->json($data);
							}
						}

					$data = [];

					$data['userToken']  = $request->header('userToken');
					$data['from']       = $item->from;
					$data['to']         = $item->to;
					$data['time']       = $item->start;
					$data['status']     = "Success";
					$data['trips']      = $list;

					return response()->json($data);

		    }else{
					$from['from'] = PlaceListModel::where('token', $request->from)->first()->title;
					$to['to']     = PlaceListModel::where('token', $request->to)->first()->title;

					$data = [];
					$data['customerToken']  = "";
					$data['breakdownToken'] = "";
					$data['from']           = $from['from'];
					$data['to']             = $to['to'];
					$data['time']           = "";
					$data['status']         = "error";
					$data['trips']          = [];
					return response()->json($data);
			}
      }
  
  	//after select breakdown seat layout
    public function seatView(Request $request){

        $break_down  = TripBreakdownModel::where('token',$request->header('breakdownToken'))->first();

        if(!empty($break_down)){
            $bus = BusListModel::with('seat')->where('token',$request->header('busToken'))->first();
            //$bus = BusListModel::with('seat')->where('token',$request->header('busToken'))->where('banner_token',$break_down->banner_token)->first();
            $seat_list    = json_decode($bus->seat->title,true);


			$hold_seats = CountdownTime::whereDate('journey_date', $request->journeyDate)->whereBetween('created_at', [now()->subMinute(3),now()])->where('trip_token',$break_down->trip_token)->pluck('seat_details')->toArray();

			$seat_bookings =TripBookingDetailsModel::where('trip_token',$break_down->trip_token)->whereDate('journey_date',$request->journeyDate)
->where('status','book')->get()->pluck('seat_title')->toArray();

            $new_arr = array();
            foreach($seat_list as $key => $item){
                $new_sub = array();
                // dd($item);
                foreach($item as $sub_key => $sub_item){
                    if(in_array($sub_item['name'], $seat_bookings)){
                        $new_sub[$sub_key] = [
                            'name' => $sub_item['name'],
                            'status' => 0,
                        ];
                    }elseif(in_array($sub_item['name'], $hold_seats)){
                        $new_sub[$sub_key] = [
                            'name' => $sub_item['name'],
                            'status' => 0,
                        ];
                    }else{
					      $new_sub[$sub_key] = [
                            'name' => $sub_item['name'],
                            'status' => 1,
                        ];
					}
                }
                $new_arr[$key] = $new_sub;
            }

            // dd($new_arr);

            $data                    = [];
            $data['seats']           = $new_arr;
            $data['breakdownToken']  = $break_down->token;
            $data['status']          = "Success";
            return response()->json($data);
        }else{
            $data = [];
            $data['seats'] = [];
            $data['breakdownToken']  = "";
            $data['status'] = "error";
            return response()->json($data);
        }
    }
  	//after seat submit this function work
    public function showSeat(Request $request){
        $break_down  = TripBreakdownModel::with('departurePoint','destinationPoint')->where('token',$request->header('breakdownToken'))->first();

  		$response = $request->json()->all();
          
      	$booking_number = TripBookingListModel::where('token',$request->header('customerToken'))->first();
        
        $hold_check_user = CountdownTime::where('user_token',$request->header('userToken'))->where('trip_token',$break_down->trip_token)->whereDate('journey_date', $booking_number->journey_date)->whereBetween('created_at', [now()->subMinute(3),now()])->pluck('seat_details')->toArray();
        
      	$arr = array_intersect($response['seats'],$hold_check_user);
   //dd($hold_check_user);
    
          if(count($hold_check_user)<1){
                $data = [];
                $data['bookable_seat'] = [];
                $data['message'] = "Please select others seat";
                $data['status'] = "error";
                return response()->json($data);
          }
        Cache::put('seats', $arr);  
      
		$unique = BookingSeatModel::where('customer_token',$request->header('customerToken'))
        ->where('trip_token',$break_down->trip_token)->whereIn('seat_details',$arr)->get()
        ->pluck('seat_details')->toArray();
      
        if(!empty($break_down)){
            $bus  = BusListModel::with('seat')->where('token',$request->header('busToken'))->first();

            foreach($arr as $seat) {
			  if(!in_array($seat,$unique)){

					$selected_seat                       = new BookingSeatModel;
					$selected_seat->bus_token            = $bus->token;
					$selected_seat->unique_id            = '#7'.mt_rand(100, 999);
					$selected_seat->counter_token        = $request->header('counterToken');
					$selected_seat->user_token           = $request->header('userToken');
					$selected_seat->customer_token       = $request->header('customerToken');
					$selected_seat->breakdown_token      = $request->header('breakdownToken');
					$selected_seat->trip_token           = $break_down->trip_token;
					$selected_seat->seat_details         = $seat;
					$selected_seat->fare                 = $break_down->fare;
					$selected_seat->save();
					$bookingToken = $this->nativeRand().'__aul'.mt_rand(100, 999).$selected_seat->id.$this->nativeRand();
					$selected_seat->token = $bookingToken;
					$selected_seat->save();
			    }
            }

            $seat_show = BookingSeatModel::where('customer_token',$request->header('customerToken'))
            ->where('trip_token',$break_down->trip_token)->whereIn('seat_details',$arr)->get();
            $seat_count = $seat_show->pluck('seat_details')->toArray();

            $fare = count($seat_count) * $break_down->fare;
			$data = [];
            $data['bookable_seat']          = $seat_show;
            $data['fare']                   = $fare;
            $data['from']                   = $break_down->departurePoint->title;
            $data['to']                     = $break_down->destinationPoint->title;
            $data['booking_number']         = $booking_number->booking_number;
            $data['journey_time']           = $break_down->departure_time;

            $data['status']                 = "Success";
            return response()->json($data);
        }else{
			$data = [];
            $data['bookable_seat'] = [];
            $data['status'] = "error";
            return response()->json($data);
		}
    }
    public function bookingSeat(Request $request){

		$seat = Cache::get('seats');
        $break_down  = TripBreakdownModel::with('trip','departurePoint','destinationPoint','counter_token')->where('token',$request->header('breakdownToken'))
			->first();

		$bus_name = $break_down->bus->brand;
		$bus_token = $break_down->bus->token;

        if(!empty($break_down)){

			$customer  = TripBookingListModel::where('token',$request->header('customerToken'))->first();

        	$booking_seats = BookingSeatModel::with('book_info')->where('customer_token',$request->header('customerToken'))
                ->where('user_token',$request->header('userToken'))->where('counter_token',$request->header('counterToken'))
                ->whereIn('seat_details',$seat)->where('trip_token',$break_down->trip_token)->get();

        $banner_token                = $break_down->banner_token;
        //$trip_token                  = $request->header('tripToken');
        $trip_breakdown_token        = $break_down->token;
        $departure_point             = $break_down->departure_point;
        $related_departure_counter   = $break_down->related_departure_counter;
        $destination_point           = $break_down->destination_point;
        $related_destination_counter = $break_down->related_destination_counter;
        $counter_token               = $break_down->counter_token->counter_token;

        $journey_time                = $break_down->departure_time;
        $trip_no                     = $break_down->trip->trip_number;

        //booking  customer

        $customer_token             = $customer->token;
        $booked_number              = $customer->booking_number;
        $booked_for                 = $customer->booked_for;

        $name_on_ticket             = $customer->name_on_ticket;
        $emergency_contact          = $customer->emergency_contact;
        $journey_date               = $customer->journey_date;

        $check_pluck_seat = TripBookingDetailsModel::whereDate('journey_date',$customer->journey_date)
        ->where('trip_token',$break_down->trip_token)->where('status','book')->get()->pluck('seat_title')->toArray();

		   foreach($booking_seats as $seat){
            if(!in_array($seat->seat_details,$check_pluck_seat)){
				$booking                       = new TripBookingDetailsModel;
				$booking->banner_token         = $banner_token;
				$booking->trip_token           = $break_down->trip_token;
				$booking->trip_breakdown_token = $trip_breakdown_token;
				$booking->customer_token       = $customer_token;
			    $booking->counter_token        = $counter_token;
				$booking->type                 = "Counter User";
				$booking->booked_by            = $request->header('userToken');
				$booking->customer_token       = $customer_token;
				$booking->booking_number       = $booked_number;
				$booking->departure_point      = $departure_point;
				$booking->related_departure_counter = $related_departure_counter;
				$booking->destination_point    = $destination_point;
				$booking->related_destination_counter = $related_destination_counter;
				$booking->seat_token            =   $seat->token;
				$booking->bus_token             =   $bus_token;
				$booking->seat_title            =   $seat->seat_details;
				$booking->seat_identifier       =   $seat->unique_id;
              	$booking->fare                  =   $seat->fare;
				$booking->final_fare            =   $seat->fare;
				$booking->booked_for            =   $booked_for;
				$booking->name_on_ticket        =   $name_on_ticket;
				$booking->emergency_contact     =   $emergency_contact;
				$booking->journey_date          =   $journey_date;
				$booking->journey_time          =   $journey_time;
				$booking->status                =   'book';
				$booking->existence             =   1;
				$booking->added_by              =   $request->header('UserToken') ?? 0;
				$booking->activity_token        =   $this->activityToken($request);
				$booking->save();

				$bookingToken = $this->nativeRand().'__aul'.mt_rand(100, 999).$booking->id.$this->nativeRand();
				$booking->token = $bookingToken;
				$booking->save();
               }else{
                    $data = [];
                    $data['status'] = "error";
                    $data['message'] = 'Your Seat is Already Booked';
                    return response()->json($data);
               }
			}
			$totalFare = 0;
				$sms_seats = [];
				$seat_random = [];
				  foreach($booking_seats as $sms){
					  $totalFare = $totalFare + $sms['fare'];
					  array_push($sms_seats, $sms['seat_details']);
					  array_push($seat_random,$sms['unique_id']);
				  }

				$textSMSSeats = implode(',', $sms_seats);

				$textSMSRandoms = implode(',', $seat_random);

	  			$message = "Booking Successful! Your booked seats: $textSMSSeats. seat uniques: $textSMSRandoms. Bus: $bus_name. In: $journey_date. At: $journey_time. SubTotal: $totalFare. Trip Number: $trip_no. ";
				$sms = SmsSwitch::where('status','1')->first();
                $admin_contact = $sms->admin_counter_sms;
                
          		if($sms->counter_sms==1){
					$sendSMS = $this->sendSMS($emergency_contact, $message, 'Busbook');
                }
                if($sms->is_admin_counter==1){
                    $sendSMS = $this->sendSMS($admin_contact, $message, 'Busbook'); 
                }
				$data = [];
				$data['status'] = "Success";
				$data['action'] = 'Booking Completed';
			  return response()->json($data);
		}
    }
    public function showHistory(Request $request){

        $user = CounterAndUserRelModel::where('user_token',$request->header('userToken'))->get()->pluck('counter_token')->toArray();
        $counterToken = CounterAndTripRelModel::whereIn('counter_token',$user)->get()->pluck('trip_token')->toArray();
		
        $list = TripListModel::with('banner')->whereIn('token',$counterToken)->get();

        if($list->isNotEmpty()){
            foreach($list as $key => $item){
                $item->active         = 1;
				$item->triptoken      = $item->token;
     			$item->bus_name       = $item->banner->title_bangla;
                $item->from           = $item->departurePoint->title;
                $item->to             = $item->destinationPoint->title;
                $item->start          = $item->departure_time;
            }
            $data = [];

            $data['status']     = "Success";

            $data['trips']      = $list;

            return response()->json($data);
        }else{
            $data['status']     = "error";

            $data['trips']      = [];

            return response()->json($data);
        }

    }
    public function historyDetails(Request $request){
  
      $user_token = $request->header('userToken');
 
        $self_counter = TripBookingDetailsModel::where('trip_token',$request->header('tripToken'))->where('booked_by',$user_token)->where('type','Counter User')->whereDate('journey_date',$request->date)->where('status','book')->get();

		 $others = TripBookingDetailsModel::where('trip_token',$request->header('tripToken'))->where('booked_by','!=',$user_token)->whereDate('journey_date',$request->date)->where('type','Counter User')->where('status','book')->get();
	
				
		 $online_user = TripBookingDetailsModel::where('trip_token',$request->header('tripToken'))->where('type','App User')->whereDate('journey_date',$request->date)->where('status','book')->get();
  

          $self = $self_counter->pluck('final_fare')->sum();
          $others = $others->pluck('final_fare')->sum();
          $online = $online_user->pluck('fare')->sum();
		
		 if($user_token){
            $data = [];
			$data['status']                = "success";
			$data['self_counter_sale']     = $self;
			$data['others_counter_sale']   = $others;
			$data['online_sale']           = $online;
			return response()->json($data);
		 }else{
            $data = [];
		 	$data['status']               = "error";
			$data['self_counter_sale']    = 0;
			$data['others_counter_sale']  = 0;
			$data['online_sale']          = 0;
			return response()->json($data);
		 }

    }
     public function seatDetails(Request $request){

       $seat_details = TripBookingDetailsModel::with('trip')->where('trip_token',$request->header('tripToken'))->whereDate('journey_date',$request->date)->where('status','book')->get();
    
       if($seat_details->isNotEmpty()){
         $data           = [];
         $data['status'] = "success";
         $data['seat_details'] = $seat_details;
         return response()->json($data);
       }else{
         $data           = [];
         $data['status'] = "error";
         $data['seat_details'] = [];
         return response()->json($data);
       }
     }
     public function seeSeat(Request $request){

        $bus = BusListModel::with('seat')->where('token',$request->bus_token)->first();
        $seat_list    = json_decode($bus->seat->title,true);
       
        $seat_bookings = TripBookingDetailsModel::where('trip_token',$request->header('tripToken'))
        ->where('journey_date',$request->journeyDate)->where('status','book')->get();
       
        $new_arr = array();
        foreach($seat_list as $key => $item){
            $new_sub = array();
            // dd($item);
            foreach($item as $sub_key => $sub_item){
                $booking_status = false;
                $user_type = 'normal';
                foreach($seat_bookings as $seat_booking){

                    if(!$booking_status && $sub_item['name'] == $seat_booking->seat_title){
                        $booking_status = true;
                        $user_type      = $seat_booking->type;
                    }
                }

                if($booking_status){
                    $new_sub[$sub_key] = [
                        'name' => $sub_item['name'],
                        'status' => 0,
                        'user_type' => $user_type
                    ];
                }else if(!$booking_status){
                    $new_sub[$sub_key] = [
                        'name' => $sub_item['name'],
                        'status' => 1,
                    ];
                }

            }
            $new_arr[$key] = $new_sub;
        }

        $data                    = [];
        $data['seats']           = $new_arr;
        $data['status']          = "Success";
        return response()->json($data);
      

     }
       
   
	  public function cacheClear(){

        Artisan::call('cache:clear');

        return redirect('beta/login');
    }
}

