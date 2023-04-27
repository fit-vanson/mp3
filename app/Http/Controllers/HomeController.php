<?php

namespace App\Http\Controllers;

use App\ListIP;
use App\Musics;

use App\Sites;
use App\Tags;
use Carbon\Carbon;
use Torann\GeoIP\Facades\GeoIP;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('show','addCountry','clear_IP');
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
        $musics = Musics::all();



       return view('dashboard.index')->with(compact('sites','tags','musics'));
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
/**
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
**/

    public function load_data(){
        $a = ListIP::selectRaw('sum(count) as count, DATE(updated_at) as date')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->toArray();

        $b = ListIP::selectRaw('count(ip_address) as ip_address, DATE(updated_at) as date')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->toArray();

        $k = array_column($a, 'date');
        $v = array_column($a, 'count');
        $x = array_column($b, 'ip_address');
        $dataArray = [];
        $dataArray['datasets'][] = [
            'label' => 'Lượt truy cập',
            'yAxisID'=> 'A',
            'fill' => false,
            'borderColor' => "#02a499",
            'backgroundColor' => "#02a499",
            'data' =>$v,
        ];
        $dataArray['datasets'][] = [
            'label' => 'IP',
            'yAxisID'=> 'B',
            'fill' => false,
            'borderColor' => "#3c4ccf",
            'backgroundColor' => "#3c4ccf",
            'data' =>$x,
        ];
        $dataArray['labels'] = $k;
        return response()->json( $dataArray);
    }

    public function load_mostApp()
    {
        $now = Carbon::now();
        $yesterday = $now->copy()->subDay(1);
        $last7days = $now->copy()->subDay(7);
        $startOfMonth = $now->copy()->startOfMonth();

        $data = ListIP::selectRaw('id_site, SUM(CASE WHEN updated_at >= ? THEN count ELSE 0 END) as count_today,
        SUM(CASE WHEN updated_at >= ? AND updated_at < ? THEN count ELSE 0 END) as count_lastday,
        SUM(CASE WHEN updated_at >= ? AND updated_at <= ? THEN count ELSE 0 END) as count_7day,
        SUM(CASE WHEN updated_at >= ? AND updated_at <= ? THEN count ELSE 0 END) as count_month',
            [
                $now->format('Y-m-d'),
                $yesterday->format('Y-m-d'),
                $now->format('Y-m-d'),
                $last7days->format('Y-m-d'),
                $now->format('Y-m-d'),
                $startOfMonth->format('Y-m-d'),
                $now->format('Y-m-d')])
            ->groupBy('id_site')
            ->orderByDesc('count_today')
            ->take(5)
            ->with('sites')
            ->get()
            ->map(function($row) {
                return [
                    "logo" => '<div><img src="'.asset('storage/sites').'/'.$row->sites->site_image.'" width="70" class="rounded-circle mr-3"><a  href="'.route('sites.view',['id'=>$row->sites->id]).'"  target="_blank"> '.$row->sites->site_name.'</a></div>',
                    'count_today' => number_format($row->count_today),
                    'count_lastday' => number_format($row->count_lastday),
                    'count_7day' => number_format($row->count_7day),
                    'count_month' => number_format($row->count_month),
                ];
            });
        return response()->json([
            'draw' => 1,
            'aaData' => $data,
        ]);
    }

    public function load_mostCountry()
    {
        $mostCountrys_today = ListIP::selectRaw('SUM(count) AS count_today, country')
            ->groupBy('country')
            ->orderByDesc('count_today')
            ->limit(10)
            ->get(['count_today', 'country']);

        $data_arr = $mostCountrys_today->map(function ($country) {
            return [
                "count_today" => number_format($country->count_today),
                "country" => $country->country
            ];
        })->toArray();

        return response()->json([
            "draw" => 1,
            "aaData" => $data_arr,
        ]);
    }


    public function clear_IP(){
        $data = ListIP::where('updated_at','<', Carbon::now()->subDays(60))->count();
        if($data> 0){
            ListIP::where('updated_at','<', Carbon::now()->subDays(60))->delete();
            return response()->json(['success'=>'Xóa thành công.']);
        }else{
            return response()->json(['errors'=>'Không có dữ liệu.']);
        }
    }

    public function addCountry(){
        $limit = $_GET['limit'] ?? 50;
        $data = ListIP::where('country',null)->latest('updated_at')->distinct('ip_address')
            ->select('ip_address','updated_at')->limit($limit)->get();
        if ($data){
            $insert = [];
            foreach ($data as $item){
                $ip_address = $item->ip_address;
                $location = GeoIP::getLocation($ip_address);
                $country = $location['country'];
                $insert[]=[
                    'ip_address' => $item->ip_address,
                    'country' => $country,
                ];
            }

            $ipInstance = new ListIP;
            $index = 'ip_address';
            $result = batch()->update($ipInstance, $insert, $index);
            echo '<pre>';
            print_r($insert);
            echo '</pre>';
            echo '<META http-equiv="refresh" content="1;URL=' . url("add-country") . '?limit='.$limit.'">';
        }

    }



}
