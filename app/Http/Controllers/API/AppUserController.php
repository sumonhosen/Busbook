<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Servers\User;
use App\Models\UserListModel;
use App\Models\SysUserSessionListModel;
use App\Models\SysDeviceListModel;
use App\Models\SysActivityListModel;

use App\Models\AppOTPListModel;
use App\Models\CounterAndUserRelModel;
use App\Models\CounterAndTripRelModel;
use App\Models\PlaceListModel;
use App\Models\BusListModel;
use App\Models\TripListModel;
use App\Models\BookingSeatModel;
use App\Models\CountdownTime;
use App\Models\TripBreakdownModel;
use App\Models\PrivacyPolicy;
use App\Models\PrivacyPolicyContent;
use App\Models\TripBookingListModel;
use App\Models\TripBookingDetailsModel;
use Carbon\Carbon;
use Cache;
use App\Http\Controllers\Servers\Helpers\Helper;
use App\Http\Controllers\Servers\Helpers\CamelCase;

class AppUserController extends Controller
{
	use Helper;

    public function sendOTP(Request $request){

    	$proceed = true;
    	if ($proceed == true) {
    		$row = UserListModel::where('phone', $request->phone)->where('type', $request->type);
    		$numRows = $row->exists();
    		if ($numRows) {
                    $OTP = mt_rand(100000, 999999);	
                    $newOTP = new AppOTPListModel;
                    $newOTP->user_token = $row->first()->token;
                    $newOTP->phone = $request->phone;

                    $newOTP->otp = $OTP;
                    $newOTP->status = 'Queued';
                    $newOTP->queued_activity_token = $request->activityToken;
                    $newOTP->save();

                    $sms = "OTP for BusBook is $OTP.Don't share your OTP.Helpline 01622776655 or FB page BusBook.";
                    $sendSMS = $this->sendSMS($request->phone, $sms, 'OTP');
                    $data = [];
                    $data['status'] = 'Success';
                    $data['guideLine'] = 'Send This OTP And Phone Number In Next Request';
                    $data['otp'] = $newOTP->otp;
                    $data['phone'] = $request->phone;
                    $data['sendSMS'] = $sendSMS;

                    return response()->json($this->apiResponse('Success', 'LIST_LOADED', $data, null));
                }else{
                    $OTP = mt_rand(100000, 999999);
                    $newRow = new UserListModel;
                    $newRow->phone = $request->phone;
                    $newRow->name  = $request->name;
                    $newRow->type  = "App User";
                    $newRow->status = "Active";
                    $newRow->existence = 1;
                    $newRow->activity_token = $request->activityToken;
                    $newRow->save();
                    $token = $this->nativeRand().'__aul__AU'.mt_rand(100, 999).$newRow->id.$this->nativeRand();
                    $newRow->token = $token;
                    $newRow->save();
                    $newOTP = new AppOTPListModel;
                    $newOTP->user_token = $newRow->token;
                    $newOTP->phone = $request->phone;
                    $newOTP->otp = $OTP;
                    $newOTP->status = 'Queued';
                    $newOTP->queued_activity_token = $request->activityToken;
                    $newOTP->save();
                    $sms = "OTP for BusBook is $OTP.Don't share your OTP.Helpline 01622776655 or FB page BusBook.";
              
                    $sendSMS = $this->sendSMS($request->phone, $sms, 'OTP');
                    $data = [];
                    $data['status'] = 'Success';
                    $data['guideLine'] = 'Send This OTP And Phone Number In Next Request';
                    $data['otp'] = $newOTP->otp;
                    $data['phone'] = $request->phone;
                    $data['sendSMS'] = $sendSMS;
                return response()->json($this->apiResponse('Success', 'LIST_LOADED', $data, null));
    		}
    	}
    }

