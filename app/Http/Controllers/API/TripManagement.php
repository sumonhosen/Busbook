<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserListModel;
use App\Models\TripBookingListModel;
use App\Models\TripBreakdownModel;
use App\Models\PlaceListModel;
use App\Http\Controllers\Servers\System;
use App\Http\Controllers\Servers\Helpers\Helper;
use App\Http\Controllers\Servers\Helpers\CamelCase;

class TripManagement extends Controller
{
	use Helper;
    public function searchTrips(Request $request){
        $proceed = true;
        if ($proceed == true) {
            # CHECK USER
            /*$user = UserListModel::where('phone', $request->phone)->where('type', 'App User');
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

            }*/

            # NOW INSERT A NEW BOOKING
            /*$newBooking = new TripBookingListModel;
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
            $token = $this->nativeRand().'__tblUA'.mt_rand(100, 999).$newBooking->id.$this->nativeRand();
            $newBooking->token = $token;
            $newBooking->save();*/

            # SELECT BREAKDOWNS BASED ON FROM TO
            $breakdowns = TripBreakdownModel::where('departure_point', $request->from)->where('destination_point', $request->to)->orderBy('departure_time', 'asc')->get();
            foreach ($breakdowns as $key => $breakdown) {
                $breakdown->departureTime = date("h:i A", strtotime($breakdown->departure_time));
                $breakdown->departureTime24H = $breakdown->departure_time;
                $breakdown->destinationTime = date("h:i A", strtotime($breakdown->destination_time));
                $breakdown->trip = $breakdown->trip;
                $breakdown->banner = $breakdown->banner;
                $breakdown->busInfo = "Online Charge";
                $breakdown->availableSeats = 40;
            }

            $ticketFor = [];
            $ticketFor['name'] = $request->name;
            $ticketFor['phone'] = $request->phone;

            $searchParams = [];
            $searchParams['from'] = PlaceListModel::where('token', $request->from)->first()->title;
            $searchParams['to'] = PlaceListModel::where('token', $request->to)->first()->title;
            $searchParams['journeyDate'] = date('d/m/Y', strtotime($request->journeyDate));

            $resend                 = [];
            $resend['name']         = $request->name;
            $resend['phone']        = $request->phone;
            $resend['from']         = $request->from;
            $resend['to']           = $request->to;
            $resend['journeyDate']  = $request->journeyDate;

            $data = [];
            $data['ticketFor'] = CamelCase::camelCaseObject($ticketFor);
            $data['resend'] = CamelCase::camelCaseObject($resend);
            $data['searchParams'] = CamelCase::camelCaseObject($searchParams);
            $data['searchResult'] = CamelCase::camelCaseObject($breakdowns);
            return response()->json($this->apiResponse('Success', 'LIST_LOADED', $data, null));
        }
    }
}
