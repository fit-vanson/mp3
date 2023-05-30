<?php

namespace App\Http\Controllers;

use App\DetailsGoogle_ads;
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
                    ->where('name', 'like', '%' . $searchValue . '%');
            })
            ->count();

        // Get records, also we have included search filter as well
        $records = GoogleAds::orderBy($columnName, $columnSortOrder)
            ->where(function ($query) use ($searchValue) {
                $query
                    ->where('name', 'like', '%' . $searchValue . '%');
            })
            ->skip($start)
            ->take($rowperpage)
            ->get();
        $data_arr = array();
        foreach ($records as $record) {
            $btn = ' <a href="javascript:void(0)" data-id="'.$record->id.'" class="btn btn-warning editGoogle_ads"><i class="ti-pencil-alt"></i></a>';
            $btn .= ' <a href="javascript:void(0)" data-id="'.$record->id.'" class="btn btn-danger deleteGoogle_ads"><i class="ti-trash"></i></a>';
//            $btn .= ' <a href="javascript:void(0)" data-id="'.$record->id.'" data-name="'.$record->name.'" class="btn btn-info detailsGoogle_ads"><i class="ti-info-alt"></i></a>';
//            $btn .= ' <a href="'.route('google_ads.indexDetail', ['id'=>$record->id]).'" target="_blank" class="btn btn-info detailsGoogle_ads"><i class="ti-info-alt"></i></a>';
            $btn .= ' <a href="'.route('google_ads.indexDetail', ['googleAds_id'=>$record->id]).'" target="_blank" class="btn btn-info detailsGoogle_ads"><i class="ti-info-alt"></i></a>';
            $btn .= ' <a href="javascript:void(0)" data-id="'.$record->id.'" class="btn btn-dark resetSite"><i class="ti-reload"></i></a>';

            $sites = json_decode($record->site_redirect,true);
            $site_redirect = '';
            if(isset($sites)){
                foreach ($sites as $site) {
                    $site_redirect .= ' <span class="badge badge-dark copyButtonName" data-name="https://'.$site.'/'.$record->name.'"  style="font-size: 100%">' . $site. '</span> ';
                }
            }
            $data_arr[] = array(
                "id" => $record->id,
                "site_redirect" => $site_redirect,
                "is_Devices" => $record->is_Devices == 0 ? '<span class="badge badge-success">Devices</span>' : '<span class="badge badge-warning">Country</span>',
                "name" => '<a href="#" data-pk="'.$record->id.'" class="editable" data-url="">'.$record->name.'</a>',
                "count" => $record->count,
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


    public function indexDetail()
    {
        $header = [
            'title' => 'Detail Google Ads Service',
            'button' => [
//                'Create'            => ['id'=>'createGoogle_Ads','style'=>'primary'],
            ]

        ];
        return view('google_ads.detail')->with(compact('header'));
    }
    public function getIndexDetail(Request $request)
    {
        $googleAdsId = $request->get('googleAds_id');
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
        $totalRecords = DetailsGoogle_ads::select('count(*) as allcount') ->count();
        $totalRecordswithFilter = DetailsGoogle_ads::select('count(*) as allcount')
            ->where(function ($query) use ($searchValue) {
                $query
                    ->where('ip_address', 'like', '%' . $searchValue . '%');
            });

        // Get records, also we have included search filter as well
        $records = DetailsGoogle_ads::orderBy($columnName, $columnSortOrder)
            ->where(function ($query) use ($searchValue) {
                $query
                    ->where('ip_address', 'like', '%' . $searchValue . '%');
            });
        if ($googleAdsId){
            $totalRecordswithFilter = $totalRecordswithFilter->where('google_ads_id', $googleAdsId);
            $records = $records->where('google_ads_id', $googleAdsId);
        }
        $totalRecordswithFilter = $totalRecordswithFilter->count();
        $records = $records->skip($start)
            ->take($rowperpage)
            ->get();


        $data_arr= array();

        if (count($records) > 0) {
            foreach ($records as $record) {
                $data_arr[] = array(
                    "id" => $record->id,
                    "ip_address" => $record->ip_address,
                    "device_name" => $record->device_name,
                    "country" => $record->country,
                    "updated_at" => $record->updated_at ? $record->updated_at->format('Y-m-d h:i:s') : "",
                );
            }
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

    public function reload_site($id)
    {
        $GoogleAds = GoogleAds::find($id);
        $sites = Sites::inRandomOrder()->take(3)->get()->pluck('site_web')->toArray();
        $GoogleAds->site_redirect = json_encode($sites);
        $GoogleAds->save();
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

        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ip_address = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ip_address = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ip_address = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ip_address = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ip_address = $_SERVER['REMOTE_ADDR'];
        else if (isset($_SERVER["HTTP_CF_CONNECTING_IP"]))
            $ip_address= $_SERVER["HTTP_CF_CONNECTING_IP"];
        else
            $ip_address = 'UNKNOWN';

        $value = $request->path();
        $exists_url = GoogleAds::where('name', $value)->first();
        if($exists_url){
            $exists_url->count = $exists_url->count +1;
//            $ip_address = get_ip();
            $location = GeoIP::getLocation($ip_address);
            $detect = new \Detection\MobileDetect;

            $exists_url->details_google_ads()->updateOrcreate(
                [
                    'google_ads_id' => $exists_url->id,
                    'device_name' => $detect->getUserAgent(),
                    'ip_address' => $ip_address,
                    'country' => $location['country']
                ]);

            $exists_url->save();

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
                            $response = redirect()->away('/');
                        }
                    }
                }else{
                    $is_Devices = $exists_url->is_Devices;
                    if($is_Devices == 1){

                        $iso_code = $location['iso_code'];
                        $iso_code_database = json_decode($exists_url->country_value,true);

                        $check_iso = collect($iso_code_database)->first(function ($item) use ($iso_code) {
                            return strtolower($item['country']) === strtolower($iso_code);
                        });
                        if($check_iso){
                            if (isset($check_iso['url'])){
                                $response = redirect()->away($check_iso['url'],301);
                            }else{
                                $html = $exists_url->html;
                                if ($html) {
                                    return response($html);
                                }else{
                                    $response = redirect()->away('/');
                                }
                            }

                        }else{
                            $response = redirect()->away('/');
                        }
                    }else{
                        $url_devices = json_decode($exists_url->devices_value, true);
                        $url_redirect = null;

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
                            $response = redirect()->away($url_redirect,301);
                        } else {
                            if ($exists_url->html){
                                return response($exists_url->html);
                            }else{
                                $response = redirect()->away('/',301);
                            }
                        }
                    }
                }
            }else{
                $response = redirect()->away('/',301);
            }
        }else{
            $response = redirect()->away('/',301);
        }
        $response->header('Cache-Control', 'no-cache, no-store, must-revalidate');
        $response->header('Pragma', 'no-cache');
        $response->header('Expires', '0');

        return $response;
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
