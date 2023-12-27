<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\support\facades\Auth;

class AdminController extends Controller
{
    public function loginHandler(Request $request){
        $fieldType = filter_var($request->login_id, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if ($fieldType == 'email') {
            $request->validate([
                'login_id'=>'required|email|exists:admins,admins,email',
                'password'=>'required|min:5|max:45'
            ],[
                'login_id.required'=>'Email or Usernameis required',
                'login_id.email'=>'Invalid email address',
                'login_id.exists'=>'email is not exists in system',
                'password.required'=>'password is required'

            ]);
        }else {
            $request->validate([
                'login_id'=>'required|exists:admins,username',
                'password'=>'required|min:5|max:45'

            ],[
                'login_id.required'=>'Email or Username is required',
                'login_id.exists'=>'username is not exists is system',
                'password.required'=>'password is required'
                
            ]);
        }

        $creds = array(
            $fieldType => $request->login_id,
            'password'=>$request->password
        );

        if(Auth::guard('admin')->attempt($creds)){
            return redirect()->route('admin.home');
        }else {
            session()->flash('fail','Incorrect credentials');
            return redirect()->route('admin.login');
        }
    } 

    public function logoutHandler(Request $request){
        auth::guard('admin')->logout();
        session()->flash('fail','rak tloggeti out!');
        return redirect()->route('admin.login');
    }
}



