<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Web\Controller;
use Illuminate\Http\Request;
use App\Models\SysRoleGroupListModel;
use App\Models\SysRoleListModel;
use App\Models\PrivacyPolicy;
use App\Models\PrivacyPolicyContent;
use App\Http\Controllers\Servers\Helpers\Helper;

class RoleController extends Controller
{
  use Helper;

  public function newRoleView (){
    $list = SysRoleGroupListModel::orderBy('id', 'desc')->get();
		return view('pages.role.new', compact('list'));
	}

  public function newRole (Request $request){
    $newRow = new SysRoleGroupListModel;
    $newRow->title = $request->title;
    $newRow->remark = $request->remark;
    $newRow->save();
    
    
    $token = $this->nativeRand().'__srgl'.mt_rand(100, 999).$newRow->id.$this->nativeRand();
    $newRow->token = $token;
    $newRow->save();

    return back()->with('status', 'New Role Group Added');

  }
  
  public function singleRoleView (Request $request, $roleToken){
    $list = SysRoleListModel::where('role_group_token', $roleToken)->get();
		return view('pages.role.singleView', compact('list', 'roleToken'));
	}

  public function singleRoleAction(Request $request){

    $validated = $request->validate([
      'token' => 'required|unique:sys_role_list',
    ]);

    $newRow = new SysRoleListModel;
    $newRow->token = $request->token;
    $newRow->role_group_token = $request->role_group_token;
    $newRow->title = $request->title;
    $newRow->details = $request->details;
    $newRow->crud_type = $request->crud_type;
    $newRow->entity = $request->entity;
    $newRow->permission = $request->permission;
    $newRow->remark = $request->remark;
    $newRow->save();

    return back()->with('status', 'New Role List Added');

  }
	public function newPrivacyPolicyView(){
     
      return view('pages.privacy.privacy_policy');
    }

	public function newPrivacyPolicyContentView(){
       $policies = PrivacyPolicy::all();
      return view('pages.privacy.privacy_policy_content',compact('policies'));
    }
  	public function newPrivacyPolicyAction(Request $request){
      $privacy_policy = new PrivacyPolicy;
      $privacy_policy->privacy_policy = $request->privacy_policy;
      $privacy_policy->save();
      $token = $this->nativeRand().'__srgl'.mt_rand(100, 999).$privacy_policy->id.$this->nativeRand();
      $privacy_policy->token = $token;
      $privacy_policy->save();
      return back();
    }
    public function newPrivacyPolicyContentAction(Request $request){
         $content = new PrivacyPolicyContent;
         $content->privacy_policy_token = $request->privacy_policy_token;
         $content->privacy_policy_content = $request->privacy_policy_content;
         $content->save();
         $token = $this->nativeRand().'__srgl'.mt_rand(100, 999).$content->id.$this->nativeRand();
         $content->token = $token;
         $content->save();
      	 return back();
      
    }
  




}
