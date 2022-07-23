<?php

namespace App\Http\Controllers\Api\v1;

use App\Categories;
use App\Http\Controllers\Controller;
use App\Http\Resources\v1\WallpapersResource;
use App\Http\Resources\v1\CategoriesResource;
use App\ListIP;
use App\Sites;
use App\Wallpapers;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public $_content_type = "application/json";
    private $_code = 200;
    public $_request = array();
    public function index(){

        dd(\request()->all());
        $api_key = 'UMvz0pkHexZ3ApdN4fpmVSJSiBXEEqLd8mZhgywEXVQvJ4LPTOWcYYSt0j4QO8Zm';

//        if(isset($_GET['keyapi']) && $_GET['keyapi'] == $api_key ){
            if (isset($_GET['get_wallpapers'])) {
                $this->get_wallpapers();
            } else if (isset($_GET['get_one_wallpaper'])) {
                $this->get_one_wallpaper();
            } else if (isset($_GET['get_categories'])) {
                $this->get_categories();
            } else if (isset($_GET['get_category_details'])) {
                $this->get_category_details();
            } else if (isset($_GET['get_search'])) {
                $this->get_search();
            } else if (isset($_GET['get_search_category'])) {
                $this->get_search_category();
            } else if (isset($_GET['update_view'])) {
                $this->update_view();
            } else if (isset($_GET['update_download'])) {
                $this->update_download();
            } else if (isset($_GET['get_ads'])) {
                $this->get_ads();
            } else if (isset($_GET['get_settings'])) {
                $this->get_settings();
            } else {
                $this->processApi();
            }
//        }else {
//            $this->processApi();
//        }

    }




    public function processApi() {
        if(isset($_REQUEST['x']) && $_REQUEST['x']!=""){
            $func = strtolower(trim(str_replace("/","", $_REQUEST['x'])));
            if((int)method_exists($this,$func) > 0) {
                $this->$func();
            } else {
                header( 'Content-Type: application/json; charset=utf-8' );
                echo 'processApi - method not exist';
                exit;
            }
        } else {
            header( 'Content-Type: application/json; charset=utf-8' );
            echo 'processApi - method not exist';
            exit;
        }
    }

    /* Api Checker */


    public function get_wallpapers() {

        $domain=$_SERVER['SERVER_NAME'];
        if($this->get_request_method() != "GET") $this->response('',406);
        $page_limit = isset($_GET['count']) ? ((int)$_GET['count']) : 10;
        $page = isset($_GET['page']) ? ((int)$_GET['page']) : 1;

        $limit= ($page -1) * $page_limit;
        $order = isset($_GET['order']) ? ((int)$_GET['order']) : 1;


        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else if (isset($_SERVER["HTTP_CF_CONNECTING_IP"]))
            $ipaddress= $_SERVER["HTTP_CF_CONNECTING_IP"];
        else
            $ipaddress = 'UNKNOWN';

        $site = Sites::where('site_web',$domain)->first();

        $listIp = ListIP::where('ip_address',$ipaddress)->where('id_site',$site->id)->first();
        if(!$listIp){
            ListIP::create([
                'ip_address'=>$ipaddress,
                'id_site' => $site->id,
                'count' => 1
            ]);
        }else{
            $listIp->update(['count' => $listIp->count +1]);
        }



        if (checkBlockIp()){
            if($order == 1){
                $data = Wallpapers::with('tags')
                    ->where('image_extension', '<>','image/gif')
                    ->whereHas('categories', function ($query) use ($site) {
                        $query->where('category_checked_ip', 1)
                            ->where('site_id',$site->id);
                    })
                    ->distinct()
                    ->orderBy('id','desc')
                    ->paginate($page_limit);
            }
            elseif($order == 2){
                $data = Wallpapers::with('tags')
                    ->where('image_extension', '<>','image/gif')
                    ->whereHas('categories', function ($query) use ($site) {
                        $query->where('category_checked_ip', 1)
                            ->where('site_id',$site->id);
                    })
                    ->distinct()
                    ->orderBy('updated_at','desc')
                    ->paginate($page_limit);
            }
            elseif($order == 3){
                $data = Wallpapers::with('tags')
                    ->where('image_extension', '<>','image/gif')
                    ->whereHas('categories', function ($query) use ($site) {
                        $query->where('category_checked_ip', 1)
                            ->where('site_id',$site->id);
                    })
                    ->distinct()
                    ->orderBy('wallpaper_view_count','desc')
                    ->paginate($page_limit);
            }
            elseif($order == 4){
                $data = Wallpapers::with('tags')
                    ->where('image_extension', '<>','image/gif')
                    ->whereHas('categories', function ($query) use ($site) {
                        $query->where('category_checked_ip', 1)
                            ->where('site_id',$site->id);
                    })
                    ->distinct()
                    ->inRandomOrder()
                    ->paginate($page_limit);
            }else{
                $data = Wallpapers::with('tags')
                    ->where('image_extension','image/gif')
                    ->whereHas('categories', function ($query) use ($site) {
                        $query->where('category_checked_ip', 1)
                            ->where('site_id',$site->id);
                    })
                    ->distinct()
                    ->inRandomOrder()
                    ->paginate($page_limit);
            }


        }else{
            if($order == 1){
                $data = Wallpapers::with('tags')
                    ->where('image_extension', '<>','image/gif')
                    ->whereHas('categories', function ($query) use ($site) {
                        $query->where('category_checked_ip', 0)
                            ->where('site_id',$site->id);
                    })
                    ->distinct()
                    ->orderBy('id','desc')
                    ->paginate($page_limit);
            }
            elseif($order == 2){
                $data = Wallpapers::with('tags')
                    ->where('image_extension', '<>','image/gif')
                    ->whereHas('categories', function ($query) use ($site) {
                        $query->where('category_checked_ip',0)
                            ->where('site_id',$site->id);
                    })
                    ->distinct()
                    ->orderBy('updated_at','desc')
                    ->paginate($page_limit);
            }
            elseif($order == 3){
                $data = Wallpapers::with('tags')
                    ->where('image_extension', '<>','image/gif')
                    ->whereHas('categories', function ($query) use ($site) {
                        $query->where('category_checked_ip', 0)
                            ->where('site_id',$site->id);
                    })
                    ->distinct()
                    ->orderBy('wallpaper_view_count','desc')
                    ->paginate($page_limit);
            }
            elseif($order == 4){
                $data = Wallpapers::with('tags')
                    ->where('image_extension', '<>','image/gif')
                    ->whereHas('categories', function ($query) use ($site) {
                        $query->where('category_checked_ip', 0)
                            ->where('site_id',$site->id);
                    })
                    ->distinct()
                    ->inRandomOrder()
                    ->paginate($page_limit);
            }else{
                $data = Wallpapers::with('tags')
                    ->where('image_extension','image/gif')
                    ->whereHas('categories', function ($query) use ($site) {
                        $query->where('category_checked_ip', 0)
                            ->where('site_id',$site->id);
                    })
                    ->distinct()
                    ->inRandomOrder()
                    ->paginate($page_limit);
            }
        }
        $getResource= WallpapersResource::collection($data);
        $count_total = $data->total();
        $count = $data->perPage();
        $respon = array(
            'status' => 'ok', 'count' => $count, 'count_total' => $count_total, 'pages' => $page, 'posts' => $getResource
        );
        $this->response($this->json($respon), 200);
    }

    public function get_categories() {
        if($this->get_request_method() != "GET") $this->response('',406);
        $domain=$_SERVER['SERVER_NAME'];
        $site = Sites::where('site_web',$domain)->first();
        $load_categories = $site->load_categories;

        if(checkBlockIp()){
            if($load_categories == 0 ){
                $data = $site
                    ->categories()
                    ->where('category_checked_ip', 1)
                    ->inRandomOrder()
                    ->withCount('wallpaper')
                    ->get();
            }
            elseif($load_categories == 1 ){
                $data = $site
                    ->categories()
                    ->where('category_checked_ip', 1)
                    ->orderBy('category_view_count','desc')
                    ->withCount('wallpaper')
                    ->get();
            }
            elseif($load_categories == 2 ){
                $data = $site
                    ->categories()
                    ->where('category_checked_ip', 1)
                    ->orderBy('updated_at','desc')
                    ->withCount('wallpaper')
                    ->get();
            }
        } else{
            if($load_categories == 0 ){
                $data = $site
                    ->categories()
                    ->where('category_checked_ip', 0)
                    ->inRandomOrder()
                    ->withCount('wallpaper')
                    ->get();
            }
            elseif($load_categories == 1 ){
                $data = $site
                    ->categories()
                    ->where('category_checked_ip', 0)
                    ->orderBy('category_view_count','desc')
                    ->withCount('wallpaper')
                    ->get();
            }
            elseif($load_categories == 2 ){
                $data = $site
                    ->categories()
                    ->where('category_checked_ip', 0)
                    ->orderBy('updated_at','desc')
                    ->withCount('wallpaper')
                    ->get();
            }
        }

        $categories =  CategoriesResource::collection($data);
        $count = count($data);
        $respon = array(
            'status' => 'ok', 'count' => $count, 'categories' => $categories
        );
        $this->response($this->json($respon), 200);

    }

    public function get_category_details() {

        if($this->get_request_method() != "GET") $this->response('',406);

        $page_limit = isset($_GET['count']) ? ((int)$_GET['count']) : 10;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $id = $_GET['id'];
        $domain=$_SERVER['SERVER_NAME'];
        $site = Sites::where('site_web',$domain)->first();
        $load_wallpapers_category = $site->load_wallpapers_category;

        $category = Categories::findOrFail($id);
        if($load_wallpapers_category==0){
            $data = $category
                ->wallpaper()
                ->distinct()
                ->inRandomOrder()
                ->paginate($page_limit);
        }
        elseif($load_wallpapers_category==1){
            $data = $category
                ->wallpaper()
                ->distinct()
                ->orderBy('wallpaper_like_count','desc')
                ->paginate($page_limit);
        }
        elseif($load_wallpapers_category==2){
            $data = $category
                ->wallpaper()
                ->distinct()
                ->orderBy('wallpaper_view_count','desc')
                ->paginate($page_limit);
        }
        elseif($load_wallpapers_category==3){
            $data = $category
                ->wallpaper()
                ->distinct()
                ->orderBy('created_at','desc')
                ->paginate($page_limit);

        }
        $category->update(['category_view_count'=>$category->category_view_count+1]);
        $wallpapers= WallpapersResource::collection($data);
        $count_total = $data->total();
        $count = $data->perPage();

        $respon = array(
            'status' => 'ok', 'count' => $count, 'count_total' => $count_total, 'pages' => $page, 'posts' => $wallpapers
        );
        $this->response($this->json($respon), 200);
    }

    public function update_view() {
        $image_id = $_POST['image_id'];
        $wallpaper = Wallpapers::find($image_id);
        if ($wallpaper) {
            $wallpaper->wallpaper_view_count = $wallpaper->wallpaper_view_count + 1;
            $wallpaper->save();
            header( 'Content-Type: application/json; charset=utf-8' );
            echo json_encode(array('response' => "View updated"));
        } else {
            header( 'Content-Type: application/json; charset=utf-8' );
            echo json_encode(array('response' => "Failed"));
        }
    }

    public function update_download() {
        $image_id = $_POST['image_id'];

        $wallpaper = Wallpapers::find($image_id);
        if ($wallpaper) {
            $wallpaper->wallpaper_download_count = $wallpaper->wallpaper_download_count + 1;
            $wallpaper->save();
            header( 'Content-Type: application/json; charset=utf-8' );
            echo json_encode(array('response' => "View updated"));
        } else {
            header( 'Content-Type: application/json; charset=utf-8' );
            echo json_encode(array('response' => "Failed"));
        }

    }

    public function get_ads() {
        if($this->get_request_method() != "GET") $this->response('',406);

        $domain=$_SERVER['SERVER_NAME'];
        $data = Sites::where('site_web',$domain)
            ->first()->toArray();



        $ads = json_decode($data['site_ads'], true);
//

        $ads_arr = [
            'id' => $data['id'],
            'ad_status' => $data['ad_switch'] == 1 ? 'on' : 'off',
            'ad_type' => 'admob',
            'admob_publisher_id' => $ads ? $ads['AdMob_Publisher_ID'] : '',
            'admob_app_id' => $ads ? $ads['AdMob_App_ID']: '',
            'admob_banner_unit_id' => $ads ? $ads['AdMob_Banner_Ad_Unit_ID']: '',
            'admob_interstitial_unit_id' => $ads ? $ads['AdMob_Interstitial_Ad_Unit_ID']: '',
            'admob_native_unit_id' => $ads ? $ads['AdMob_Native_Ad_Unit_ID']: '',
            'admob_app_open_ad_unit_id' => $ads ? $ads['AdMob_App_Open_Ad_Unit_ID']: '',
            'fan_banner_unit_id' => 0,
            'fan_interstitial_unit_id' => 0,
            'fan_native_unit_id' => 0,
            'startapp_app_id' => 0,
            'unity_game_id' => 0,
            'unity_banner_placement_id' => 'banner',
            'unity_interstitial_placement_id' => 'video',
            'applovin_banner_ad_unit_id' => 0,
            'applovin_interstitial_ad_unit_id' => 0,
            'mopub_banner_ad_unit_id' => 0,
            'mopub_interstitial_ad_unit_id' => 0,
            'interstitial_ad_interval' => 0,
            'native_ad_interval' => 0,
            'native_ad_index' => 6,
            'last_update_ads' => $data['updated_at'],
        ];
        $ads_status = [
            'ads_status_id' => $data['ad_switch'] == 1 ? 1 : 0,
            'banner_ad_on_home_page' =>$data['ad_switch'] == 1 ? 1 : 0,
            'banner_ad_on_search_page' => $data['ad_switch'] == 1 ? 1 : 0,
            'banner_ad_on_wallpaper_detail' => $data['ad_switch'] == 1 ? 1 : 0,
            'banner_ad_on_wallpaper_by_category' => $data['ad_switch'] == 1 ? 1 : 0,
            'interstitial_ad_on_click_wallpaper' => $data['ad_switch'] == 1 ? 1 : 0,
            'interstitial_ad_on_wallpaper_detail' => $data['ad_switch'] == 1 ? 1 : 0,
            'native_ad_on_wallpaper_list' => $data['ad_switch'] == 1 ? 1 : 0,
            'native_ad_on_exit_dialog' => $data['ad_switch'] == 1 ? 1 : 0,
            'app_open_ad' => $data['ad_switch'] == 1 ? 1 : 0,
            'last_update_ads_status' => $data['updated_at'],

        ];

        $respon = array(
            'status' => 'ok', 'ads' => $ads_arr, 'ads_status' => $ads_status
        );
        $this->response($this->json($respon), 200);
    }

    public function get_settings() {

        if($this->get_request_method() != "GET") $this->response('',406);

        $domain=$_SERVER['SERVER_NAME'];
        $data = Sites::where('site_web',$domain)
            ->first()->toArray();

        $settings = [
            'onesignal_app_id' =>'d',
            'privacy_policy' =>$data['site_policy'],
        ];

        $respon = array(
            'status' => 'ok', 'settings' => $settings
        );
        $this->response($this->json($respon), 200);
    }

    public function get_search(){
        $domain = $_SERVER['SERVER_NAME'];
        $site = Sites::where('site_web',$domain)->first();
        $page_limit = isset($_GET['count']) ? ((int)$_GET['count']) : 10;
        $page = isset($_GET['page']) ? ((int)$_GET['page']) : 1;

        if (checkBlockIp()){

                $data = Wallpapers::with('tags')
                    ->where('wallpaper_name', 'like', '%' . $_GET['search'] . '%')
                    ->whereHas('categories', function ($query) use ($site) {
                        $query->where('category_checked_ip', 1)
                            ->where('site_id',$site->id);
                    })
                    ->distinct()
                    ->orderBy('id','desc')
                    ->paginate($page_limit);


        }else{
                $data = Wallpapers::with('tags')
                    ->where('wallpaper_name', 'like', '%' . $_GET['search'] . '%')
                    ->whereHas('categories', function ($query) use ($site) {
                        $query->where('category_checked_ip', 0)
                            ->where('site_id',$site->id);
                    })
                    ->distinct()
                    ->orderBy('id','desc')
                    ->paginate($page_limit);

        }
        $getResource= WallpapersResource::collection($data);
        $count_total = $data->total();
        $count = $data->perPage();
        $respon = array(
            'status' => 'ok', 'count' => $count, 'count_total' => $count_total, 'pages' => $page, 'posts' => $getResource
        );
        $this->response($this->json($respon), 200);
    }

    public function get_search_category(){
        $domain = $_SERVER['SERVER_NAME'];
        $site = Sites::where('site_web',$domain)->first();
        $page_limit = isset($_GET['count']) ? ((int)$_GET['count']) : 10;
        $page = isset($_GET['page']) ? ((int)$_GET['page']) : 1;

        if (checkBlockIp()){

            $data = $site
                ->categories()
                ->where('category_checked_ip', 1)
                ->where('category_name', 'like', '%' . $_GET['search'] . '%')
                ->inRandomOrder()
                ->withCount('wallpaper')
                ->get();



        }else{
            $data = $site
                ->categories()
                ->where('category_checked_ip', 0)
                ->where('category_name', 'like', '%' . $_GET['search'] . '%')
                ->inRandomOrder()
                ->withCount('wallpaper')
                ->get();

        }
        $categories =  CategoriesResource::collection($data);
        $count = count($data);
        $respon = array(
            'status' => 'ok', 'count' => $count, 'categories' => $categories
        );
        $this->response($this->json($respon), 200);
    }




    private function responseInvalidParam() {
        $resp = array("status" => 'Failed', "msg" => 'Invalid Parameter' );
        $this->response($this->json($resp), 200);
    }

    /* ==================================== End of API utilities ==========================================
     * ====================================================================================================
     */

    /* Encode array into JSON */
    private function json($data) {
        if(is_array($data)) {
            // return json_encode($data, JSON_NUMERIC_CHECK);
            return json_encode($data);
        }
    }



    public function get_request_method(){
        return $_SERVER['REQUEST_METHOD'];
    }

    public function response($data,$status){
        $this->_code = ($status)?$status:200;
        $this->set_headers();
        echo $data;
        exit;
    }
    private function set_headers(){
        header("HTTP/1.1 ".$this->_code." ".$this->get_status_message());
        header("Content-Type:".$this->_content_type);
    }

    private function get_status_message(){
        $status = array(
            200 => 'OK',
            201 => 'Created',
            204 => 'No Content',
            404 => 'Not Found',
            406 => 'Not Acceptable',
            401 => 'Unauthorized');
        return ($status[$this->_code])?$status[$this->_code]:$status[500];
    }

}
