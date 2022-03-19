<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Servers\User;
use App\Models\UserListModel;
use App\Http\Controllers\Servers\Helpers\Helper;

class UserController extends Controller
{
  use Helper;

  public function newUserView(){
    $list = UserListModel::where('existence', 1)->orderBy('id', 'desc')->get();
    return view('pages.user.new', compact('list'));
  }
  
  public function newUser(Request $request){   

    $info = [];
    $info['name'] = $request->name;
    $info['phone'] = $request->phone;
    $info['email'] = $request->email;
    $info['password'] = $request->password;
    $info['type'] = $request->type;
    $info['address'] = $request->address;

    $info = array_merge($info, $this->commonColumns($request->all()));
    (new User)->__newUser($info);

    return redirect()->back()->with('status', 'New User Added');
  }

  public function updateUser(Request $request, $userToken){ 
    
    $info = [];
    $info['userToken'] = $userToken;
    $info['name'] = $request->name;
    $info['phone'] = $request->phone;
    $info['email'] = $request->email;
    $info['address'] = $request->address;
    $info['remark'] = "UPDATE";
    
    $info = array_merge($info, $this->commonColumns($request->all()));
    (new User)->__updateUser($info);

    return redirect()->back()->with('status', 'User Has Been Updated');
  }

  public function updatePassword(Request $request, $userToken){
    if ($request->isMethod('get')) {
      return view('pages.user.password');
    } 
    else{
      $info = [];
      return (new User)->__updatePassword($info);
    }
    
  }

  public function deleteUser(Request $request, $userToken){
    $info = [];
    $info['userToken'] = $userToken;
    $info['existence'] = 0;
    $info['addedBy'] = 'Saikat';
    $info['activityToken'] = 'ajjkdkdddkdinrhr';
    $info['remark'] = "DELETE";

    (new User)->__deleteUser($info);
    return back()->with('status', 'User Has Been Deleted');
  }

  public function userList(){
    $List = UserListModel::where('existence', 1)->get();

    return view('pages.user.list', compact('List'));
  }

  public function userView($userToken){
    $Item = UserListModel::where('token', $userToken)->first();

    return view('pages.user.update', compact('Item'));
  }


}