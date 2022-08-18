<?php

namespace App\Http\Controllers;

use App\Ringtones;
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
        $sites = Sites::all();
        $tags = Tags::count();
        $wallpapers = Wallpapers::all();
        $ringtones = Ringtones::all();

//        dd($wallpapers->where('wallpaper_status',1));

       return view('dashboard.index')->with(compact('sites','tags','wallpapers','ringtones'));
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

    public function load_data(){
        $sites = Sites::select('site_cron')->get();
        $dataArray = [];
        $sumArray = array();
        $color =  str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
        foreach ($sites as $site){
            $data = json_decode($site->site_cron,true);
            if(isset($data)){
                foreach ($data as $key=>$value) {
                    (!isset($sumArray[$key])) ?
                        $sumArray[$key]=$value :
                        $sumArray[$key]+=$value;
                }
            }
        }

        $k = array_keys($sumArray);
        $v = array_values($sumArray);
        sort($k);
        $dataArray['datasets'][] = [
//            'label' => false,
            'fill' => false,
            'borderColor' => "#$color",
            'backgroundColor' => "#$color",
            'data' =>$v,
        ];
        $dataArray['labels'] = $k;
        return response()->json( $dataArray);
    }

    public function cloudflare($zoneID){
        $date1 = Carbon::today()->subDays(1);
        $time0H     = $date1->toIso8601String();
        $time6H     = $date1->addHours(6)->toIso8601String();
        $time12H    = $date1->addHours(6)->toIso8601String();
        $time18H    = $date1->addHours(6)->toIso8601String();
        $time24H    = $date1->addHours(6)->toIso8601String();
        $date = Carbon::today()->subDays(1)->format('Y-m-d');
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
        $num = 0;
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
                echo $site->site_web.'<br>';
                $num ++;
            }
        }

        return $date.':'.$num;
    }



}