    public function checkOTP(Request $request){

    	$proceed = true;
    	if ($proceed == true) {
    		# Check OTP Is Valid Or Not
    		$row = AppOTPListModel::where('phone', $request->phone)->where('otp', $request->otp)->where('status', 'Queued');
    		$numRows = $row->count();

    		if ($numRows == 1) {
    			# Updated Status
    			$row = $row->first();
    			$row->status = 'Confirmed';
    			$row->confirmed_activity_token = $request->activityToken;
    			$row->save();

                $user = UserListModel::where('phone', $request->phone)->first();
                    # Create A New Session
                    $newSession = new SysUserSessionListModel;
                    $newSession['user_token'] = $user->token;
                    $newSession['source'] = $request->header('Source');
                    $newSession['starting_device_token'] = $request->header('DeviceToken');
                    $newSession['status'] = 'Active';
                    $newSession['existence'] = 1;
                    $newSession['activity_token'] = 'AppTest';

                    $token = $this->nativeRand().'SessionTokenOTP'.mt_rand(100, 999).$newSession->id.$this->nativeRand();
                    $newSession->token = $token;
                    $newSession->save();
                    # Send Success
                    $data = [];
                    $data['status'] = 'Success';
                    $data['guideLine'] = 'Send This OTP And Phone Number In Next Request';
                    $data['sessionToken'] = $token;
                    $data['AppUserToken'] = $user->token;
                    $data['AppUserPhone'] = $user->phone;
                    $data['userName']     = $user->name;
                    $data['counterToken'] = 'ONLINE_COUNTER';
                        return response()->json($this->apiResponse('Success', 'LIST_LOADED', $data, null));
                }else{
    			# Send Error
                $data = [];
                $data['status'] = 'Error';
                $data['guideLine'] = 'OTP Mismatch. Allow User To Send OTP Request 3 Times Highest in 24 Hours';
                return response()->json($this->apiResponse('Error', 'LIST_LOADED', $data, null));
    		}
    	}
    }

