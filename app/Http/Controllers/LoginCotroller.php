<?php

namespace App\Http\Controllers;
use app\Models\User;
use Illuminate\Http\Request;
// use Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Auth as FacadesAuth;

class LoginCotroller extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request){
        // dd($request->all());
        // validate data
        $request->validate([
            'email' => 'required',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required',
            'terms' => 'required'
        ]);

        // login code
        
        if(Auth::attempt($request->only('email','password'))){
            if(Auth::user()->role_id == 1){
            return redirect()->route('userslist');
        }
        elseif(Auth::user()->role_id == 3){
            return redirect()->route('listassignedtask');
        }        
        }
        // dd(Auth::attempt($request->only('email','password'))); 

        return redirect('/')->with('Error' ,'Login details are not valid');

    }

    public function logout(Request $request): RedirectResponse{

        Auth::logout();
 
        $request->session()->invalidate();
     
        $request->session()->regenerateToken();
    //  dd('fafds');
        return redirect('login');
     
    }
}
