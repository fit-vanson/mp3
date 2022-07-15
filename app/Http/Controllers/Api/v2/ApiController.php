<?php

namespace App\Http\Controllers\Api\v2;

use App\Categories;
use App\Http\Controllers\Controller;
use App\Http\Resources\v2\CategoriesResource;
use App\ListIP;
use App\Sites;
use App\VisitorFavorite;
use App\Visitors;
use App\Wallpapers;
use Dflydev\DotAccessData\Data;
use Illuminate\Http\Request;
use Rolandstarke\Thumbnail\Facades\Thumbnail;

class ApiController extends Controller
{
    public function getData(){


        $get_method = $this->checkSignSalt($_POST['data']);

        if( $get_method['method_name']=="get_home")
        {
            $this->get_home($get_method);
        }
        else if ($get_method['method_name']=="get_latest") {
            $this->get_latest($get_method);
        }
        else if ($get_method['method_name']=="get_category")
        {
            $this->get_category($get_method);
        }
        else if ($get_method['method_name']=="get_wallpaper")
        {
            $this->get_wallpaper($get_method);
        }
        else if ($get_method['method_name']=="get_single_wallpaper")
        {
            $this->get_single_wallpaper($get_method);
        }
        else if ($get_method['method_name']=="get_wallpaper_most_viewed")
        {
            $this->get_wallpaper_most_viewed($get_method);

        }
        else if ($get_method['method_name']=="get_wallpaper_most_rated")
        {
            $this->get_wallpaper_most_rated($get_method);

        }
        else if ($get_method['method_name']=="get_latest_gif")
        {
            $this->get_latest_gif($get_method);
        }
        else if($get_method['method_name']=="get_check_favorite")
        {
            echo "<pre>";
            print_r($get_method);
            echo "</pre>";
            die();
            $this->get_check_favorite($get_method);
        }
        else if($get_method['method_name']=="get_recent_post")
        {
            $this->get_recent_post($get_method);

        }
        else if ($get_method['method_name']=="get_gif_list")
        {
            $this->get_gif_list($get_method);
        }
        else if ($get_method['method_name']=="get_single_gif")
        {
            $this->get_single_gif($get_method);

        }
        else if ($get_method['method_name']=="get_gif_wallpaper_most_viewed")
        {
            $this->get_gif_wallpaper_most_viewed($get_method);

        }
        else if ($get_method['method_name']=="get_gif_wallpaper_most_rated")
        {
            $this->get_gif_wallpaper_most_rated($get_method);

        }
        else if ($get_method['method_name']=="search_wallpaper")
        {
            $this->search_wallpaper($get_method);
        }
        else if ($get_method['method_name']=="search_gif")
        {
            $this->search_gif($get_method);

        }
        else if ($get_method['method_name']=="wallpaper_rate")
        {
            echo "<pre>";
            print_r($get_method);
            echo "</pre>";
            die();
            $this->wallpaper_rate($get_method);
        }
        else if ($get_method['method_name']=="get_wallpaper_rate")
        {
            echo "<pre>";
            print_r($get_method);
            echo "</pre>";
            die();
            $this->get_wallpaper_rate($get_method);
        }
        else if ($get_method['method_name']=="gif_rate")
        {
            echo "<pre>";
            print_r($get_method);
            echo "</pre>";
            die();
            $this->gif_rate($get_method);

        }
        else if ($get_method['method_name']=="get_gif_rate")
        {
            echo "<pre>";
            print_r($get_method);
            echo "</pre>";
            die();
            $this->get_gif_rate($get_method);
        }
        else if ($get_method['method_name']=="download_wallpaper")
        {
            echo "<pre>";
            print_r($get_method);
            echo "</pre>";
            die();
            $this->download_wallpaper($get_method);

        }
        else if ($get_method['method_name']=="download_gif")
        {
            echo "<pre>";
            print_r($get_method);
            echo "</pre>";
            die();
            $this->download_gif($get_method);
        }
        else if($get_method['method_name']=="get_app_details")
        {
            $this->get_app_details($get_method);
        }

        else if($get_method['method_name']=="user_login")
        {
            echo "<pre>";
            print_r($get_method);
            echo "</pre>";
            die();
            $this->user_login($get_method);

        }

        else if($get_method['method_name']=="user_register")
        {
            echo "<pre>";
            print_r($get_method);
            echo "</pre>";
            die();
            $this->user_register($get_method);
        }
        else if($get_method['method_name']=="user_profile")
        {
            echo "<pre>";
            print_r($get_method);
            echo "</pre>";
            die();
            $this->user_profile($get_method);
        }
        else if($get_method['method_name']=="edit_profile")
        {
            echo "<pre>";
            print_r($get_method);
            echo "</pre>";
            die();
            $this->edit_profile($get_method);
        }
        else if($get_method['method_name']=="forgot_pass")
        {
            echo "<pre>";
            print_r($get_method);
            echo "</pre>";
            die();
            $this->forgot_pass($get_method);
        }
        else if($get_method['method_name']=="user_report")
        {
            echo "<pre>";
            print_r($get_method);
            echo "</pre>";
            die();
            $this->user_report($get_method);

        }
        else if($get_method['method_name']=="favorite_post")
        {
            $this->favorite_post($get_method);
        }
        else if($get_method['method_name']=="get_favorite_post")
        {
            $this->get_favorite_post($get_method);
        }
        else
        {
            $this->checkSignSalt($_GET['data']);
        }
    }

