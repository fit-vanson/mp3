<?php

namespace App\Http\Controllers;

use App\GoogleAds;
use App\Sites;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Torann\GeoIP\Facades\GeoIP;

class GoogleAdsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except('show_direct');
    }

    public function index()
    {
        $header = [
            'title' => 'Google Ads Service',
            'button' => [
                'Create'            => ['id'=>'createGoogle_Ads','style'=>'primary'],
//                'Play List'         => ['id'=>'videoList','style'=>'primary'],
            ]

        ];
        return view('google_ads.index')->with(compact('header'));
    }

    public function getIndex(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // total number of rows per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        // Total records
        $totalRecords = GoogleAds::select('count(*) as allcount') ->count();
        $totalRecordswithFilter = GoogleAds::select('count(*) as allcount')
            ->where(function ($query) use ($searchValue) {
                $query
                    ->where('name', 'like', '%' . $searchValue . '%')
                    ->orwhere('value', 'like', '%' .$searchValue . '%');
            })
            ->count();

        // Get records, also we have included search filter as well
        $records = GoogleAds::orderBy($columnName, $columnSortOrder)
            ->where(function ($query) use ($searchValue) {
                $query
                    ->where('name', 'like', '%' . $searchValue . '%')
                    ->orwhere('value', 'like', '%' .$searchValue . '%');
            })
            ->skip($start)
            ->take($rowperpage)
            ->get();
        $data_arr = array();
        foreach ($records as $record) {
            $btn = ' <a href="javascript:void(0)" data-id="'.$record->id.'" class="btn btn-warning editGoogle_ads"><i class="ti-pencil-alt"></i></a>';
            $btn .= ' <a href="javascript:void(0)" data-id="'.$record->id.'" class="btn btn-danger deleteGoogle_ads"><i class="ti-trash"></i></a>';

            $sites = json_decode($record->site_redirect,true);
            $site_redirect = '';
            if(isset($sites)){
                foreach ($sites as $site) {
                    $site_redirect .= ' <span class="badge badge-dark" style="font-size: 100%">' . $site. '</span> ';
                }
            }
            $data_arr[] = array(
                "id" => $record->id,
                "site_redirect" => $site_redirect,
                "is_Devices" => $record->is_Devices == 0 ? '<span class="badge badge-success">Devices</span>' : '<span class="badge badge-warning">Country</span>',
                "name" => '<a href="#" data-pk="'.$record->id.'" class="editable" data-url="">'.$record->name.'</a>',
//                "value" => '<a href="#" data-pk="'.$record->id.'" class="editable" data-url="">'.$record->value.'</a>',
                "action" => $btn,
            );

        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr,
        );

        echo json_encode($response);
    }

    public function create(){
        $sites = Sites::inRandomOrder()->take(3)->get()->pluck('site_web')->toArray();
        $data = new GoogleAds();
        $data->name = Str::random(15);
        $data->site_redirect = json_encode($sites);
        $data->save();
        return response()->json([
            'success'=>'Thêm mới thành công',
        ]);

    }

    public function edit($id)
    {
        $data = GoogleAds::find($id);
        return response()->json($data);
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $data= GoogleAds::find($id);
        $data->name = trim($request->value);
//        dd($data);
        $data->save();
        return response()->json(['success'=>'Cập nhật thành công']);
    }

    public function updatePost(Request $request)
    {
        $GoogleAds_country= array_filter($request->GoogleAds_country, function ($item) {
            return $item['country'] !== null && $item['url'] !== null;
        });

        $id = $request->GoogleAds_id;
        $data= GoogleAds::find($id);
        $data->name = trim($request->GoogleAds_name);
        $data->url_block = trim($request->GoogleAds_url_block);
        $data->html = $request->GoogleAds_html;
        $data->is_Devices = $request->GoogleAds_is_Devices;
        $data->site_redirect =  json_encode(array_map('trim',array_values(array_filter(preg_split("/\r\n|\n|\r|,|\|/", $request->GoogleAds_sites)))));
        $data->country_value =  json_encode(array_values($GoogleAds_country));
        $data->devices_value =  json_encode($request->GoogleAds_Devices);
        $data->save();
        return response()->json(['success'=>'Cập nhật thành công']);
    }


    public function delete($id)
    {
        $GoogleAds = GoogleAds::find($id);
        $GoogleAds->delete();
        return response()->json(['success'=>'Xoá thành công']);
    }

    public function show_direct(Request $request)
    {
        Cache::flush();
        Cookie::queue(Cookie::forget('cookie_name'));
        $this->clearBrowserCache();
        $value = $request->path();
        $exists_url = GoogleAds::where('name', $value)->first();
        if($exists_url){
            $sites = json_decode($exists_url->site_redirect,true);
            $site_check = getDomain();

            $check_site = collect($sites)->first(function ($item) use ($site_check) {
                return strtolower($item) === strtolower($site_check);
            });
            if($check_site){
                if (checkBlockIp()) {
                    $url_block = $exists_url->url_block;
                    if ($url_block) {
                        return redirect($url_block, 301);
                    } else {
                        $html = $exists_url->html;
                        if ($html) {
                            return response($html);
                        }else{
                            return redirect('/' );
                        }
                    }
                }else{
                    $is_Devices = $exists_url->is_Devices;
                    if($is_Devices == 1){
                        $ip_address = get_ip();
                        $location = GeoIP::getLocation($ip_address);
                        $iso_code = $location['iso_code'];
                        $iso_code_database = json_decode($exists_url->country_value,true);

                        $check_iso = collect($iso_code_database)->first(function ($item) use ($iso_code) {
                            return strtolower($item['country']) === strtolower($iso_code);
                        });
                        if($check_iso){
                            return redirect($check_iso['url'], 301);
                        }else{
                            return redirect('/' );
                        }
                    }else{
                        $url_devices = json_decode($exists_url->devices_value, true);
                        $url_redirect = null;

                        $detect = new \Detection\MobileDetect;

                        if ($detect->isAndroidOS() && isset($url_devices['GoogleAds_Android'])) {
                            $url_redirect = $url_devices['GoogleAds_Android'];
                        } elseif ($detect->isiOS() && isset($url_devices['GoogleAds_OS'])) {
                            $url_redirect = $url_devices['GoogleAds_OS'];
                        }elseif (isset($url_devices['GoogleAds_Windows'])) {
                            $url_redirect = $url_devices['GoogleAds_Windows'];
                        }elseif (isset($url_devices['GoogleAds_Other'])) {
                            $url_redirect = $url_devices['GoogleAds_Other'];
                        }
                        if ($url_redirect) {
                            return redirect($url_redirect, 301);
                        } else {
                            if ($exists_url->html){
                                return response($exists_url->html);
                            }else{
                                return redirect('/', 301);
                            }
                        }
                    }
                }
            }else{
                return redirect('/' );
            }
        }else{
            return redirect('/' );
        }
    }


    public function clearBrowserCache()
    {
        $response = new Response();

        // Xóa cache trình duyệt
        $response->header('Cache-Control', 'no-cache, no-store, must-revalidate');
        $response->header('Pragma', 'no-cache');
        $response->header('Expires', '0');

        // Xóa cookie liên quan đến cache (nếu có)
        $response->cookie('cookiename', '', 0, '/');

        return $response;
    }
}
