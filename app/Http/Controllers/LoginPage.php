<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\UserAccount;
use Session;
use RealRashid\SweetAlert\Facades\Alert;

class LoginPage extends Controller
{
    public function home(){
        return view('home');
    }

    public function postLogin(Request $request){
        //dd($request->username ." | ".$request->password);
        $this->validate($request,[
            'username'  => 'required',
            'password'  => 'required'
        ]);
        
        if(Auth::attempt(['username'=> $request->username, 'password'=> $request->password], $request->remember)){
            $userAccount = UserAccount::where('username', $request->username)->first();
            //dd(Auth::id());
            /* Check account if active */
            if(!$userAccount->account_is_active()){
                Alert::error('WARNING', "Your Account is Disabled.");
                return back()->withErrors(""); 
                //return redirect()->route('home')->withErrors('Your Account is Disabled.');
            }
            
            //dd($userAccount->ua_id);
            /* Check user-type */
            if($userAccount->user_type == "Instructor"){
                return redirect()->route('instructordashboard');
            }else if($userAccount->user_type == "Dean"){
                return redirect()->route('deandashboard');
            }else if($userAccount->user_type == "Student"){
                return redirect()->route('studentdashboard');
            }else if($userAccount->user_type == "Admin"){
                return redirect()->route('sadashboard');
            }
            //dd("User Type = ".$userAccount->user_type);
            //return redirect()->route('home');
            //$this->checkUserType($userAccount->user_type);
            return abort(404);
            
        }
        Alert::error('WARNING', "Incorrect Credentials.");
        return back()->withErrors(""); 
        //return redirect()->route('home')->with('error','Incorrect Credentials.');
    }

    

    public function logout(Request $request){
        Auth::logout();
        return redirect()->route('home')->with('toast_success', 'Logged-out successfully.');
    }
}