    function checkSignSalt($data_info)
    {
        $key = "zxcv@vietmmo";
//        $key = "viaviweb";
        $data_json = $data_info;
        $data_arr = json_decode(urldecode(base64_decode($data_json)), true);



        if (isset($data_arr['sign']) == '' && isset($data_arr['salt']) == '') {
            return $data_arr;


            $set['HD_WALLPAPER'][] = array("success" => -1, "MSG" => "Invalid sign salt.");
            header('Content-Type: application/json; charset=utf-8');
            echo $val = str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            exit();
        } else {

            $data_arr['salt'];

            $md5_salt = md5($key . $data_arr['salt']);

            if ($data_arr['sign'] != $md5_salt) {

                $set['HD_WALLPAPER'][] = array("success" => -1, "MSG" => "Invalid sign salt.");
                header('Content-Type: application/json; charset=utf-8');
                echo $val = str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
                exit();
            }
            if (isset($_SERVER['HTTP_CLIENT_IP']))
                $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
            else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
                $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
            else if (isset($_SERVER['HTTP_X_FORWARDED']))
                $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
            else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
                $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
            else if (isset($_SERVER['HTTP_FORWARDED']))
                $ipaddress = $_SERVER['HTTP_FORWARDED'];
            else if (isset($_SERVER['REMOTE_ADDR']))
                $ipaddress = $_SERVER['REMOTE_ADDR'];
            else if (isset($_SERVER["HTTP_CF_CONNECTING_IP"]))
                $ipaddress = $_SERVER["HTTP_CF_CONNECTING_IP"];
            else
                $ipaddress = 'UNKNOWN';

            $domain = $_SERVER['SERVER_NAME'];

            $site = Sites::where('site_web', $domain)->first();
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
            if(isset($data_arr['android_id'])){
                $visitor = $data_arr['android_id'];
                Visitors::updateOrCreate([
                    'device_id' => $visitor
                ]);
            }
        }

        return $data_arr;
    }

    private function get_home($get_method){
        $domain = $_SERVER['SERVER_NAME'];
//        if ($get_method['type'] != '') {
        $type = (isset($get_method['type']) && $get_method['type'] != '' ) ? trim($get_method['type']) : 'Square' ;
        if (checkBlockIp()) {
            $data = Sites::where('site_web',$domain)->first()
                ->categories()
                ->where('category_checked_ip',1)
                ->get();
        } else {
            $data = Sites::where('site_web',$domain)->first()
                ->categories()
                ->where('category_checked_ip',0)
                ->get();


            $wallpaper = [];
            foreach ($data as $item ){
                $item->wallpaper()->with('categories')->get()->toArray();
                foreach ($item->wallpaper()->where('image_extension','<>','image/gif')->with('categories','tags')->get()->toArray() as $wall){
                    $wallpaper[] = $wall;
                }
            }
        }

        $temp = array_unique(array_column($wallpaper, 'id'));
        $unique_arr = array_intersect_key($wallpaper, $temp);

        $row['featured_wallpaper'] =  $this->sortWallpaper($unique_arr,'wallpaper_like_count',$type, $get_method['android_id']);
        $getCategoryResource = CategoriesResource::collection($data);
        $row['wallpaper_category'] = $getCategoryResource;
        $row['latest_wallpaper'] = $this->sortWallpaper($unique_arr,null,$type, $get_method['android_id']);
        $row['popular_wallpaper'] = $this->sortWallpaper($unique_arr,'wallpaper_view_count',$type, $get_method['android_id']);
        $row['recent_wallpapers'] = $this->sortWallpaper($unique_arr,'created_at',$type, $get_method['android_id']);
        $set['HD_WALLPAPER'] = $row;
        header('Content-Type: application/json; charset=utf-8');
        echo $val = str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        die();
//        }
    }

    private function get_latest($get_method)
    {
        $domain = $_SERVER['SERVER_NAME'];
        if ($get_method['type'] != '') {
            $type = trim($get_method['type']);
            $page_limit = 12;
            $limit=($get_method['page']-1) * $page_limit;

            if (checkBlockIp()) {
                $data = Sites::where('site_web',$domain)->first()
                    ->categories()
                    ->where('category_checked_ip',1)
                    ->get();
                $wallpaper = [];
                foreach ($data as $item ){
                    $item->wallpaper()->with('categories')->get()->toArray();
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
                    $item->wallpaper()->with('categories')->get()->toArray();
                    foreach ($item->wallpaper()->where('image_extension','<>','image/gif')->with('categories')->get()->toArray() as $wall){
                        $wallpaper[] = $wall;
                    }
                }
            }


            $temp = array_unique(array_column($wallpaper, 'id'));
            $unique_arr = array_intersect_key($wallpaper, $temp);
            $result = array_slice($unique_arr, $limit, $page_limit);

            $row = $this->getWallpaper($result,$type,$get_method['android_id']);

            $set['HD_WALLPAPER'] = $row;
            header('Content-Type: application/json; charset=utf-8');
            echo $val = str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
        }
    }

