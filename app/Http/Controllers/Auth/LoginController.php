<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    protected $username;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');

    }

    public function login(Request $request){
        $input = $request->all();
        $check = filter_var($input['email'], FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
        $remember = $request->has('remember')? true:false;
        if(auth()->attempt(array($check=>$input['email'], 'password'=>$input['password']),$remember)){
            return redirect()->route('home.index');
        }else{
            return redirect()->route('login')->with('error', 'Email and password are wrong');
        }
    }


}
