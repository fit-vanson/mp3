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
        $api_key = 'UMvz0pkHexZ3ApdN4fpmVSJSiBXEEqLd8mZhgywEXVQvJ4LPTOWcYYSt0j4QO8Zm';
        if(isset($_GET['keyapi']) && $_GET['keyapi'] == $api_key ){
            if (isset($_GET['action']) && $_GET['action'] == "get_category") {
                $this->getCategory();

            } else if (isset($_GET['action']) && $_GET['action'] == "get_category_detail") {
                $id = $_GET['id'];
                $offset = $_GET['offset'];
                $this->getCategoryDetail($id, $offset);

            } else if (isset($_GET['action']) && $_GET['action'] == "get_recent") {

                $offset = $_GET['offset'];

                $this->getRecent($offset);

            } else if (isset($_GET['action']) && $_GET['action'] == "get_popular") {

                $offset = $_GET['offset'];
                $this->getPopular($offset);

            } else if (isset($_GET['action']) && $_GET['action'] == "get_random") {

                $offset = $_GET['offset'];
                $this->getRandom($offset);

            } else if (isset($_GET['action']) && $_GET['action'] == "get_featured") {

                $offset = $_GET['offset'];
                $this->getFeatured($offset);

            } else if (isset($_GET['action']) && $_GET['action'] == "get_search") {

                $search = $_GET['search'];
                $offset = $_GET['offset'];
                $this->getSearch($search, $offset);

            } else if (isset($_GET['action']) && $_GET['action'] == "view_count") {

                $id = $_GET['id'];
                $this->viewCount($id);

            } else if (isset($_GET['action']) && $_GET['action'] == "download_count") {

                $id = $_GET['id'];
                $this->downloadCount($id);

            } else if (isset($_GET['action']) && $_GET['action'] == "get_privacy_policy") {

                $this->getPrivacyPolicy();

            } else if (isset($_GET['get_wallpapers'])) {
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
        }else {
            $this->processApi();
        }

    }


    function getCategory() {
        $domain=$_SERVER['SERVER_NAME'];
        if(checkBlockIp()){
            $data = CategoryManage::
            leftJoin('tbl_category_has_site', 'tbl_category_has_site.category_id', '=', 'tbl_category_manages.id')
                ->leftJoin('tbl_site_manages', 'tbl_site_manages.id', '=', 'tbl_category_has_site.site_id')
                ->has('wallpaper','>',0)
                ->where('site_name',$domain)
                ->where('tbl_category_manages.checked_ip',1)
                ->select('tbl_category_manages.*','tbl_category_has_site.image as site_image')
                ->withCount('wallpaper')
                ->get();
        } else{
            $data = CategoryManage::
            leftJoin('tbl_category_has_site', 'tbl_category_has_site.category_id', '=', 'tbl_category_manages.id')
                ->leftJoin('tbl_site_manages', 'tbl_site_manages.id', '=', 'tbl_category_has_site.site_id')
                ->has('wallpaper','>',0)
                ->where('site_name',$domain)
                ->where('tbl_category_manages.checked_ip',0)
                ->select('tbl_category_manages.*','tbl_category_has_site.image as site_image')
                ->withCount('wallpaper')
                ->get();
        }
        dd($data);







        $setting_qry = "SELECT * FROM tbl_settings where id = '1'";
        $result = mysqli_query($connect, $setting_qry);
        $row    = mysqli_fetch_assoc($result);
        $sort   = $row['category_sort'];
        $order  = $row['category_order'];

        $json_object = array();

        $query = "SELECT cid, category_name, category_image FROM tbl_category ORDER BY $sort $order";
        $sql = mysqli_query($connect, $query);

        while ($data = mysqli_fetch_assoc($sql)) {

            $query = "SELECT COUNT(*) as num FROM tbl_gallery WHERE cat_id = '".$data['cid']."'";
            $total = mysqli_fetch_array(mysqli_query($connect, $query));
            $total = $total['num'];

            $object['category_id'] = $data['cid'];
            $object['category_name'] = $data['category_name'];
            $object['category_image'] = $data['category_image'];
            $object['total_wallpaper'] = $total;

            array_push($json_object, $object);

        }

        $set = $json_object;

        header( 'Content-Type: application/json; charset=utf-8' );
        echo $val = str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        die();

    }

    function getCategoryDetail($id, $offset) {



        dd(1);

        include_once "../includes/config.php";

        $qry = "SELECT * FROM tbl_settings where id = '1'";
        $result = mysqli_query($connect, $qry);
        $settings_row = mysqli_fetch_assoc($result);
        $load_more = $settings_row['limit_recent_wallpaper'];

        $id = $_GET['id'];
        $offset = isset($_GET['offset']) && $_GET['offset'] != '' ? $_GET['offset'] : 0;


        $all = mysqli_query($connect, "SELECT * FROM tbl_gallery ORDER BY id DESC");
        $count_all = mysqli_num_rows($all);
        $query = mysqli_query($connect, "SELECT w.id, w.image, w.image_url, w.type, w.view_count, w.download_count, w.featured, w.tags, c.cid AS 'category_id', c.category_name FROM tbl_category c, tbl_gallery w WHERE c.cid = w.cat_id AND c.cid = $id ORDER BY w.id DESC LIMIT $offset, $load_more");
        $count = mysqli_num_rows($query);
        $json_empty = 0;
        if ($count < $load_more) {
            if ($count == 0) {
                $json_empty = 1;
            } else {
                $query = mysqli_query($connect, "SELECT w.id, w.image, w.image_url, w.type, w.view_count, w.download_count, w.featured, w.tags, c.cid AS 'category_id', c.category_name FROM tbl_category c, tbl_gallery w WHERE c.cid = w.cat_id AND c.cid = $id ORDER BY w.id DESC LIMIT $offset, $count");
                $count = mysqli_num_rows($query);
                if (empty($count)) {
                    $query = mysqli_query($connect, "SELECT w.id, w.image, w.image_url, w.type, w.view_count, w.download_count, w.featured, w.tags, c.cid AS 'category_id', c.category_name FROM tbl_category c, tbl_gallery w WHERE c.cid = w.cat_id AND c.cid = $id ORDER BY w.id DESC LIMIT 0, $load_more");
                    $num = 0;
                } else {
                    $num = $offset;
                }
            }
        } else {
            $num = $offset;
        }
        $json = '[';
        while ($row = mysqli_fetch_array($query)) {
            $num++;
            $char ='"';
            $json .= '{
				"no": '.$num.',
				"image_id": "'.$row['id'].'",
				"image_upload": "'.$row['image'].'",
				"image_url": "'.$row['image_url'].'",
				"type": "'.$row['type'].'",
				"view_count": "'.$row['view_count'].'",
				"download_count": "'.$row['download_count'].'",
				"featured": "'.$row['featured'].'",
				"tags": "'.$row['tags'].'",
				"category_id": "'.$row['category_id'].'",
				"category_name": "'.$row['category_name'].'"
			},';
        }

        $json = substr($json,0, strlen($json)-1);

        if ($json_empty == 1) {
            $json = '[]';
        } else {
            $json .= ']';
        }

        header('Content-Type: application/json; charset=utf-8');
        echo $json;

        mysqli_close($connect);

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

        if (checkBlockIp()) {
            $data = Sites::where('site_web',$domain)->first()
                ->categories()
                ->where('category_checked_ip',1)
                ->get();
            $wallpaper = [];
            foreach ($data as $item ){
                foreach ($item->wallpaper()->where('image_extension','<>','image/gif')->with('categories')->get()->toArray() as $wall){
                    $wallpaper[] = $wall;
                }
            }
        } else {
            $data = Sites::where('site_web',$domain)->first()
                ->categories()
                ->where('category_checked_ip',0)
                ->get();

            $wallpaper = [];
            foreach ($data as $item ){
                foreach ($item->wallpaper()->where('image_extension','<>','image/gif')->with('categories')->get()->toArray() as $wall){
                    $wallpaper[] = $wall;
                }
            }
        }

        $temp = array_unique(array_column($wallpaper, 'id'));
        $unique_arr = array_intersect_key($wallpaper, $temp);


        if($order == 1){
            usort($unique_arr, function($a, $b) {
                return $b['id'] <=> $a['id'];
            });

        }elseif ($order ==2){
            usort($unique_arr, function($a, $b) {
                return $b['updated_at'] <=> $a['updated_at'];
            });
        }elseif ($order ==3){
            usort($unique_arr, function($a, $b) {
                return $b['wallpaper_view_count'] <=> $a['wallpaper_view_count'];
            });
        }elseif ($order ==4){
            shuffle($unique_arr);
        }else{
            usort($unique_arr, function($a, $b) {
                return $b['wallpaper_like_count'] <=> $a['wallpaper_like_count'];
            });
        }

        $result = array_slice($unique_arr, $limit, $page_limit);

        $getResource= WallpapersResource::collection(json_decode(json_encode($result), FALSE));
        $count_total = count($unique_arr);
        $count = count($result);
        $respon = array(
            'status' => 'ok', 'count' => $count, 'count_total' => $count_total, 'pages' => $page, 'posts' => $getResource
        );
        $this->response($this->json($respon), 200);
    }



    public function get_categories() {
        if($this->get_request_method() != "GET") $this->response('',406);
        $domain=$_SERVER['SERVER_NAME'];

        if(checkBlockIp()){
            $data = Sites::where('sites.site_web',$domain)
                ->with(['categories'=>function ($q){
                    $q->withCount('wallpaper')
                        ->where('category_checked_ip', 1);
                }])
                ->first();

        } else{
            $data = Sites::where('sites.site_web',$domain)
                ->with(['categories'=>function ($q){
                    $q->withCount('wallpaper')
                        ->where('category_checked_ip', 0);
                }])
                ->first();
        }

        $categories =  CategoriesResource::collection($data->categories);
        $count = count($categories);
        $respon = array(
            'status' => 'ok', 'count' => $count, 'categories' => $categories
        );
        $this->response($this->json($respon), 200);

    }

    public function get_category_details() {


        if($this->get_request_method() != "GET") $this->response('',406);
//        dd($_GET['page']);
        $page_limit = isset($_GET['count']) ? ((int)$_GET['count']) : 10;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $limit=($page - 1) * $page_limit;
        $id = $_GET['id'];

        $data = Categories::findorFail($id)
            ->wallpaper()
            ->distinct()
            ->orderBy('wallpaper_like_count', 'desc')
            ->skip($limit)
            ->take($page_limit)
            ->get();


        $wallpapers= WallpapersResource::collection($data);
        $count_total = Categories::findOrFail($id)
            ->wallpaper()
            ->distinct()
            ->get()
            ->count();
        $count = count($wallpapers);
;
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

    /* String mysqli_real_escape_string */
    private function real_escape($s) {
        return mysqli_real_escape_string($this->mysqli, $s);
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
