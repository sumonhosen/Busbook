<?php

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use App\Library\SslCommerz\SslCommerzNotification;

use Illuminate\Http\Request;
use App\Models\TripBreakdownModel;
use App\Models\BookingSeatModel;
use App\Models\TripBookingListModel;
use App\Models\TripBookingDetailsModel;
use App\Models\SysActivityListModel;
use App\Models\SmsSwitch;
use App\Http\Controllers\Servers\Helpers\Helper;
use Carbon\Carbon;
use Cache;

use DB;

class SslCommerzPaymentController extends Controller
{
use Helper;
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

    public function index(Request $request)
    {

        $seat = Cache::get('seats');

        $break_down  = TripBreakdownModel::with('departurePoint','destinationPoint','counter_token')
        ->where('token',$request->header('breakdownToken'))->first();


		$bus_name = $break_down->bus_name->title_bangla;
		$bus_token = $break_down->bus_name->token;


        if(!empty($break_down)){

			$customer  = TripBookingListModel::where('token',$request->header('customerToken'))
            ->where('booked_type','App User')
            ->first();
			

        	$booking_seats = BookingSeatModel::with('book_info')->where('customer_token',$request->header('customerToken'))
                ->where('user_token',$request->header('AppUserToken'))->where('breakdown_token',$break_down->token)->whereIn('seat_details',$seat)
				->where('trip_token',$break_down->trip_token)->get();
	
				$grand_total = 0;
					  foreach($booking_seats as $fare){
						  $grand_total = $grand_total + $fare['fare'] + $fare['online_charge'];
					  }

					$post_data = array();
					$post_data['total_amount'] =$grand_total;
					$post_data['currency'] = "BDT";
					$post_data['tran_id'] = uniqid(); // tran_id must be unique

					# CUSTOMER INFORMATION
					$post_data['cus_name'] = 'Customer Name';
					$post_data['cus_email'] = 'customer@mail.com';
					$post_data['cus_add1'] = 'Customer Address';
					$post_data['cus_add2'] = "";
					$post_data['cus_city'] = "";
					$post_data['cus_state'] = "";
					$post_data['cus_postcode'] = "";
					$post_data['cus_country'] = "Bangladesh";
					$post_data['cus_phone'] = '8801XXXXXXXXX';
					$post_data['cus_fax'] = "";

					# SHIPMENT INFORMATION
					$post_data['ship_name'] = "Store Test";
					$post_data['ship_add1'] = "Dhaka";
					$post_data['ship_add2'] = "Dhaka";
					$post_data['ship_city'] = "Dhaka";
					$post_data['ship_state'] = "Dhaka";
					$post_data['ship_postcode'] = "1000";
					$post_data['ship_phone'] = "";
					$post_data['ship_country'] = "Bangladesh";

					$post_data['shipping_method'] = "NO";
					$post_data['product_name'] = "Computer";
					$post_data['product_category'] = "Goods";
					$post_data['product_profile'] = "physical-goods";

					# OPTIONAL PARAMETERS
					$post_data['value_a'] = "ref001";
					$post_data['value_b'] = "ref002";
					$post_data['value_c'] = "ref003";
					$post_data['value_d'] = "ref004";



            $banner_token                = $break_down->banner_token;
            //$trip_token                  = $request->header('tripToken');
            $trip_breakdown_token        = $break_down->token;
            $departure_point             = $break_down->departure_point;
            $related_departure_counter   = $break_down->related_departure_counter;
            $destination_point           = $break_down->destination_point;
            $related_destination_counter = $break_down->related_destination_counter;
            $counter_token               = $break_down->counter_token->counter_token;

            $journey_time                = $break_down->departure_time;

            //booking  customer

            $customer_token             = $customer->token;
            $booked_number              = $customer->booking_number;
            $booked_for                 = $customer->booked_for;
            $booked_type                = $customer->booked_type;
            $ticket_type                = $customer->ticket_type;
            $name_on_ticket             = $customer->name_on_ticket;
            $emergency_contact          = $customer->emergency_contact;
            $journey_date               = $customer->journey_date;

            $check_pluck_seat = TripBookingDetailsModel::whereDate('journey_date',$customer->journey_date)
            ->where('trip_token',$break_down->trip_token)->where('status','book')->get()->pluck('seat_title')->toArray();
			  
			foreach($booking_seats as $seat){
				if(!in_array($seat->seat_details,$check_pluck_seat)){
		
					$booking = new TripBookingDetailsModel;
                      
				            $booking->transaction_id       = $post_data['tran_id'];
							$booking->currency             = $post_data['currency'];
							$booking->banner_token          = $banner_token;
							$booking->trip_token           = $break_down->trip_token;
							$booking->trip_breakdown_token = $trip_breakdown_token;
							$booking->customer_token       = $customer_token;							
                  			$booking->counter_token        = $counter_token;
							$booking->type                 = "App User";
							$booking->booked_by            = $request->header('AppUserToken');
							$booking->customer_token       = $customer_token;
							$booking->booking_number       = $booked_number;
							$booking->departure_point      = $departure_point;
							$booking->related_departure_counter = $related_departure_counter;
							$booking->destination_point     = $destination_point;
							$booking->related_destination_counter = $related_destination_counter;
							$booking->seat_token            =   $seat->token;
							$booking->bus_token             =   $bus_token;
							$booking->seat_title            =   $seat->seat_details;
							$booking->seat_identifier       =   $seat->unique_id;
							$booking->fare                  =   $seat->fare;
							$booking->online_charge         =   $seat->online_charge;
							$booking->final_fare            =   $seat->fare+$seat->online_charge;
							$booking->booked_for            =   $booked_for;
							$booking->booked_type           =   $booked_type;
							$booking->ticket_type           =   $ticket_type;
							$booking->name_on_ticket        =   $name_on_ticket;
							$booking->emergency_contact     =   $emergency_contact;
							$booking->journey_date          =   $journey_date;
							$booking->journey_time          =   $journey_time;
							$booking->status                =   'Pending';
							$booking->existence             =   1;
							$booking->added_by              =   $request->header('AppUserToken') ?? 0;
							$booking->activity_token        =   $this->activityToken($request);
							$booking->token                 =   $this->nativeRand().'__aul'.mt_rand(100, 999).$break_down->id.$this->nativeRand();
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
	

						$sslc = new SslCommerzNotification();
					    $payment_options = $sslc->makePayment($post_data, 'hosted');
			
						$data = [];
						$data['url']          = $payment_options;
						$data['status']       = "Success";
			            $data['message']      = "Successfully found SSL Url";
						return response()->json($data);
		     }
     }
    public function success(Request $request)
    {
        $tran_id = $request->input('tran_id');
		
        $amount = $request->input('amount');
        $currency = $request->input('currency');

        $sslc = new SslCommerzNotification();

        $order_detials = DB::table('app_trip_booking_details')
            ->where('transaction_id', $tran_id)
            ->select('transaction_id', 'status', 'currency', 'final_fare')->get()->pluck('status')->toArray();

        if (in_array('Pending',$order_detials)) {
            $validation = $sslc->orderValidate($request->all(), $tran_id, $amount, $currency);

            if ($validation == true) {
                $update_product = DB::table('app_trip_booking_details')
                    ->where('transaction_id', $tran_id)->where('status','pending')
                    ->update(['status' => 'book']);
              
              		$get_sms = TripBookingDetailsModel::with('banner','trip')->where('transaction_id', $tran_id)->where('status','book')->get();
              		$totalFare = 0;
						$sms_seats = [];
						$seat_random = [];
              
						  foreach($get_sms as $sms){
							  $totalFare = $totalFare + $sms['fare']+$sms['online_charge'];
							  array_push($sms_seats, $sms['seat_title']);
							  array_push($seat_random,$sms['seat_identifier']);
						  }

						$textSMSSeats      = implode(',', $sms_seats);
                        $emergency_contact = $sms->emergency_contact;
						$bus_name          = $sms->banner->title_bangla;
              			$journey_time	   = $sms->journey_time;
              			$journey_date 	   = $sms->journey_date;
              			$trip 			   = $sms->trip->trip_number;
						$textSMSRandoms    = implode(',', $seat_random);

						$message = "Booking Successful! Your booked seats: $textSMSSeats. seat uniques: $textSMSRandoms. Bus: $bus_name. In: $journey_date. At: $journey_time. SubTotal: $totalFare. Trip Number: $trip";

    					$app_sms = SmsSwitch::where('status','1')->first();
                        $admin_contact = $app_sms->admin_app_sms;

                  		if($app_sms->app_sms==1){
    						$sendSMS = $this->sendSMS($emergency_contact, $message, 'Busbook');
                        }
                        if($app_sms->is_admin_app==1){
                            $sendSMS = $this->sendSMS($admin_contact, $message, 'Busbook');
                        }
					$data                 = [];
					$data['status']       = "Success";
			        $data['message']      = "Transaction is successfully Completed By ssl";
                	return response()->json($data);
            } else {

                $update_product = DB::table('app_trip_booking_details')
                    ->where('transaction_id', $tran_id)
                    ->update(['status' => 'Failed']);
                    $data                 = [];
					$data['status']       = "error";
			        $data['message']      = "Transaction is Failed";
                	return response()->json($data);
				}
        	}else if (in_array('Processing',$order_detials)) {
            		$data                 = [];
					$data['status']       = "Success";
			        $data['message']      = "Transaction is successfully Completed By ssl";
                	return response()->json($data);
          
        			}else{
						$data                 = [];
						$data['status']       = "error";
						$data['message']      = "Transaction is Failed";
						return response()->json($data);
					}
    		}

    public function fail(Request $request)
    {
        $tran_id = $request->input('tran_id');

        $order_detials = DB::table('app_trip_booking_details')
            ->where('transaction_id', $tran_id)
            ->select('transaction_id', 'status', 'currency', 'final_fare')->get()->pluck('status')->toArray();

        if (in_array('Pending',$order_detials)) {
            $update_product = DB::table('app_trip_booking_details')
                ->where('transaction_id', $tran_id)
                ->update(['status' => 'Failed']);
           	$data                 = [];
			$data['status']       = "error";
			$data['message']      = "Transaction is Failed";
			return response()->json($data);
        } else if ($order_detials->status == 'Processing' || $order_detials->status == 'book') {
			$data                 = [];
			$data['status']       = "error";
			$data['message']      = "Transaction is already Successful";
			return response()->json($data);
		} else {
			$data                 = [];
			$data['status']       = "error";
			$data['message']      = "Transaction is Invalid";
			return response()->json($data);
        }
    }

    public function cancel(Request $request)
    {
        $tran_id = $request->input('tran_id');

        $order_detials = DB::table('app_trip_booking_details')
            ->where('transaction_id', $tran_id)
            ->select('transaction_id', 'status', 'currency', 'final_fare')->get()->pluck('status')->toArray();

        if (in_array('Pending',$order_detials)) {
            $update_product = DB::table('orders')
                ->where('transaction_id', $tran_id)
                ->update(['status' => 'Canceled']);
				$data                 = [];
				$data['status']       = "error";
				$data['message']      = "Transaction is Cancel";
				return response()->json($data);
        } else if (in_array('book',$order_detials)) {
				$data                 = [];
				$data['status']       = "error";
				$data['message']      = "Transaction is already Successful";
        } else {
            echo "Transaction is Invalid";
        }


    }

    public function ipn(Request $request)
    {
        #Received all the payement information from the gateway
        if ($request->input('tran_id')) #Check transation id is posted or not.
        {

            $tran_id = $request->input('tran_id');

            #Check order status in order tabel against the transaction id or order id.
            $order_details = DB::table('orders')
                ->where('transaction_id', $tran_id)
                ->select('transaction_id', 'status', 'currency', 'amount')->first();

            if ($order_details->status == 'Pending') {
                $sslc = new SslCommerzNotification();
                $validation = $sslc->orderValidate($request->all(), $tran_id, $order_details->amount, $order_details->currency);
                if ($validation == TRUE) {
                    /*
                    That means IPN worked. Here you need to update order status
                    in order table as Processing or Complete.
                    Here you can also sent sms or email for successful transaction to customer
                    */
                    $update_product = DB::table('orders')
                        ->where('transaction_id', $tran_id)
                        ->update(['status' => 'Processing']);

                    echo "Transaction is successfully Completed";
                } else {
                    /*
                    That means IPN worked, but Transation validation failed.
                    Here you need to update order status as Failed in order table.
                    */
                    $update_product = DB::table('orders')
                        ->where('transaction_id', $tran_id)
                        ->update(['status' => 'Failed']);

                    echo "validation Fail";
                }

            } else if ($order_details->status == 'Processing' || $order_details->status == 'book') {

                #That means Order status already updated. No need to udate database.

                echo "Transaction is already successfully Completed";
            } else {
                #That means something wrong happened. You can redirect customer to your product page.

                echo "Invalid Transaction";
            }
        } else {
            echo "Invalid Data";
        }
    }

}
