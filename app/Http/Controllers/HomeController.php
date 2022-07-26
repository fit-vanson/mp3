<?php

namespace App\Http\Controllers;

use App\Sites;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('show');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request, $id='index')
    {
        if (Session::get('lang')) {
            App::setLocale(Session::get('lang'));
        }
        return view($id);
    }

    public function show(){

        $domain=$_SERVER['SERVER_NAME'];
        $site = Sites::where('site_web',$domain)->first();
        if($site){
            return view('home.index')->with(compact('site'));
        }
        else{
            return 1;
        }



    }

    public function policy(){
        $domain=$_SERVER['SERVER_NAME'];
        $site = Sites::where('site_web',$domain)->first();
        if($site){
            return view('home.policy')->with(compact('site'));
        }
        else{
            return 1;
        }
    }
}
