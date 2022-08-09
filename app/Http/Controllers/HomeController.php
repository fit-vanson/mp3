<?php

namespace App\Http\Controllers;

use App\Sites;
use App\Tags;
use App\Wallpapers;
use Carbon\Carbon;
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
    public function index()
    {
        $sites = Sites::count();
        $tags = Tags::count();
        $wallpapers = Wallpapers::count();
//        dd($site);

       return view('dashboard.index')->with(compact('sites','tags','wallpapers'));
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

    public function cloudflare($zoneID){
        $date1 = Carbon::today();
        $time0H     = $date1->toIso8601String();
        $time6H     = $date1->addHours(6)->toIso8601String();
        $time12H    = $date1->addHours(6)->toIso8601String();
        $time18H    = $date1->addHours(6)->toIso8601String();
        $time24H    = $date1->addHours(6)->toIso8601String();
        $date = Carbon::today()->format('Y-m-d');

//        dd($date,$time0H,$time6H,$time12H,$time18H,$time24H);


        $time = [
            [$time0H,$time6H],
            [$time6H,$time12H],
            [$time12H,$time18H],
            [$time18H,$time24H]
        ];

        $api_key = 'f4fb1dd91d4a7abce9460fe85f0cec82a6a69';
        $email = 'ngocphandang@yahoo.com.vn';
        $key        = new \Cloudflare\API\Auth\APIKey($email, $api_key);
        $adapter    = new \Cloudflare\API\Adapter\Guzzle($key);
        $analytics  = new \Cloudflare\API\Endpoints\DNSAnalytics($adapter);
//        $filters = 'responseCode==NOERROR';
        $filters = '';

        $result = [];

        foreach ($time as $value){
            $data = $analytics->getReportTable(
                $zoneID,
//                '741323803456060081ecf0064be0ab59',
                ['queryName'],
                ['queryCount'],
                ['-queryCount'],
                $filters,
                $value[0],
                $value[1],
                200
            );

            $resultArray = [];
            if ($data->rows >0){
                foreach ($data->data as $item){
                    $resultArray[] = [
                        'dimensions' => $item->dimensions[0],
                        'metrics' => $item->metrics[0],
                    ];

                }
            }

            foreach($resultArray as  $number) {

                (!isset($result[$number['dimensions']])) ?
                    $result[$number['dimensions']] = $number['metrics'] :
                    $result[$number['dimensions']] += $number['metrics'];
            }
        }
        foreach ($result as $web=>$res){
            $site =  Sites::where('site_web',$web)->first();
            if ($site){
                $site_cron = json_decode($site->site_cron,true);

                if (isset($site_cron)){
                    $site_cron[$date]  = $res;
                }else{
                    $site_cron = [$date=>$res];
                }
                $site_cron = json_encode($site_cron);

                $site->site_cron = $site_cron;
                $site->save();
            }
        }

        return 1;
    }



}
