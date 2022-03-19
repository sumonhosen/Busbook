<?php

namespace App\Http\Controllers\Web;

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
use App\Models\TripBookingDetailsModel;
use App\Models\BookingSeatModel;

use App\Http\Controllers\Servers\Helpers\Helper;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;

class BetaController extends Controller
{
    use Helper;

    public function loginView(){
        return view('beta.pages.login');
    }

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

    public function dashboardView(){

        return view('beta.pages.dashboardView');
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
        $newRow->added_by = $request->header('UserToken') ?? 0;
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
        $newTripBookingRow->status = 'Active';
        $newTripBookingRow->existence = 1;
        $newTripBookingRow->added_by = $request->header('UserToken') ?? 0;
        $newTripBookingRow->activity_token = $this->activityToken($request);
        $newTripBookingRow->save();

        $bookingToken = $this->nativeRand().'__aul'.mt_rand(100, 999).$newTripBookingRow->id.$this->nativeRand();
        $newTripBookingRow->token = $bookingToken;
        $newTripBookingRow->save();

        $data = [];
        $data['status'] = "Success";

        $data['TripBookingToken'] = $newTripBookingRow;

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
        $newTripBookingRow->status = 'Active';
        $newTripBookingRow->existence = 1;
        $newTripBookingRow->added_by = $request->header('UserToken') ?? 0;
        $newTripBookingRow->activity_token = $this->activityToken($request);
        $newTripBookingRow->save();

        $bookingToken = $this->nativeRand().'__aul'.mt_rand(100, 999).$newTripBookingRow->id.$this->nativeRand();
        $newTripBookingRow->token = $bookingToken;
        $newTripBookingRow->save();

        $data = [];
        $data['status'] = "Success";
        $data['TripBookingToken'] = $newTripBookingRow;
        return response()->json($data);
        }
    }

    public function ajaxTripsList(Request $request){

        $list = TripBreakdownModel::with('bus','departurePoint','destinationPoint')->where('departure_point', $request->from)->where('destination_point', $request->to)->get();

        foreach($list as $key => $item){
        $counterTripRel = CounterAndTripRelModel::where('trip_token', $item->trip_token)->where('status', 'Active')->where('existence', 1)->first();
            $item->active = 1;
            $item->start  = date('h:i A', strtotime($item->departure_time));
            $item->end    = date('h:i A', strtotime($item->destination_time));
            }
        $data = [];
        $data['trip_booking_token'] = $request->trip_booking_token;
        $data['status'] = "Success";
        $data['trips']  = $list;

        return response()->json($data);

    }
    public function seatView(Request $request){
        $seat = BusListModel::with('total_seat')->where('token',$request->bus_token)->first();
        $booked_seat = TripBookingDetailsModel::where('bus_token',$seat->token)->get();


        $seats = json_decode($seat->total_seat->title,true);
        $data = [];
        $data['seats']              = $seats;
        $data['trip_booking_token'] = $request->trip_booking_token;
        $data['trip_break_token']   = $request->trip_break_token;
        $data['start']              = $request->start;
        $data['end']                = $request->end;

        $data['status'] = "Success";
        return response()->json($data);
    }
    public function showSeat(Request $request){

        $booking_info = TripBookingListModel::where('token',$request->trip_booking_token)->first();

        $trip_break_down_data = TripBreakdownModel::with('bus')->where('token',$request->trip_break_token)->first();

        $booking_seats= $request->seats;

        foreach($booking_seats as $seat) {
            $booking_seat                       = new BookingSeatModel;
            $booking_seat->bus_token            = $trip_break_down_data->bus->token;
            $booking_seat->unique_id            = '7'.mt_rand(100, 999);
            $booking_seat->booking_token        = $request->trip_booking_token;
            $booking_seat->trip_breakdown_token = $request->trip_break_token;
            $booking_seat->seat_details         = $seat;
            $booking_seat->fare                 = $trip_break_down_data->fare;
            $booking_seat->online_charge        = $trip_break_down_data->online_charge;
            $booking_seat->save();
            $bookingToken = $this->nativeRand().'__aul'.mt_rand(100, 999).$booking_seat->id.$this->nativeRand();
            $booking_seat->token = $bookingToken;
            $booking_seat->save();
        }
        $booking_seats = BookingSeatModel::where('bus_token',$trip_break_down_data->bus->token)->where('booking_token',$request->trip_booking_token)->get();
        $seats = $booking_seats->pluck('seat_details');

        $data = [];
        $data['total_fare']    = $booking_seats->sum('fare');
        $data['total_charge']  = $booking_seats->sum('online_charge');
        $data['sub_total']     =  $data['total_fare']+$data['total_charge'];

        $data['departure_point']        = $trip_break_down_data->departurePoint->title;
        $data['destination_point']      = $trip_break_down_data->destinationPoint->title;
        $data['trip_break_token']       = $trip_break_down_data['token'];
        $data['start_time']             = $request->start;
        //booking info
        $data['trip_booking_token']     = $booking_info->token;
        $data['booking_number']         = $booking_info->booking_number;

        $data['seats']                  = $seats;
        $data['booking_seats']          = $booking_seats;

        $data['status']                 = "Success";


        return response()->json($data);
    }
    public function bookingSeat(Request $request){
        $booking = TripBookingListModel::where('token',$request->trip_booking_token)->first();

        $trip_break_down = TripBreakdownModel::with('bus')->where('token',$request->trip_break_token)->first();

        $booking_seats = BookingSeatModel::where('bus_token',$trip_break_down->bus->token)->where('booking_token',$request->trip_booking_token)->get();

        $seat_token                  = $booking_seats->pluck('token');
        $seat_title                  = $booking_seats->pluck('seat_details');
        $seat_identifier             = $booking_seats->pluck('unique_id');
        $fare                        = $booking_seats->sum('fare');
        $total_charge                = $booking_seats->sum('online_charge');
        $final_fare                  = $fare+$total_charge;


        $bus_token                   = $trip_break_down->bus->token;
        $banner_token                = $trip_break_down->banner_token;
        $trip_token                  = $trip_break_down->trip_token;
        $trip_breakdown_token        = $trip_break_down->token;
        $departure_point             = $trip_break_down->departure_point;
        $related_departure_counter   = $trip_break_down->related_departure_counter;
        $destination_point           = $trip_break_down->destination_point;
        $related_destination_counter = $trip_break_down->related_destination_counter;

        $journey_time                = $trip_break_down->departure_time;

        //booking
        $booking_token              = $booking->token;
        $booking_number             = $booking->booking_number;
        $booked_for                 = $booking->booked_for;
        $name_on_ticket             = $booking->name_on_ticket;
        $emergency_contact          = $booking->emergency_contact;
        $journey_date               = $booking->journey_date;


        $booking                       = new TripBookingDetailsModel;
        $booking->banner_token         = $banner_token;
        $booking->trip_token           = $trip_token;
        $booking->trip_breakdown_token = $trip_breakdown_token;
        $booking->booking_token        = $booking_token;
        $booking->booking_number       = $booking_number;
        $booking->departure_point      = $departure_point;
        $booking->related_departure_counter = $related_departure_counter;
        $booking->destination_point    = $destination_point;
        $booking->related_destination_counter = $related_destination_counter;
        $booking->bus_token             =   $bus_token;
        $booking->seat_token            =   $seat_token;
        $booking->seat_title            =   $seat_title;
        $booking->seat_identifier       =   $seat_identifier;
        $booking->fare                  =   $fare;
        $booking->online_charge         =   $total_charge;
        $booking->final_fare            =   $final_fare;
        $booking->booked_for            =   $booked_for;
        $booking->name_on_ticket        =   $name_on_ticket;
        $booking->emergency_contact     =   $emergency_contact;
        $booking->journey_date          =   $journey_date;
        $booking->journey_time          =   $journey_time;
        $booking->status                =   'Active';
        $booking->existence             =   1;
        $booking->added_by              =   $request->header('UserToken') ?? 0;
        $booking->activity_token        =   $this->activityToken($request);
        $booking->save();

        $bookingToken = $this->nativeRand().'__aul'.mt_rand(100, 999).$booking->id.$this->nativeRand();
        $booking->token = $bookingToken;
        $booking->save();

        $data = [];
        $data['status'] = "Success";
        $data['booking'] = $booking;
        return response()->json($data);

    }
}