    public function activityToken($request){
        $newRow = new SysActivityListModel;
        $newRow->device_token = $request->header('DeviceToken') ?? 0;
        $newRow->app_token = $request->header('AppUserToken');
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
        $newRow->added_by = $request->header('AppUserToken') ?? 0;
        $newRow->save();

        $token = $this->nativeRand().'__sal'.mt_rand(100, 999).$newRow->id.$this->nativeRand();
        $newRow->token = $token;
        $newRow->save();
        return $newRow->token;
    }
    public function TripBookingAction(Request $request){

        $user = UserListModel::where('token',$request->header('AppUserToken'))
        ->where('phone', $request->header('AppUserPhone'));

                if($request->ticket_type=='For Me'){
  
                        $userData = $user->first();
                        $newTripBookingRow = new TripBookingListModel;
                        $newTripBookingRow->booking_number = '#'.mt_rand(100, 99999);
                        $newTripBookingRow->departure_point = $request->from;
                        $newTripBookingRow->destination_point = $request->to;
                        $newTripBookingRow->booked_for = $userData->token;
                        $newTripBookingRow->name_on_ticket = $userData->name;
                        $newTripBookingRow->emergency_contact = $userData->phone;
                        $newTripBookingRow->journey_date = $request->journeyDate;
                        $newTripBookingRow->booked_type ="App User";
                        $newTripBookingRow->ticket_type ="For Me";

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
                    
                }elseif($request->ticket_type=='Others') {
	
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
                    $newTripBookingRow->journey_date = $request->journeyDate;
                    $newTripBookingRow->booked_type ="App User";
                    $newTripBookingRow->ticket_type ="Others";
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

    public function updateUser(Request $request){

        $proceed = true;
            if ($proceed == true) {
            $row = UserListModel::where('token', $request->header('UserToken'))->first();

            $row->name = $request->name;
            $row->email = $request->email;
            $row->address = $request->address;
            $row->save();
            $data = [];
            $data['userDetails'] = CamelCase::camelCaseObject($row);
            return response()->json($this->apiResponse('Success', 'USER_UPDATED', $data, null));
        }
    }

    public function userDetails(Request $request, $userToken){

      $proceed = true;
      if ($proceed == true) {
        $row = UserListModel::where('token', $userToken)->first();

        $data = [];
        $data['userDetails'] = CamelCase::camelCaseObject($row);
        return response()->json($this->apiResponse('Success', 'LIST_LOADED', $data, null));
      }
    }

    public function TripList(Request $request){

        $todaytime = Carbon::now()->format('H:i:s');

        $tripToken = TripListModel::with('counter')->where('seat_access','!=',1)->get()->pluck('token')->toArray();

		if($request->journeyDate != Carbon::toDay()->format('Y-m-d')){
			$list = TripBreakdownModel::with('bus')->where('departure_point', $request->from)
				->where('destination_point', $request->to)->whereIn('trip_token',$tripToken)->get();
		}else{
		$list = TripBreakdownModel::with('bus')->where('departure_point', $request->from)
				->where('destination_point', $request->to)->whereIn('trip_token',$tripToken)->whereTime('departure_time', '>', $todaytime)->get();
		}
		
		//>whereTime('departure_time', '>', $todaytime)

				if($list->isNotEmpty()){
					foreach($list as $key => $item){
						$available_seat = TripBookingDetailsModel::where('trip_token',$item->trip_token)->whereDate('journey_date',$request->journeyDate)->where('status','book')->count();
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
                            $item->from   = $item->departurePoint->title;
                            $item->to     = $item->destinationPoint->title;
							$item->bus_name = $item->bus_name->title_bangla;
							$item->available_seat = $total_seat_count - $available_seat;
                            $item->active = 1;
                            $item->beakdownToken = $item->token;
                            $item->tripToken = $item->trip->token;
                            $item->start  = date('h:i A', strtotime($item->departure_time));
                            $item->end    = date('h:i A', strtotime($item->destination_time));
                    }

					$data = [];

					$data['AppUserToken']  = $request->header('AppUserToken');
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
    public function seatView(Request $request){

        $break_down  = TripBreakdownModel::where('token',$request->header('breakdownToken'))->
        where('trip_token',$request->header('tripToken'))->first();

        if(!empty($break_down)){

			$bus = BusListModel::with('seat')->where('token',$request->header('busToken'))
            ->where('banner_token',$break_down->banner_token)->first();
            $seat_list    = json_decode($bus->seat->title,true);

			$hold_seats = CountdownTime::whereDate('journey_date', $request->journeyDate)
            ->whereBetween('created_at', [now()->subMinute(3),now()])
            ->where('trip_token',$break_down->trip_token)->pluck('seat_details')->toArray();

			$seat_bookings =TripBookingDetailsModel::where('trip_token',$break_down->trip_token)
            ->whereDate('journey_date',$request->journeyDate)->where('status','book')
				->get()->pluck('seat_title')->toArray();
	

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

            $data = [];
            $data['seats'] = $new_arr;
            $data['breakdownToken']  = $break_down->token;
            $data['status'] = "Success";
            return response()->json($data);
        }else{
            $data = [];
            $data['seats'] = [];
            $data['breakdownToken']  = "";
            $data['status'] = "error";
            return response()->json($data);
        }
    }
    public function showSeat(Request $request){

        $break_down  = TripBreakdownModel::with('departurePoint','destinationPoint')->where('token',$request->header('breakdownToken'))->first();
      
        $booking_number = TripBookingListModel::where('token',$request->header('customerToken'))->first();
      
        $hold_check_user = CountdownTime::where('user_token',$request->header('AppUserToken'))->where('trip_token',$break_down->trip_token)->whereDate('journey_date',           			$booking_number->journey_date)->whereBetween('created_at', [now()->subMinute(3),now()])->pluck('seat_details')->toArray();

        $response = $request->json()->all();
	
         $arr = array_intersect($response['seats'],$hold_check_user);

          if(count($hold_check_user)<1){
                $data = [];
                $data['bookable_seat'] = [];
                $data['message'] = "Please select others seat";
                $data['status'] = "error";
                return response()->json($data);
          }
        Cache::put('seats', $arr);  

		$unique = BookingSeatModel::where('customer_token',$request->header('customerToken'))
        ->where('trip_token',$break_down->trip_token)->whereIn('seat_details',$arr)
        ->pluck('seat_details')->toArray();

        if(!empty($break_down)){
            $bus  = BusListModel::with('seat')->where('token',$request->header('busToken'))->first();
            foreach($arr as $seat) {
			
			  if(!in_array($seat,$unique)){

					$selected_seat                       = new BookingSeatModel;
					$selected_seat->bus_token            = $bus->token;
					$selected_seat->unique_id            = '#7'.mt_rand(100, 999);
					$selected_seat->user_token           = $request->header('AppUserToken');
					$selected_seat->customer_token       = $request->header('customerToken');
					$selected_seat->breakdown_token      = $request->header('breakdownToken');
					$selected_seat->trip_token           = $break_down->trip_token;
					$selected_seat->seat_details         = $seat;
					$selected_seat->fare                 = $break_down->fare;
					$selected_seat->online_charge        = $break_down->online_charge;
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
            $subtotal =  $fare + count($seat_count)*$break_down->online_charge;
			$data = [];
            $data['bookable_seat']          = $seat_show;
            $data['fare']                   = $fare;
            $data['sub_total']              = $subtotal;
            $data['from']                   = $break_down->departurePoint->title;
            $data['to']                     = $break_down->destinationPoint->title;
            $data['booking_number']         = $booking_number->booking_number;
            $data['journey_time']           = $break_down->departure_time;

            $data['status']                 = "Success";
            return response()->json($data);
        }
    }
   public function holdSeat(Request $request){
        $break_down  = TripBreakdownModel::where('token',$request->header('breakdownToken'))->first();
    
        if(!empty($break_down)){

            $submit_seat =  $request->seat;
				
          
            $check_user = CountdownTime::where('seat_details',$submit_seat)->where('trip_token',$request->header('tripToken'))->whereDate('journey_date', $request->journeyDate)->whereBetween('created_at', [now()->subMinute(3),now()])->first();
		
			
              if(!isset($check_user->id)){
				$checked_seat                       = new CountdownTime;
                $checked_seat->seat_details         = $submit_seat;
				$checked_seat->journey_date         = $request->journeyDate;
				$checked_seat->trip_token           = $request->header('tripToken');
                $checked_seat->user_token           = $request->header('AppUserToken');
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
                
                	$hold_tiket = CountdownTime::where('user_token',$request->header('AppUserToken'))->where('seat_details',$submit_seat)->where('trip_token',$request->header('tripToken'))->whereDate('journey_date', $request->journeyDate)->first();
                
              		$seat = $submit_seat;
					$data = [];
              
              		if(isset($hold_tiket->id)){
                      $hold_tiket->delete();				
                      $data['seat']        = $seat;
                      $data['hold']		   = 0;
                      $data['status']      = 1;
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
		$delete_seat = CountdownTime::where('user_token',$request->header('AppUserToken'))->where('trip_token',$request->header('tripToken'))->delete();
		$data                  = [];
		$data['status']        = 1;
		$data['message'] 	   = "success";
		return response()->json($data);
	} 

  
    public function showHistory(Request $request){

        $user_history = TripBookingDetailsModel::with('banner','departurePoint','destinationPoint')->where('type','App User')
			->where('booked_by',$request->header('AppUserToken'))->where('status','book')->orderBy('id','DESC')->get();
		if($user_history->isNotEmpty()){
			foreach($user_history as $key => $item){
				$item->from   = $item->departurePoint->title;
				$item->to     = $item->destinationPoint->title;
				$item->seat   = $item->seat_title;
				$item->journey_time  = date('h:i A', strtotime($item->journey_time));
				$item->bus_name = $item->banner->title_bangla;
			}
		
			  $fare = $user_history->pluck('fare')->sum();
				$online_charge = $user_history->pluck('online_charge')->sum();
				$data['status']             = "success";
				$data['total']              = $fare + $online_charge;
				$data['history']            = $user_history;
				return response()->json($data);
			}else{
				$data['status']             = "error";
				$data['total']              = 0;
				$data['history']            = [];
				return response()->json($data);
		}
	}
  public function privacyPolicy(){
     $all = PrivacyPolicy::all();
     $data = [];
     $data['status']             = "success";
     $data['policies']             = $all;
	 return response()->json($data);
  }
  public function privacyPolicyContent(Request $request){
    $content = PrivacyPolicyContent::where('privacy_policy_token',$request->policy_token)->first('privacy_policy_content');  
     $data                       = [];
     $data['status']             = "success";
     $data['contents']           = $content;
	 return response()->json($data);
  }
}
