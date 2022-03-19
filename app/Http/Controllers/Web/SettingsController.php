<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SmsSwitch;

class SettingsController extends Controller
{
    public function smsSetting(){
    	$sms = SmsSwitch::where('status','1')->first();
    	return view('pages.sms_setting.sms_setting',compact('sms'));
    }    
    public function counter_sms(Request $request){
    	$sms_setting 			   = SmsSwitch::where('id',$request->sms_id)->first();
    	$sms_setting->counter_sms  = $request->counter_sms;
        $sms_setting->user_sms     = 1;
    	$sms_setting->save();
    	return "Sms Setting Successfull";
    }
    public function app_sms(Request $request){
        $sms_setting               = SmsSwitch::where('id',$request->sms_id)->first();
        $sms_setting->app_sms      = $request->app_sms;
        $sms_setting->user_sms     = 1;
        $sms_setting->save();
        return "Sms Setting Successfull";
    }
    public function is_admin_sms(Request $request){
        $sms_setting  = SmsSwitch::where('id',$request->sms_id)->first();  
        $sms_setting->admin_counter_sms  = $request->admin_counter_sms;
        $sms_setting->admin_app_sms      = $request->admin_app_sms;
        $sms_setting->save();
        return back()->with('success','Your Phone sms Updated successfull');
    }
    public function is_admin_counter(Request $request){
        $sms_setting               = SmsSwitch::where('id',$request->sms_id)->first();
        $sms_setting->is_admin_counter  = $request->admin_counter_sms;
        $sms_setting->user_sms     = 1;
        $sms_setting->save();
        return "Sms Setting Successfull";
    }
    public function is_admin_app(Request $request){
        $sms_setting               = SmsSwitch::where('id',$request->sms_id)->first();
        $sms_setting->is_admin_app = $request->admin_app_sms;
        $sms_setting->save();
        return "Sms Setting Successfull";
    }
}
