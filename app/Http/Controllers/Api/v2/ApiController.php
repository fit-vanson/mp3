<?php

namespace App\Http\Controllers\Api\v2;

use App\Categories;
use App\Http\Controllers\Controller;
use App\Http\Resources\v2\CategoriesResource;
use App\Http\Resources\v2\WallpaperResource;
use App\ListIP;
use App\Sites;
use App\VisitorFavorite;
use App\Visitors;
use App\Wallpapers;
use Dflydev\DotAccessData\Data;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isEmpty;

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
        $row['featured_wallpaper'] =  $this->sortWallpaper($get_method['android_id'],'wallpaper_like_count');
        $row['wallpaper_category'] = $this->getCategories();
        $row['latest_wallpaper'] = $this->sortWallpaper($get_method['android_id'],'updated_at');
        $row['popular_wallpaper'] = $this->sortWallpaper($get_method['android_id'],'wallpaper_view_count');
        $row['recent_wallpapers'] = $this->sortWallpaper($get_method['android_id'],'created_at' );
        $set['HD_WALLPAPER'] = $row;
        header('Content-Type: application/json; charset=utf-8');
        echo $val = str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        die();
//        }
    }

    private function get_latest($get_method)
    {
        $domain = $_SERVER['SERVER_NAME'];
        $site = Sites::where('site_web',$domain)->first();
        if ($get_method['type'] != '') {
            $page_limit = 12;
            $limit=($get_method['page']-1) * $page_limit;

            if (checkBlockIp()) {
                $data = Wallpapers::with('tags')
                    ->where('image_extension', '<>','image/gif')
                    ->whereHas('categories', function ($query) use ($site) {
                        $query->where('category_checked_ip', 1)
                            ->where('site_id',$site->id);
                    })
                    ->distinct()
                    ->orderBy('updated_at','desc')
                    ->skip($limit)
                    ->take($page_limit)
                    ->get();
            } else {
                $data = Wallpapers::with('tags')
                    ->where('image_extension', '<>','image/gif')
                    ->whereHas('categories', function ($query) use ($site) {
                        $query->where('category_checked_ip', 0)
                            ->where('site_id',$site->id);
                    })
                    ->distinct()
                    ->orderBy('updated_at','desc')
                    ->skip($limit)
                    ->take($page_limit)
                    ->get();
            }
            $row = $this->getWallpaper($data,$get_method['android_id']);
            $set['HD_WALLPAPER'] = $row;
            header('Content-Type: application/json; charset=utf-8');
            echo $val = str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
        }
    }

    private function get_recent_post($get_method)
    {
        $domain = $_SERVER['SERVER_NAME'];
        $site = Sites::where('site_web',$domain)->first();
        if ($get_method['type'] != '') {
            $page_limit = 12;
            $limit=($get_method['page']-1) * $page_limit;

            if (checkBlockIp()) {
                $data = Wallpapers::with('tags')
                    ->where('image_extension', '<>','image/gif')
                    ->whereHas('categories', function ($query) use ($site) {
                        $query->where('category_checked_ip', 1)
                            ->where('site_id',$site->id);
                    })
                    ->distinct()
                    ->orderBy('created_at','desc')
                    ->skip($limit)
                    ->take($page_limit)
                    ->get();
            } else {
                $data = Wallpapers::with('tags')
                    ->where('image_extension', '<>','image/gif')
                    ->whereHas('categories', function ($query) use ($site) {
                        $query->where('category_checked_ip', 0)
                            ->where('site_id',$site->id);
                    })
                    ->distinct()
                    ->orderBy('created_at','desc')
                    ->skip($limit)
                    ->take($page_limit)
                    ->get();
            }
            $row = $this->getWallpaper($data,$get_method['android_id']);
            $set['HD_WALLPAPER'] = $row;
            header('Content-Type: application/json; charset=utf-8');
            echo $val = str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
        }
    }

    private function get_category()
    {
        $row = $this->getCategories();
        $set['HD_WALLPAPER'] = $row;
        header('Content-Type: application/json; charset=utf-8');
        echo $val = str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        die();

    }

    private function get_wallpaper($get_method)
    {
        if ($get_method['type'] != '') {
            $type = trim($get_method['type']);
            $page_limit = 12;
            $limit=($get_method['page']-1) * $page_limit;
            $wallpaper = Categories::find($get_method['cat_id'])
                ->wallpaper()
                ->where('image_extension','<>','image/gif')
                ->distinct()
                ->skip($limit)
                ->take($page_limit)
                ->inRandomOrder()
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
        $site = Sites::where('site_web',$domain)->first();
        if ($get_method['type'] != '') {
            $page_limit = 12;
            $limit=($get_method['page']-1) * $page_limit;

            if (checkBlockIp()) {
                $data = Wallpapers::with('tags')
                    ->where('image_extension', '<>','image/gif')
                    ->whereHas('categories', function ($query) use ($site) {
                        $query->where('category_checked_ip', 1)
                            ->where('site_id',$site->id);
                    })
                    ->distinct()
                    ->orderBy('wallpaper_view_count','desc')
                    ->skip($limit)
                    ->take($page_limit)
                    ->get();
            } else {
                $data = Wallpapers::with('tags')
                    ->where('image_extension', '<>','image/gif')
                    ->whereHas('categories', function ($query) use ($site) {
                        $query->where('category_checked_ip', 0)
                            ->where('site_id',$site->id);
                    })
                    ->distinct()
                    ->orderBy('wallpaper_view_count','desc')
                    ->skip($limit)
                    ->take($page_limit)
                    ->get();
            }
            $row = $this->getWallpaper($data,$get_method['android_id']);
            $set['HD_WALLPAPER'] = $row;
            header('Content-Type: application/json; charset=utf-8');
            echo $val = str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
        }

    }

    private function get_wallpaper_most_rated($get_method){


        $domain = $_SERVER['SERVER_NAME'];
        $site = Sites::where('site_web',$domain)->first();
        if ($get_method['type'] != '') {
            $page_limit = 12;
            $limit=($get_method['page']-1) * $page_limit;

            if (checkBlockIp()) {
                $data = Wallpapers::with('tags')
                    ->where('image_extension', '<>','image/gif')
                    ->whereHas('categories', function ($query) use ($site) {
                        $query->where('category_checked_ip', 1)
                            ->where('site_id',$site->id);
                    })
                    ->distinct()
                    ->orderBy('wallpaper_like_count','desc')
                    ->skip($limit)
                    ->take($page_limit)
                    ->get();
            } else {
                $data = Wallpapers::with('tags')
                    ->where('image_extension', '<>','image/gif')
                    ->whereHas('categories', function ($query) use ($site) {
                        $query->where('category_checked_ip', 0)
                            ->where('site_id',$site->id);
                    })
                    ->distinct()
                    ->orderBy('wallpaper_like_count','desc')
                    ->skip($limit)
                    ->take($page_limit)
                    ->get();
            }
            $row = $this->getWallpaper($data,$get_method['android_id']);
            $set['HD_WALLPAPER'] = $row;
            header('Content-Type: application/json; charset=utf-8');
            echo $val = str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
        }

    }

    private function get_latest_gif($get_method){
        $domain = $_SERVER['SERVER_NAME'];
        $site = Sites::where('site_web',$domain)->first();
        if ($get_method['type'] != '') {
            $page_limit = 12;
            $limit=($get_method['page']-1) * $page_limit;

            if (checkBlockIp()) {
                $data = Wallpapers::with('tags')
                    ->where('image_extension','image/gif')
                    ->whereHas('categories', function ($query) use ($site) {
                        $query->where('category_checked_ip', 1)
                            ->where('site_id',$site->id);
                    })
                    ->distinct()
                    ->orderBy('created_at','desc')
                    ->skip($limit)
                    ->take($page_limit)
                    ->get();
            } else {
                $data = Wallpapers::with('tags')
                    ->where('image_extension','image/gif')
                    ->whereHas('categories', function ($query) use ($site) {
                        $query->where('category_checked_ip', 0)
                            ->where('site_id',$site->id);
                    })
                    ->distinct()
                    ->orderBy('created_at','desc')
                    ->skip($limit)
                    ->take($page_limit)
                    ->get();
            }
            $row = $this->getWallpaperGif($data,$get_method['android_id']);
            $set['HD_WALLPAPER'] = $row;
            header('Content-Type: application/json; charset=utf-8');
            echo $val = str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
        }
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
        $site = Sites::where('site_web',$domain)->first();
        if ($get_method['type'] != '') {
            $page_limit = 12;
            $limit=($get_method['page']-1) * $page_limit;

            if (checkBlockIp()) {
                $data = Wallpapers::with('tags')
                    ->where('image_extension', '<>','image/gif')
                    ->where('wallpaper_name', 'like', '%' . $get_method['search_text'] . '%')
                    ->whereHas('categories', function ($query) use ($site) {
                        $query->where('category_checked_ip', 1)
                            ->where('site_id',$site->id);
                    })
                    ->distinct()
                    ->orderBy('wallpaper_view_count','desc')
                    ->skip($limit)
                    ->take($page_limit)
                    ->get();
            } else {
                $data = Wallpapers::with('tags')
                    ->where('image_extension', '<>','image/gif')
                    ->where('wallpaper_name', 'like', '%' . $get_method['search_text'] . '%')
                    ->whereHas('categories', function ($query) use ($site) {
                        $query->where('category_checked_ip', 0)
                            ->where('site_id',$site->id);
                    })
                    ->distinct()
                    ->orderBy('wallpaper_view_count','desc')
                    ->skip($limit)
                    ->take($page_limit)
                    ->get();
            }
            $row = $this->getWallpaper($data,$get_method['android_id']);
            $set['HD_WALLPAPER'] = $row;
            header('Content-Type: application/json; charset=utf-8');
            echo $val = str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
        }

    }

    private function search_gif($get_method){

        $domain = $_SERVER['SERVER_NAME'];
        $site = Sites::where('site_web',$domain)->first();
        if ($get_method['type'] != '') {
            $page_limit = 12;
            $limit=($get_method['page']-1) * $page_limit;

            if (checkBlockIp()) {
                $data = Wallpapers::with('tags')
                    ->where('image_extension','image/gif')
                    ->where('wallpaper_name', 'like', '%' . $get_method['gif_search_text'] . '%')
                    ->whereHas('categories', function ($query) use ($site) {
                        $query->where('category_checked_ip', 1)
                            ->where('site_id',$site->id);
                    })
                    ->distinct()
                    ->orderBy('created_at','desc')
                    ->skip($limit)
                    ->take($page_limit)
                    ->get();
            } else {
                $data = Wallpapers::with('tags')
                    ->where('image_extension','image/gif')
                    ->where('wallpaper_name', 'like', '%' . $get_method['gif_search_text'] . '%')
                    ->whereHas('categories', function ($query) use ($site) {
                        $query->where('category_checked_ip', 0)
                            ->where('site_id',$site->id);
                    })
                    ->distinct()
                    ->orderBy('created_at','desc')
                    ->skip($limit)
                    ->take($page_limit)
                    ->get();
            }
            $row = $this->getWallpaperGif($data,$get_method['android_id']);
            $set['HD_WALLPAPER'] = $row;
            header('Content-Type: application/json; charset=utf-8');
            echo $val = str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
        }

    }

    private function get_gif_wallpaper_most_viewed($get_method){


        $domain = $_SERVER['SERVER_NAME'];
        $site = Sites::where('site_web',$domain)->first();
        if ($get_method['type'] != '') {
            $page_limit = 12;
            $limit=($get_method['page']-1) * $page_limit;

            if (checkBlockIp()) {
                $data = Wallpapers::with('tags')
                    ->where('image_extension','image/gif')
                    ->whereHas('categories', function ($query) use ($site) {
                        $query->where('category_checked_ip', 1)
                            ->where('site_id',$site->id);
                    })
                    ->distinct()
                    ->orderBy('wallpaper_view_count','desc')
                    ->skip($limit)
                    ->take($page_limit)
                    ->get();
            } else {
                $data = Wallpapers::with('tags')
                    ->where('image_extension','image/gif')
                    ->whereHas('categories', function ($query) use ($site) {
                        $query->where('category_checked_ip', 0)
                            ->where('site_id',$site->id);
                    })
                    ->distinct()
                    ->orderBy('wallpaper_view_count','desc')
                    ->skip($limit)
                    ->take($page_limit)
                    ->get();
            }
            $row = $this->getWallpaperGif($data,$get_method['android_id']);
            $set['HD_WALLPAPER'] = $row;
            header('Content-Type: application/json; charset=utf-8');
            echo $val = str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
        }

    }

    private function get_gif_wallpaper_most_rated($get_method){


        $domain = $_SERVER['SERVER_NAME'];
        $site = Sites::where('site_web',$domain)->first();
        if ($get_method['type'] != '') {
            $page_limit = 12;
            $limit=($get_method['page']-1) * $page_limit;

            if (checkBlockIp()) {
                $data = Wallpapers::with('tags')
                    ->where('image_extension','image/gif')
                    ->whereHas('categories', function ($query) use ($site) {
                        $query->where('category_checked_ip', 1)
                            ->where('site_id',$site->id);
                    })
                    ->distinct()
                    ->orderBy('wallpaper_like_count','desc')
                    ->skip($limit)
                    ->take($page_limit)
                    ->get();
            } else {
                $data = Wallpapers::with('tags')
                    ->where('image_extension','image/gif')
                    ->whereHas('categories', function ($query) use ($site) {
                        $query->where('category_checked_ip', 0)
                            ->where('site_id',$site->id);
                    })
                    ->distinct()
                    ->orderBy('wallpaper_like_count','desc')
                    ->skip($limit)
                    ->take($page_limit)
                    ->get();
            }
            $row = $this->getWallpaperGif($data,$get_method['android_id']);
            $set['HD_WALLPAPER'] = $row;
            header('Content-Type: application/json; charset=utf-8');
            echo $val = str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
        }

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
        $row['app_description'] = $data['site_header_title'] ;
        $row['app_developed_by'] = $domain;

        $row['app_privacy_policy'] =  $data['site_policy'];
        $row['publisher_id'] = $ads ? ( $ads['AdMob_Publisher_ID'] ?  $ads['AdMob_Publisher_ID'] : '') : '';

        $row['interstital_ad'] = $data['ad_switch'] == 1 ? 'true':'false' ;
        $row['interstital_ad_id'] = $ads ? ( $ads['AdMob_Interstitial_Ad_Unit_ID'] ?  $ads['AdMob_Interstitial_Ad_Unit_ID'] : '' ) : '';

        $row['interstital_ad_click'] = '12';
        $row['banner_ad'] = $data['ad_switch'] == 1 ? 'true':'false' ;
        $row['banner_ad_id'] = $ads ? ( $ads['AdMob_Banner_Ad_Unit_ID'] ? $ads['AdMob_Banner_Ad_Unit_ID'] :"" ):  "";


        $row['facebook_interstital_ad'] = $data['ad_switch'] == 1 ? 'true':'false';
        $row['facebook_interstital_ad_id'] = '1393008281089270_1393009821089116';
        $row['facebook_interstital_ad_click'] = '5';
        $row['facebook_banner_ad'] = 'false';
        $row['facebook_banner_ad_id'] = '1393008281089270_1393010137755751';

        $row['facebook_native_ad'] = $data['ad_switch'] == 1 ? 'true':'false';
        $row['facebook_native_ad_id'] = '1393008281089270_1393009201089178';
        $row['facebook_native_ad_click'] = '12';
        $row['admob_nathive_ad'] = $data['ad_switch'] == 1 ? 'true':'false' ;
        $row['admob_native_ad_id'] = $ads ? ( $ads['AdMob_Native_Ad_Unit_ID'] ?  $ads['AdMob_Native_Ad_Unit_ID'] : "") :"";
        $row['admob_native_ad_click'] = 12;

        $row['publisher_id_ios'] = '';
        $row['interstital_ad_ios'] = $data['ad_switch'] == 1 ? 'true':'false';
        $row['interstital_ad_id_ios'] = '';
        $row['interstital_ad_click_ios'] = '5';
        $row['banner_ad_ios'] = $data['ad_switch'] == 1 ? 'true':'false';
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

    private  function sortWallpaper($android_id,$sort){
        $domain=$_SERVER['SERVER_NAME'];
        $site = Sites::where('site_web',$domain)->first();
        $jsonObj = [];
        if (checkBlockIp()) {
            if (!$sort) {
                $data = Wallpapers::with('tags')
                    ->where('image_extension', '<>', 'image/gif')
                    ->whereHas('categories', function ($query) use ($site) {
                        $query->where('category_checked_ip', 1)
                            ->where('site_id', $site->id);
                    })
                    ->inRandomOrder()
                    ->distinct()
                    ->take(12)
                    ->get()
                    ->toArray();
            } else {
                $data = Wallpapers::with('tags')
                    ->where('image_extension', '<>', 'image/gif')
                    ->whereHas('categories', function ($query) use ($site) {
                        $query->where('category_checked_ip', 1)
                            ->where('site_id', $site->id);
                    })
                    ->orderBy($sort, 'desc')
                    ->take(12)
                    ->get()
                    ->toArray();
            }
        }else{
            if (!$sort) {
                $data = Wallpapers::with('tags')
                    ->where('image_extension', '<>', 'image/gif')
                    ->whereHas('categories', function ($query) use ($site) {
                        $query->where('category_checked_ip', 0)
                            ->where('site_id', $site->id);
                    })
                    ->inRandomOrder()
                    ->distinct()
                    ->take(12)
                    ->get()
                    ->toArray();
            } else {
                $data = Wallpapers::with('tags')
                    ->where('image_extension', '<>', 'image/gif')
                    ->whereHas('categories', function ($query) use ($site) {
                        $query->where('category_checked_ip', 0)
                            ->where('site_id', $site->id);
                    })
                    ->orderBy($sort, 'desc')
                    ->take(12)
                    ->get()
                    ->toArray();
            }
        }
        foreach ($data as $item){
            $tags = [];
            foreach ($item['tags'] as $tag){

                $tags[] = $tag['tag_name'];
            }

            $data_arr['id'] = $item['id'];
            $data_arr['cat_id'] = '';
            $data_arr['wallpaper_type'] = 'Portrait';
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

    private  function getWallpaper($data,$android_id){
        $jsonObj = [];
        foreach ($data as $item){
            $tags = [];
            foreach ($item['tags'] as $tag){

                $tags[] = $tag['tag_name'];
            }

            $category = isset($item['pivot']) ? Categories::find($item['pivot']['category_id']) : null;
            $data_arr['num'] = count($data);
            $data_arr['id'] = $item['id'];
            $data_arr['cat_id'] = isset($item['pivot']) ? $item['pivot']['category_id'] : '';
            $data_arr['wallpaper_type'] = 'Portrait' ;
            $data_arr['wallpaper_image'] = asset('storage/wallpapers/' . $item['wallpaper_image']);
            $data_arr['wallpaper_image_thumb'] = asset('storage/wallpapers/thumbnails/' . $item['wallpaper_image']);
            $data_arr['total_views'] = $item['wallpaper_view_count'];
            $data_arr['total_rate'] = $item['wallpaper_like_count'];
            $data_arr['rate_avg'] = $item['wallpaper_like_count'];

            $data_arr['is_favorite']= $this->is_favorite($item['id'], 'wallpaper', $android_id);

            $data_arr['wall_tags'] = implode(",", $tags);
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

        if (!empty($data)){
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

        $tags = [];
        foreach ($data['tags'] as $tag){

            $tags[] = $tag['tag_name'];
        }

        $data_arr['id'] = (string)$data->id;
        $data_arr['wallpaper_type'] = '' ;
        $data_arr['wallpaper_image'] = asset('storage/wallpapers/' . $data['wallpaper_image']);
        $data_arr['wallpaper_image_thumb'] = asset('storage/wallpapers/thumbnails/' . $data['wallpaper_image']);

        $data_arr['total_views'] = (string)$data['wallpaper_view_count'];
        $data_arr['total_rate'] = (string)$data['wallpaper_like_count'];
        $data_arr['rate_avg'] = (string)$data['wallpaper_download_count'];
        $data_arr['is_favorite']= $this->is_favorite($data['id'], 'wallpaper', $android_id);;
        $data_arr['total_download'] = (string)$data['wallpaper_download_count'];


        $data_arr['wall_tags'] = implode(",", $tags);
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
        $tags = [];
        foreach ($data['tags'] as $tag){

            $tags[] = $tag['tag_name'];
        }


        $data_arr['id'] = (string)$data->id;
        $data_arr['gif_image'] = asset('storage/wallpapers/' . $data['wallpaper_image']);
        $data_arr['gif_tags'] = implode(",", $tags);
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

    public  function getCategories(){
        $domain = $_SERVER['SERVER_NAME'];
        $site = Sites::where('site_web',$domain)->first();
        $load_categories = $site->load_categories;

        if (checkBlockIp()) {

            if($load_categories == 0 ){
                $data = $site
                    ->categories()
                    ->where('category_checked_ip', 1)
                    ->inRandomOrder()
                    ->get();
            }
            elseif($load_categories == 1 ){
                $data = $site
                    ->categories()
                    ->where('category_checked_ip', 1)
                    ->orderBy('category_view_count','desc')
                    ->get();
            }
            elseif($load_categories == 2 ){
                $data = $site
                    ->categories()
                    ->where('category_checked_ip', 1)
                    ->orderBy('updated_at','desc')
                    ->get();
            }

            $wallpaper = [];
            foreach ($data as $item ){
                foreach ($item->wallpaper()->where('image_extension','<>','image/gif')->with('categories','tags')->get()->toArray() as $wall){
                    $wallpaper[] = $wall;
                }
            }
        } else {

            if($load_categories == 0 ){
                $data = $site
                    ->categories()
                    ->where('category_checked_ip', 0)
                    ->inRandomOrder()
                    ->get();
            }
            elseif($load_categories == 1 ){
                $data = $site
                    ->categories()
                    ->where('category_checked_ip',0)
                    ->orderBy('category_view_count','desc')
                    ->get();
            }
            elseif($load_categories == 2 ){
                $data = $site
                    ->categories()
                    ->where('category_checked_ip', 0)
                    ->orderBy('updated_at','desc')
                    ->get();
            }
        }
        $row = CategoriesResource::collection($data);
        return $row;
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
