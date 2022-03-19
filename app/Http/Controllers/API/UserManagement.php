<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Servers\User;
use App\Models\UserListModel;
use App\Models\SysUserSessionListModel;
use App\Models\AppOTPListModel;
use App\Models\CounterAndUserRelModel;
use App\Http\Controllers\Servers\Helpers\Helper;
use App\Http\Controllers\Servers\Helpers\CamelCase;

class UserManagement extends Controller
{
	use Helper;

    public function sendOTP(Request $request){
    	$proceed = true;
    	if ($proceed == true) {
    		# Check Phone Exists Or Not
    		$row = UserListModel::where('phone', $request->phone)->where('type', 'App User');
    		$numRows = $row->count();
    		if ($numRows == 1) {
            $OTP = mt_rand(100000, 999999);
    			# Create New OTP
    			$newOTP = new AppOTPListModel;
    			$newOTP->user_token = $row->first()->token;
    			$newOTP->phone = $request->phone;
    			$newOTP->otp = $OTP;
    			$newOTP->status = 'Queued';
    			$newOTP->queued_activity_token = $request->activityToken;
    			$newOTP->save();
    			# Send Message -> Call SMS Server
          $sms = "OTP for BusBook is $OTP . Please don't share your One Time Password (OTP) with anyone else. Wear Mask & Stay Safe. With Love BusBook Team.";
          $sendSMS = $this->sendSMS($request->phone, $sms, 'OTP');
          $data = [];
          $data['status'] = 'Success';
          $data['guideLine'] = 'Send This OTP And Phone Number In Next Request';
          $data['otp'] = $newOTP->otp;
          $data['phone'] = $request->phone;
          $data['sendSMS'] = $sendSMS;

          return response()->json($this->apiResponse('Success', 'LIST_LOADED', $data, null));
    		}
    		else{
    			# Create New User With Type = "App User"
          $OTP = mt_rand(100000, 999999);
    			$newRow = new UserListModel;
    			$newRow->phone = $request->phone;
    			$newRow->type = "App User";
    			$newRow->status = "Active";
    			$newRow->existence = 1;
    			$newRow->activity_token = $request->activityToken;
    			$newRow->save();
    			$token = $this->nativeRand().'__aul__AU'.mt_rand(100, 999).$newRow->id.$this->nativeRand();
    			$newRow->token = $token;
    			$newRow->save();
    			# Create New OTP
    			$newOTP = new AppOTPListModel;
    			$newOTP->user_token = $newRow->token;
    			$newOTP->phone = $request->phone;
    			$newOTP->otp = $OTP;
    			$newOTP->status = 'Queued';
    			$newOTP->queued_activity_token = $request->activityToken;
    			$newOTP->save();
    			# Send Message -> Call SMS Server
          $sms = "OTP for BusBook is $OTP . Please don't share your One Time Password (OTP) with anyone else. Wear Mask & Stay Safe. With Love BusBook Team.";
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
    	/*
			@ Check OTP is Valid or Not
			@ If Valid Create A New Session
    	*/
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
          $data['userToken'] = $user->token;
          $data['userPhone'] = $user->phone;
          $data['userName'] = $user->name;
          $data['counterToken'] = 'ONLINE_COUNTER';

          return response()->json($this->apiResponse('Success', 'LIST_LOADED', $data, null));
    		}
    		else{
    			# Send Error
          $data = [];
          $data['status'] = 'Error';
          $data['guideLine'] = 'OTP Mismatch. Allow User To Send OTP Request 3 Times Highest in 24 Hours';

          return response()->json($this->apiResponse('Error', 'LIST_LOADED', $data, null));
    		}
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

    public function specialLogin(Request $request){
      $phone = $request->phone;
      $textPassword = $request->password;
      $userType = $request->userType; // Bus Related, Counter Related
      $hashedPassword = $this->nativeHash($textPassword);

      $proceed = true;
      if ($proceed == true) {
        # Check Phone Exists Or Not
        $row = UserListModel::where('phone', $request->phone)->where('password', $hashedPassword)->where('type', $userType);

        $numRows = $row->count();

        if ($numRows == 1) {
          $user = $row->first();

          # Create A New Session
          $newSession = new SysUserSessionListModel;
          $newSession['user_token'] = $user->token;
          $newSession['source'] = $request->header('Source');
          $newSession['starting_device_token'] = $request->header('DeviceToken');
          $newSession['status'] = 'Active';
          $newSession['existence'] = 1;
          $newSession['activity_token'] = 'AppTest';

          $token = $this->nativeRand().'SessionTokenSpecial'.mt_rand(100, 999).$newSession->id.$this->nativeRand();
          $newSession->token = $token;
          $newSession->save();

          # Get Counter Token
          $counterToken = CounterAndUserRelModel::where('user_token', $user->token)->first();
          # Send Success

          $data = [];
          $data['status'] = 'Success';
          $data['sessionToken'] = $token;
          $data['userToken'] = $user->token;
          $data['userPhone'] = $user->phone;
          $data['userName'] = $user->name;
          $data['counterToken'] = $counterToken->counter_token;
          $data['counterTitle'] = $counterToken->counter->title;
          $data['counterAddress'] = $counterToken->counter->address;
          $data['counterType'] = $counterToken->counter->type;

          return response()->json($this->apiResponse('Success', 'LIST_LOADED', $data, null));
        }
        else{
          # Send Error
          $data = [];
          $data['status'] = 'Error';
          $data['guideLine'] = 'No Registered User.';

          return response()->json($this->apiResponse('Error', 'LIST_LOADED', $data, null));
        }
      }
    }
}