    private function get_recent_post($get_method)
    {
        $domain = $_SERVER['SERVER_NAME'];
        if ($get_method['type'] != '') {
            $type = trim($get_method['type']);
            $page_limit = 12;
            $limit=($get_method['page']-1) * $page_limit;

            if (checkBlockIp()) {
                $data = Sites::where('site_web',$domain)->first()
                    ->categories()
                    ->where('category_checked_ip',1)
                    ->get();
                $wallpaper = [];
                foreach ($data as $item ){
                    $item->wallpaper()->with('categories')->get()->toArray();
                    foreach ($item->wallpaper()->where('image_extension','<>','image/gif')->with('categories')->orderBy('updated_at', 'desc')->get()->toArray() as $wall){
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
                    $item->wallpaper()->with('categories')->get()->toArray();
                    foreach ($item->wallpaper()->where('image_extension','<>','image/gif')->with('categories')->orderBy('updated_at', 'desc')->get()->toArray() as $wall){
                        $wallpaper[] = $wall;
                    }
                }
            }
            $temp = array_unique(array_column($wallpaper, 'id'));
            $unique_arr = array_intersect_key($wallpaper, $temp);
            $result = array_slice($unique_arr, $limit, $page_limit);

            $row = $this->getWallpaper($result,$type,$get_method['android_id']);

            $set['HD_WALLPAPER'] = $row;
            header('Content-Type: application/json; charset=utf-8');
            echo $val = str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
        }
    }

    private function get_category($get_method)
    {
        $domain = $_SERVER['SERVER_NAME'];
        if ($get_method['type'] != '') {
            $type = trim($get_method['type']);
            if (checkBlockIp()) {
                $category =  Sites::where('site_web',$domain)->first()
                    ->categories()
                    ->where('category_checked_ip',1)
                    ->get();
            } else {
                $category = Sites::where('site_web',$domain)->first()
                    ->categories()
                    ->where('category_checked_ip',0)
//                    ->withCount('wallpaper')
                    ->get();
            }

            $row = $this->getCategory($category);

            $set['HD_WALLPAPER'] = $row;
            header('Content-Type: application/json; charset=utf-8');
            echo $val = str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
        }
    }

    private function get_wallpaper($get_method)
    {
        if ($get_method['type'] != '') {
            $type = trim($get_method['type']);
            $page_limit = 12;
            $limit=($get_method['page']-1) * $page_limit;
            $wallpaper = Categories::find($get_method['cat_id'])
                ->wallpaper()
                ->distinct()
                ->skip($limit)
                ->take($page_limit)
                ->get();

            $row = $this->getWallpaper($wallpaper,$type,$get_method['android_id']);
            $set['HD_WALLPAPER'] = $row;
            header('Content-Type: application/json; charset=utf-8');
            echo $val = str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
        }
    }

    private function get_single_wallpaper($get_method){
        $wallpaper = Wallpapers::find($get_method['wallpaper_id']);

        $row = $this->singleWallpaper($wallpaper, $get_method['android_id']);
        $wallpaper->wallpaper_view_count = $wallpaper->wallpaper_view_count + 1;
        $wallpaper->save();
        $set['HD_WALLPAPER'] = $row;
        header('Content-Type: application/json; charset=utf-8');
        echo $val = str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        die();

    }

    private function favorite_post($get_method){
        $jsonObj= array();
        $domain=$_SERVER['SERVER_NAME'];
        $visitorFavorite = VisitorFavorite::where(
            [
                'wallpaper_id' =>$get_method['post_id'],
                'visitor_id' => Visitors::where('device_id', $get_method['android_id'])->value('id'),
                'site_id' => Sites::where('site_web', $domain)->value('id')
            ])
            ->first();

        if ($visitorFavorite) {
            VisitorFavorite::where([
                'wallpaper_id' => $get_method['post_id'],
                'visitor_id' => Visitors::where('device_id', $get_method['android_id'])->value('id'),
                'site_id' => Sites::where('site_web', $domain)->value('id')
            ])->delete();
            $wallpaper = Wallpapers::where('id', $get_method['android_id'])->first();
            $wallpaper->decrement('wallpaper_like_count');
            $info['success']="1";
            $info['MSG']= 'favourite remove success';

        } else {
            VisitorFavorite::create([
                'wallpaper_id' => $get_method['post_id'],
                'visitor_id' => Visitors::where('device_id', $get_method['android_id'])->value('id'),
                'site_id' => Sites::where('site_web', $domain)->value('id')
            ])->first();
            $wallpaper = Wallpapers::where('id', $get_method['post_id'])->first();
            $wallpaper->increment('wallpaper_like_count');

            $info['success']="1";
            $info['MSG']='favourite success';
        }
        array_push($jsonObj,$info);
        $set['HD_WALLPAPER'] = $jsonObj;
        header( 'Content-Type: application/json; charset=utf-8' );
        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE));
        die();

    }

    private function get_favorite_post($get_method){
        $fav_type=$get_method['fav_type'];
        $page_limit = 12;
        $limit=($get_method['page']-1) * $page_limit;
        $type = trim($get_method['type']);
        $domain=$_SERVER['SERVER_NAME'];

        $data =  VisitorFavorite::where([
            'visitor_id' => Visitors::where('device_id',$get_method['android_id'])->value('id'),
            'site_id' => Sites::where('site_web', $domain)->value('id'),
        ])
            ->skip($limit)
            ->take($page_limit)
            ->get();
        $wallpaper = [];

        switch ($fav_type) {
            case 'wallpaper':
                {
                    foreach ($data as $item){
                        $item->wallpaper()->with('categories')->get()->toArray();
                        foreach ($item->wallpaper()->where('image_extension','<>','image/gif')->with('categories','tags')->orderBy('wallpaper_view_count', 'desc')->get()->toArray() as $wall){
                            $wallpaper[] = $wall;
                        }
                    }

                    $row = $this->getWallpaper($wallpaper,$type,$get_method['android_id']);
                }
                break;
            case 'gif':
            {
                foreach ($data as $item){
                    $item->wallpaper()->with('categories')->get()->toArray();
                    foreach ($item->wallpaper()->where('image_extension','image/gif')->with('categories','tags')->orderBy('wallpaper_view_count', 'desc')->get()->toArray() as $wall){
                        $wallpaper[] = $wall;
                    }
                }
                $row = $this->getWallpaperGif($wallpaper,$get_method['android_id']);
            }
            default:
                {
                }
                break;
        }

        $set['HD_WALLPAPER'] = $row;
        header('Content-Type: application/json; charset=utf-8');
        echo $val = str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        die();


    }

    private function get_wallpaper_most_viewed($get_method){
        $domain = $_SERVER['SERVER_NAME'];
        if ($get_method['type'] != '') {
            $type = trim($get_method['type']);
            $page_limit = 12;
            $limit = ($get_method['page'] - 1) * $page_limit;

            if (checkBlockIp()) {
                $data = Sites::where('site_web',$domain)->first()
                    ->categories()
                    ->where('category_checked_ip',1)
                    ->get();
                $wallpaper = [];
                foreach ($data as $item ){
                    $item->wallpaper()->with('categories')->get()->toArray();
                    foreach ($item->wallpaper()->where('image_extension','<>','image/gif')->with('categories')->orderBy('wallpaper_view_count', 'desc')->get()->toArray() as $wall){
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
                    $item->wallpaper()->with('categories')->get()->toArray();
                    foreach ($item->wallpaper()->where('image_extension','<>','image/gif')->with('categories')->orderBy('wallpaper_view_count', 'desc')->get()->toArray() as $wall){
                        $wallpaper[] = $wall;
                    }
                }
            }
            $temp = array_unique(array_column($wallpaper, 'id'));
            $unique_arr = array_intersect_key($wallpaper, $temp);
            $result = array_slice($unique_arr, $limit, $page_limit);



            $row = $this->getWallpaper($result, $type, $get_method['android_id']);
            $set['HD_WALLPAPER'] = $row;
            header('Content-Type: application/json; charset=utf-8');
            echo $val = str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
        }
    }

    private function get_wallpaper_most_rated($get_method){
        $domain = $_SERVER['SERVER_NAME'];
        if ($get_method['type'] != '') {
            $type = trim($get_method['type']);
            $page_limit = 12;
            $limit = ($get_method['page'] - 1) * $page_limit;
            if (checkBlockIp()) {
                $data = Sites::where('site_web',$domain)->first()
                    ->categories()
                    ->where('category_checked_ip',1)
                    ->get();
                $wallpaper = [];
                foreach ($data as $item ){
                    $item->wallpaper()->with('categories')->get()->toArray();
                    foreach ($item->wallpaper()->where('image_extension','<>','image/gif')->with('categories')->orderBy('wallpaper_like_count', 'desc')->get()->toArray() as $wall){
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
                    $item->wallpaper()->with('categories')->get()->toArray();
                    foreach ($item->wallpaper()->where('image_extension','<>','image/gif')->with('categories')->orderBy('wallpaper_like_count', 'desc')->get()->toArray() as $wall){
                        $wallpaper[] = $wall;
                    }
                }
            }
            $temp = array_unique(array_column($wallpaper, 'id'));
            $unique_arr = array_intersect_key($wallpaper, $temp);
            $result = array_slice($unique_arr, $limit, $page_limit);

            $row = $this->getWallpaper($result, $type, $get_method['android_id']);
            $set['HD_WALLPAPER'] = $row;
            header('Content-Type: application/json; charset=utf-8');
            echo $val = str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
        }
    }

    private function get_latest_gif($get_method){

        $domain = $_SERVER['SERVER_NAME'];
        $page_limit = 12;
        $limit = ($get_method['page'] - 1) * $page_limit;


        if (checkBlockIp()) {
            $data = Sites::where('site_web',$domain)->first()
                ->categories()
                ->where('category_checked_ip',1)
                ->get();
            $wallpaper = [];
            foreach ($data as $item ){
                $item->wallpaper()->with('categories')->get()->toArray();
                foreach ($item->wallpaper()->where('image_extension','image/gif')->with('categories','tags')->orderBy('wallpaper_like_count', 'desc')->get()->toArray() as $wall){
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
                $item->wallpaper()->with('categories')->get()->toArray();
                foreach ($item->wallpaper()->where('image_extension','image/gif')->with('categories','tags')->orderBy('wallpaper_like_count', 'desc')->get()->toArray() as $wall){
                    $wallpaper[] = $wall;
                }
            }
        }
        $temp = array_unique(array_column($wallpaper, 'id'));
        $unique_arr = array_intersect_key($wallpaper, $temp);
        $result = array_slice($unique_arr, $limit, $page_limit);
        $row = $this->getlatestgif($result, $get_method['android_id']);

//        $row = $this->getlatestgif($result);
        $set['HD_WALLPAPER'] = $row;
        header('Content-Type: application/json; charset=utf-8');
        echo $val = str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        die();

    }

    private function get_gif_list($get_method){
        dd(1);
        $domain = $_SERVER['SERVER_NAME'];
        $page_limit = 12;
        $limit = ($get_method['page'] - 1) * $page_limit;
        if (checkBlockIp()) {
            $wallpaper = Wallpapers::where('image_extension', 'image/gif')->with('category')->whereHas('category', function ($q) use ($domain) {
                $q->leftJoin('tbl_category_has_site', 'tbl_category_has_site.category_id', '=', 'tbl_category_manages.id')
                    ->leftJoin('tbl_site_manages', 'tbl_site_manages.id', '=', 'tbl_category_has_site.site_id')
                    ->where('site_name', $domain)
                    ->where('tbl_category_manages.checked_ip', 1)
                    ->select('tbl_category_manages.*');
            })
                ->orderBy('like_count', 'desc')
                ->limit($page_limit)
                ->offset($limit)
                ->get()->toArray();
        } else {
            $wallpaper = Wallpapers::where('image_extension', 'image/gif')->with('category')->whereHas('category', function ($q) use ($domain) {
                $q->leftJoin('tbl_category_has_site', 'tbl_category_has_site.category_id', '=', 'tbl_category_manages.id')
                    ->leftJoin('tbl_site_manages', 'tbl_site_manages.id', '=', 'tbl_category_has_site.site_id')
                    ->where('site_name', $domain)
                    ->where('tbl_category_manages.checked_ip', 0)
                    ->select('tbl_category_manages.*');
            })
                ->orderBy('like_count', 'desc')
                ->limit($page_limit)
                ->offset($limit)
                ->get()->toArray();
        }
        $row = $this->getlatestgif($wallpaper, $get_method['android_id']);
        $set['HD_WALLPAPER'] = $row;
        header('Content-Type: application/json; charset=utf-8');
        echo $val = str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        die();

    }

    private function get_single_gif($get_method){
        $wallpaper = Wallpapers::find($get_method['gif_id']);
        $row = $this->singleWallpaperGif($wallpaper, $get_method['android_id']);
        $wallpaper->wallpaper_view_count = $wallpaper->wallpaper_view_count + 1;
        $wallpaper->save();
        $set['HD_WALLPAPER'] = $row;
        header('Content-Type: application/json; charset=utf-8');
        echo $val = str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        die();

    }

    private function search_wallpaper($get_method){

        $domain = $_SERVER['SERVER_NAME'];
        if ($get_method['type'] != '') {
            $type = trim($get_method['type']);
            $page_limit = 12;
//            $limit = ($get_method['page'] - 1) * $page_limit;

            if (checkBlockIp()) {
                $data = Sites::where('site_web',$domain)->first()
                    ->categories()
                    ->where('category_checked_ip',1)
                    ->get();
                $wallpaper = [];
                foreach ($data as $item ){
                    $item->wallpaper()->with('categories')->get()->toArray();
                    foreach ($item->wallpaper()->where('image_extension','<>','image/gif')->where('wallpaper_name', 'like', '%' . $get_method['search_text'] . '%')->with('categories')->orderBy('wallpaper_like_count', 'desc')->get()->toArray() as $wall){
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
                    $item->wallpaper()->with('categories')->get()->toArray();
                    foreach ($item->wallpaper()->where('image_extension','<>','image/gif')->where('wallpaper_name', 'like', '%' . $get_method['search_text'] . '%')->with('categories')->orderBy('wallpaper_like_count', 'desc')->get()->toArray() as $wall){
                        $wallpaper[] = $wall;
                    }
                }
            }
            $row = $this->getWallpaper($wallpaper, $type, $get_method['android_id']);
            $set['HD_WALLPAPER'] = $row;
            header('Content-Type: application/json; charset=utf-8');
            echo $val = str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
        }

    }

    private function search_gif($get_method){
        $domain = $_SERVER['SERVER_NAME'];
        if (checkBlockIp()) {
            $data = Sites::where('site_web',$domain)->first()
                ->categories()
                ->where('category_checked_ip',1)
                ->get();
            $wallpaper = [];
            foreach ($data as $item ){
                $item->wallpaper()->with('categories')->get()->toArray();
                foreach ($item->wallpaper()->where('image_extension','image/gif')->where('wallpaper_name', 'like', '%' . $get_method['gif_search_text'] . '%')->with('categories')->orderBy('wallpaper_like_count', 'desc')->get()->toArray() as $wall){
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
                $item->wallpaper()->with('categories')->get()->toArray();
                foreach ($item->wallpaper()->where('image_extension','image/gif')->where('wallpaper_name', 'like', '%' . $get_method['gif_search_text'] . '%')->with('categories')->orderBy('wallpaper_like_count', 'desc')->get()->toArray() as $wall){
                    $wallpaper[] = $wall;
                }
            }
        }
        $temp = array_unique(array_column($wallpaper, 'id'));
        $unique_arr = array_intersect_key($wallpaper, $temp);

        $row = $this->getWallpaperGif($unique_arr, $get_method['android_id']);

        $set['HD_WALLPAPER'] = $row;
        header('Content-Type: application/json; charset=utf-8');
        echo $val = str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        die();
    }

    private function get_gif_wallpaper_most_viewed($get_method){
        $domain = $_SERVER['SERVER_NAME'];

        $page_limit = 12;
        $limit = ($get_method['page'] - 1) * $page_limit;

        if (checkBlockIp()) {
            $data = Sites::where('site_web',$domain)->first()
                ->categories()
                ->where('category_checked_ip',1)
                ->get();
            $wallpaper = [];
            foreach ($data as $item ){
                $item->wallpaper()->with('categories')->get()->toArray();
                foreach ($item->wallpaper()->where('image_extension','image/gif')->with('categories','tags')->orderBy('wallpaper_view_count', 'desc')->get()->toArray() as $wall){
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
                $item->wallpaper()->with('categories')->get()->toArray();
                foreach ($item->wallpaper()->where('image_extension','image/gif')->with('categories','tags')->orderBy('wallpaper_view_count', 'desc')->get()->toArray() as $wall){
                    $wallpaper[] = $wall;
                }
            }
        }

        $temp = array_unique(array_column($wallpaper, 'id'));
        $unique_arr = array_intersect_key($wallpaper, $temp);
        $result = array_slice($unique_arr, $limit, $page_limit);
        $row = $this->getWallpaperGif($result, $get_method['android_id']);

        $set['HD_WALLPAPER'] = $row;
        header('Content-Type: application/json; charset=utf-8');
        echo $val = str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        die();

    }

    private function get_gif_wallpaper_most_rated($get_method){
        $domain = $_SERVER['SERVER_NAME'];
        $page_limit = 12;
        $limit = ($get_method['page'] - 1) * $page_limit;

        if (checkBlockIp()) {
            $data = Sites::where('site_web',$domain)->first()
                ->categories()
                ->where('category_checked_ip',1)
                ->get();
            $wallpaper = [];
            foreach ($data as $item ){
                $item->wallpaper()->with('categories')->get()->toArray();
                foreach ($item->wallpaper()->where('image_extension','image/gif')->with('categories','tags')->orderBy('wallpaper_like_count', 'desc')->get()->toArray() as $wall){
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
                $item->wallpaper()->with('categories')->get()->toArray();
                foreach ($item->wallpaper()->where('image_extension','image/gif')->with('categories','tags')->orderBy('wallpaper_like_count', 'desc')->get()->toArray() as $wall){
                    $wallpaper[] = $wall;
                }
            }
        }

        $temp = array_unique(array_column($wallpaper, 'id'));
        $unique_arr = array_intersect_key($wallpaper, $temp);
        $result = array_slice($unique_arr, $limit, $page_limit);
        $row = $this->getWallpaperGif($result, $get_method['android_id']);

        $set['HD_WALLPAPER'] = $row;
        header('Content-Type: application/json; charset=utf-8');
        echo $val = str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        die();

    }

    private function get_app_details($get_method){
        $jsonObj= array();
        $domain = $_SERVER['SERVER_NAME'];
        $data = Sites::where('site_web',$domain)
            ->first()->toArray();

        $ads = json_decode($data['site_ads'], true);
//
//        $type=explode(',', 'Portrait,Landscape,Square');
        $type=explode(',', 'Portrait');

        $row['ios_bundle_identifier'] =  'com.viavi.hdwallpapers' ;
        $row['package_name'] = 'com.vpapps.hdwallpaper';
//        $row['app_name'] = 'HD Wallpaper App' ;
        $row['app_name'] = $data['site_name'] ? $data['site_name'] : '' ;
        $row['app_logo'] = 'Icon144.png' ;
        $row['app_version'] =  '1.0.0';
        $row['app_author'] =  "vietmmo" ;
        $row['app_contact'] = '+84 9227777522' ;
        $row['app_email'] =  'info@vietmmo.net';
        $row['app_website'] = $domain;
        $row['app_description'] =  '<p><strong>&ldquo;HD Wallpaper&rdquo;</strong> is a cool new app that brings all the best HD wallpapers and backgrounds to your Android device.</p>\r\n\r\n<p>Each high resolution image has been perfectly formatted fit to the phone display and comes with a host of user friendly features. The stunning UI allows you easily tap and swipe your way through the multiple image galleries. To develop similar app with your name you can contact us via skype or whatsapp.<br />\r\n<br />\r\n<strong>Skype:</strong> viaviwebtech<br />\r\n<strong>WhatsApp:</strong> +919227777522</p>\r\n';
        $row['app_developed_by'] = $domain;

        $row['app_privacy_policy'] =  "<p><strong>We are committed to protecting your privac&nbsp;</strong></p>\r\n\r\n<p>We collect the minimum amount of information about you that is commensurate with providing you with a satisfactory service. This policy indicates the type of processes that may result in data being collected about you. Your use of this website gives us the right to collect that information.&nbsp;</p>\r\n\r\n<p><strong>Information Collected</strong></p>\r\n\r\n<p>We may collect any or all of the information that you give us depending on the type of transaction you enter into, including your name, address, telephone number, and email address, together with data about your use of the website. Other information that may be needed from time to time to process a request may also be collected as indicated on the website.</p>\r\n\r\n<p><strong>Information Use</strong></p>\r\n\r\n<p>We use the information collected primarily to process the task for which you visited the website. Data collected in the UK is held in accordance with the Data Protection Act. All reasonable precautions are taken to prevent unauthorised access to this information. This safeguard may require you to provide additional forms of identity should you wish to obtain information about your account details.</p>\r\n\r\n<p><strong>Cookies</strong></p>\r\n\r\n<p>Your Internet browser has the in-built facility for storing small files - &quot;cookies&quot; - that hold information which allows a website to recognise your account. Our website takes advantage of this facility to enhance your experience. You have the ability to prevent your computer from accepting cookies but, if you do, certain functionality on the website may be impaired.</p>\r\n\r\n<p><strong>Disclosing Information</strong></p>\r\n\r\n<p>We do not disclose any personal information obtained about you from this website to third parties unless you permit us to do so by ticking the relevant boxes in registration or competition forms. We may also use the information to keep in contact with you and inform you of developments associated with us. You will be given the opportunity to remove yourself from any mailing list or similar device. If at any time in the future we should wish to disclose information collected on this website to any third party, it would only be with your knowledge and consent.&nbsp;</p>\r\n\r\n<p>We may from time to time provide information of a general nature to third parties - for example, the number of individuals visiting our website or completing a registration form, but we will not use any information that could identify those individuals.&nbsp;</p>\r\n\r\n<p>In addition Dummy may work with third parties for the purpose of delivering targeted behavioural advertising to the Dummy website. Through the use of cookies, anonymous information about your use of our websites and other websites will be used to provide more relevant adverts about goods and services of interest to you. For more information on online behavioural advertising and about how to turn this feature off, please visit youronlinechoices.com/opt-out.</p>\r\n\r\n<p><strong>Changes to this Policy</strong></p>\r\n\r\n<p>Any changes to our Privacy Policy will be placed here and will supersede this version of our policy. We will take reasonable steps to draw your attention to any changes in our policy. However, to be on the safe side, we suggest that you read this document each time you use the website to ensure that it still meets with your approval.</p>\r\n\r\n<p><strong>Contacting Us</strong></p>\r\n\r\n<p>If you have any questions about our Privacy Policy, or if you want to know what information we have collected about you, please email us at hd@dummy.com. You can also correct any factual errors in that information or require us to remove your details form any list under our control.</p>\r\n" ;

        $row['publisher_id'] = $ads ? ( $ads['AdMob_Publisher_ID'] ?  $ads['AdMob_Publisher_ID'] : '') : '';

        $row['interstital_ad'] = $data['ad_switch'] != 0 ? 'true':'false' ;
        $row['interstital_ad_id'] = $ads ? ( $ads['AdMob_Interstitial_Ad_Unit_ID'] ?  $ads['AdMob_Interstitial_Ad_Unit_ID'] : '' ) : '';

        $row['interstital_ad_click'] = '12';
        $row['banner_ad'] = $data['ad_switch'] != 0 ? 'true':'false' ;
        $row['banner_ad_id'] = $ads ? ( $ads['AdMob_Banner_Ad_Unit_ID'] ? $ads['AdMob_Banner_Ad_Unit_ID'] :"" ):  "";


        $row['facebook_interstital_ad'] = $data['ad_switch'] != 0 ? 'true':'false';
        $row['facebook_interstital_ad_id'] = '1393008281089270_1393009821089116';
        $row['facebook_interstital_ad_click'] = '5';
        $row['facebook_banner_ad'] = 'false';
        $row['facebook_banner_ad_id'] = '1393008281089270_1393010137755751';

        $row['facebook_native_ad'] = $data['ad_switch'] != 0 ? 'true':'false';
        $row['facebook_native_ad_id'] = '1393008281089270_1393009201089178';
        $row['facebook_native_ad_click'] = '12';
        $row['admob_nathive_ad'] = $data['ad_switch'] != 0 ? 'true':'false' ;
        $row['admob_native_ad_id'] = $ads ? ( $ads['AdMob_Native_Ad_Unit_ID'] ?  $ads['AdMob_Native_Ad_Unit_ID'] : "") :"";
        $row['admob_native_ad_click'] = 12;

        $row['publisher_id_ios'] = '';
        $row['interstital_ad_ios'] = $data['ad_switch'] != 0 ? 'true':'false';
        $row['interstital_ad_id_ios'] = '';
        $row['interstital_ad_click_ios'] = '5';
        $row['banner_ad_ios'] = $data['ad_switch'] != 0 ? 'true':'false';
        $row['banner_ad_id_ios'] = '';

        $row['gif_on_off'] = 'true';

        if(in_array('Portrait',$type) || empty($type)){
            $row['portrait'] = 'true';
        }else{
            $row['portrait'] = 'false';
        }

        if(in_array('Landscape',$type)){
            $row['landscape'] = 'true';
        }else{
            $row['landscape'] = 'false';
        }

        if(in_array('Square',$type)){
            $row['square'] = 'true';
        }else{
            $row['square'] = 'false';
        }

        $row['app_update_status'] = 'false';
        $row['app_new_version'] = '';
        $row['app_update_desc'] = '';
        $row['app_redirect_url'] = '';
        $row['cancel_update_status'] = 'false';

        array_push($jsonObj, $row);

        $set['HD_WALLPAPER'] = $jsonObj;

        header( 'Content-Type: application/json; charset=utf-8' );
        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        die();
    }




    //==============================================================

    function cleanInput($inputText)
    {
        return htmlentities(addslashes(trim($inputText)));
    }

    private  function sortWallpaper($data, $sort, $type,$android_id){
        $jsonObj = [];
        if ($sort){
            usort($data, function($a, $b) use ($sort) {
                return $b[$sort] <=> $a[$sort];
            });
        }else{
            shuffle($data);
        }
        $output = array_slice($data, 0, 12);
//        $output = array_slice($data, $limit, $page_limit);
        foreach ($output as $item){
            $tags = [];
            foreach ($item['tags'] as $tag){

                $tags[] = $tag['tag_name'];
            }

            $data_arr['id'] = $item['id'];
            $data_arr['cat_id'] = '';
            $data_arr['wallpaper_type'] = $type;
            $data_arr['wallpaper_image'] = asset('storage/wallpapers/'.$item['wallpaper_image']);
            $data_arr['wallpaper_image_thumb'] = asset('storage/wallpapers/thumbnails/'.$item['wallpaper_image']);
            $data_arr['total_views'] = $item['wallpaper_view_count'];
            $data_arr['total_rate'] = $item['wallpaper_like_count'];
            $data_arr['rate_avg'] = $item['wallpaper_download_count'];

            $data_arr['is_favorite']= $this->is_favorite($item['id'], 'wallpaper', $android_id);

            $data_arr['wall_tags'] = implode(",", $tags);
            $data_arr['wall_colors'] = 1;
            $data_arr['cid'] = '';
            $data_arr['category_name'] = $item['wallpaper_name'];
            $data_arr['category_image'] =   '';
            $data_arr['category_image_thumb'] ='';
            array_push($jsonObj,$data_arr);
        }
        return $jsonObj;
    }

    private  function getWallpaper($data,$type,$android_id){
        $jsonObj = [];
        foreach ($data as $item){
            $category = isset($item['pivot']) ? Categories::find($item['pivot']['category_id']) : null;
            $data_arr['num'] = count($data);
            $data_arr['id'] = $item['id'];
            $data_arr['cat_id'] = isset($item['pivot']) ? $item['pivot']['category_id'] : '';
            $data_arr['wallpaper_type'] = $type ;
            $data_arr['wallpaper_image'] = asset('storage/wallpapers/' . $item['wallpaper_image']);
            $data_arr['wallpaper_image_thumb'] = asset('storage/wallpapers/thumbnails/' . $item['wallpaper_image']);
            $data_arr['total_views'] = $item['wallpaper_view_count'];
            $data_arr['total_rate'] = $item['wallpaper_like_count'];
            $data_arr['rate_avg'] = $item['wallpaper_like_count'];

            $data_arr['is_favorite']= $this->is_favorite($item['id'], 'wallpaper', $android_id);

            $data_arr['wall_tags'] = $item['wallpaper_name'];
            $data_arr['wall_colors'] = 1;

            $data_arr['cid'] = isset($category) ? $category->id : '';
            $data_arr['category_name'] = $item['wallpaper_name'];
            $data_arr['category_image'] = isset($category)  ? asset('storage/categories/'.$category->category_image) : '';
            $data_arr['category_image_thumb'] =  isset($category) ? asset('storage/categories/'.$category->category_image) : '';
            array_push($jsonObj,$data_arr);
        }
        return $jsonObj;
    }

    private  function getWallpaperGif($data,$android_id){

        $jsonObj = [];
        if (count($data) > 0){
            foreach ($data as $item){
                $tags = [];
                foreach ($item['tags'] as $tag){

                    $tags[] = $tag['tag_name'];
                }
                $data_arr['num'] = count($data);
                $data_arr['id'] = $item['id'];
                $data_arr['gif_image'] = asset('storage/wallpapers/' . $item['wallpaper_image']);
                $data_arr['gif_tags'] = $item['wallpaper_name'];
                $data_arr['total_views'] = $item['wallpaper_view_count'];
                $data_arr['total_rate'] = $item['wallpaper_like_count'];
                $data_arr['rate_avg'] = $item['wallpaper_download_count'];
                $data_arr['is_favorite']= $this->is_favorite($item['id'], 'wallpaper', $android_id);
                array_push($jsonObj,$data_arr);
            }
        }
        else{
            $data_arr['num'] = '';
            $data_arr['id'] ='';
            $data_arr['gif_image'] = '';
            $data_arr['gif_tags'] = '';
            $data_arr['total_views'] = '';
            $data_arr['total_rate'] = '';
            $data_arr['rate_avg'] = '';
            $data_arr['is_favorite']= '';
            array_push($jsonObj,$data_arr);
        }
        return $jsonObj;
    }

    private  function getCategory($data){
        $jsonObj = [];
        foreach ($data as $item){
            $data_arr['cid'] = $item['id'];
            $data_arr['category_name'] = $item['category_name'];
            $data_arr['category_image'] = asset('storage/categories/'.$item->category_image);
            $data_arr['category_image_thumb'] = asset('storage/categories/' . $item->category_image);
            $data_arr['category_total_wall'] = $item->wallpaper()->distinct()->get()->count();
            array_push($jsonObj,$data_arr);
        }
        return $jsonObj;
    }

    private  function singleWallpaper($data, $android_id){
        $path = storage_path('app/public/wallpapers/'.$data->wallpaper_image);
        $image = $size = '';
        if (file_exists($path)){
            $image = getimagesize($path);
            $size = $this->filesize_formatted($path);
        }

        $jsonObj = [];



        $data_arr['id'] = (string)$data->id;
        $data_arr['wallpaper_type'] = '' ;
        $data_arr['wallpaper_image'] = asset('storage/wallpapers/' . $data['wallpaper_image']);
        $data_arr['wallpaper_image_thumb'] = asset('storage/wallpapers/thumbnails/' . $data['wallpaper_image']);



        $data_arr['total_views'] = (string)$data['wallpaper_view_count'];
        $data_arr['total_rate'] = (string)$data['wallpaper_like_count'];
        $data_arr['rate_avg'] = (string)$data['wallpaper_download_count'];
        $data_arr['is_favorite']= $this->is_favorite($data['id'], 'wallpaper', $android_id);;
        $data_arr['total_download'] = (string)$data['wallpaper_download_count'];


//        $data_arr['wall_tags'] = $data['categories']['category_name'].','.$data->name ;
        $data_arr['wall_colors'] = "2";
        $data_arr['resolution'] = $image ?  $image[0]. ' x '.$image[1]: 'n/a';
        $data_arr['size'] = $size ? $size : 'n/a';
        array_push($jsonObj,$data_arr);

        return $jsonObj;
    }

    private  function singleWallpaperGif($data, $android_id){
        $path = storage_path('app/public/wallpapers/'.$data->wallpaper_image);
        $image = $size = '';
        if (file_exists($path)){
            $image = getimagesize($path);
            $size = $this->filesize_formatted($path);
        }
        $jsonObj = [];

        $data_arr['id'] = (string)$data->id;
        $data_arr['gif_image'] = asset('storage/wallpapers/' . $data['wallpaper_image']);
        $data_arr['gif_tags'] = $data['wallpaper_name'];
        $data_arr['total_views'] = $data['view_count'];
        $data_arr['total_rate'] = $data['like_count'];
        $data_arr['rate_avg'] = $data['like_count'];
        $data_arr['is_favorite']= $this->is_favorite($data['id'], 'wallpaper', $android_id);
        $data_arr['total_download'] = $data['total_download'];
        $data_arr['resolution'] = $image ?  $image[0]. ' x '.$image[1]: 'n/a';
        $data_arr['size'] = $size ? $size : 'n/a';

        array_push($jsonObj,$data_arr);
        return $jsonObj;
    }
    private  function getlatestgif($data,$android_id){
        $jsonObj = [];
        if (count($data) > 0){
            foreach ($data as $item){
                $tags = [];
                foreach ($item['tags'] as $tag){

                    $tags[] = $tag['tag_name'];
                }

                $data_arr['num'] = count($data);
                $data_arr['id'] = $item['id'];
                $data_arr['gif_image'] = asset('storage/wallpapers/' . $item['wallpaper_image']);
                $data_arr['gif_tags'] = implode(",", $tags);
                $data_arr['total_views'] = $item['wallpaper_view_count'];
                $data_arr['total_rate'] = $item['wallpaper_like_count'];
                $data_arr['rate_avg'] = $item['wallpaper_download_count'];
                $data_arr['is_favorite']= $this->is_favorite($item['id'], 'wallpaper', $android_id);
                array_push($jsonObj,$data_arr);
            }
        }
        else{
            $data_arr['num'] = '';
            $data_arr['id'] ='';
            $data_arr['gif_image'] = '';
            $data_arr['gif_tags'] = '';
            $data_arr['total_views'] = '';
            $data_arr['total_rate'] = '';
            $data_arr['rate_avg'] = '';
            $data_arr['is_favorite']= '';
            array_push($jsonObj,$data_arr);
        }
        return $jsonObj;
    }

    function is_favorite($id,$type='wallpaper',$android_id='')
    {
        $visitorFavorite = VisitorFavorite::where
        ([
            'wallpaper_id' => $id,
            'visitor_id' => Visitors::where('device_id', $android_id)->value('id')
        ])
            ->first();

        if ($visitorFavorite) {
            return true;
        } else {
            return false;
        }
    }

    function filesize_formatted($path)
    {
        $size = filesize($path);
        $units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $power = $size > 0 ? floor(log($size, 1024)) : 0;
        return number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
    }
}
