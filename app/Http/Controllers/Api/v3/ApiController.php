<?php

namespace App\Http\Controllers\Api\v3;

use App\Categories;
use App\Http\Controllers\Controller;
use App\ListIP;
use App\Sites;
use App\Wallpapers;
use BaconQrCode\Renderer\Color\Rgb;
use Carbon\Carbon;
use ColorThief\ColorThief;
use Dflydev\DotAccessData\Data;
use League\ColorExtractor\Palette;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends Controller
{
    public function index()
    {
        dd(1);
    }

    public function checkCode($id)
    {

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
        $listIp = ListIP::where('ip_address', $ipaddress)->where('id_site', $site->id)->first();
        if (!$listIp) {
            ListIP::create([
                'ip_address' => $ipaddress,
                'id_site' => $site->id,
                'count' => 1
            ]);
        } else {
            $listIp->update(['count' => $listIp->count + 1]);
        }


        $ads = json_decode($site->site_ads, true);

        $response_publisher_id["name"] = "ADMIN_PUBLISHER_ID";
        $response_publisher_id["value"] = $site->ad_switch == 1 ? ($ads ? $ads['AdMob_Publisher_ID'] : '') : '';

        $response_app_id["name"] = "ADMIN_APP_ID";
        $response_app_id["value"] = $site->ad_switch == 1 ? ($ads ? $ads['AdMob_App_ID'] : '') : '';

        $response_ads_rewarded["name"] = "ADMIN_REWARDED_ADMOB_ID";
        $response_ads_rewarded["value"] = $site->ad_switch == 1 ? ($ads ? $ads['AdMob_App_Reward_Ad_Unit_ID'] : '') : '';


        $response_ads_interstitial_admob_id["name"] = "ADMIN_INTERSTITIAL_ADMOB_ID";
        $response_ads_interstitial_admob_id["value"] = $site->ad_switch == 1 ? ($ads ? $ads['AdMob_Interstitial_Ad_Unit_ID'] : '') : '';

        $response_ads_interstitial_facebook_id["name"] = "ADMIN_INTERSTITIAL_FACEBOOK_ID";
        $response_ads_interstitial_facebook_id["value"] = '';


        $response_ads_interstitial_type["name"] = "ADMIN_INTERSTITIAL_TYPE";
        $response_ads_interstitial_type["value"] = 'BOTH';

        $response_ads_interstitial_click["name"] = "ADMIN_INTERSTITIAL_CLICKS";
        $response_ads_interstitial_click["value"] = 3;

        $response_ads_banner_admob_id["name"] = "ADMIN_BANNER_ADMOB_ID";
        $response_ads_banner_admob_id["value"] = $site->ad_switch == 1 ? ($ads ? $ads['AdMob_Banner_Ad_Unit_ID'] : '') : '';


        $response_ads_banner_facebook_id["name"] = "ADMIN_BANNER_FACEBOOK_ID";
        $response_ads_banner_facebook_id["value"] = "";

        $response_ads_banner_type["name"] = "ADMIN_BANNER_TYPE";
        $response_ads_banner_type["value"] = "BOTH";

        $response_ads_native_facebook_id["name"] = "ADMIN_NATIVE_FACEBOOK_ID";
        $response_ads_native_facebook_id["value"] = "";

        $response_ads_native_admob_id["name"] = "ADMIN_NATIVE_ADMOB_ID";
        $response_ads_native_admob_id["value"] = $site->ad_switch == 1 ? ($ads ? $ads['AdMob_Native_Ad_Unit_ID'] : '') : '';

        $response_ads_native_item["name"] = "ADMIN_NATIVE_LINES";
        $response_ads_native_item["value"] = 6;


        $response_ads_native_type["name"] = "ADMIN_NATIVE_TYPE";
        $response_ads_native_type["value"] = "BOTH";

        $code = "200";
        $response["name"] = "update";
        $response["value"] = "App on update";
        $message = "";

        $errors[] = $response;

        $errors[] = $response_app_id;
        $errors[] = $response_publisher_id;
        $errors[] = $response_ads_rewarded;
        $errors[] = $response_ads_interstitial_admob_id;
        $errors[] = $response_ads_interstitial_facebook_id;
        $errors[] = $response_ads_interstitial_type;
        $errors[] = $response_ads_interstitial_click;
        $errors[] = $response_ads_banner_admob_id;
        $errors[] = $response_ads_banner_facebook_id;
        $errors[] = $response_ads_banner_type;
        $errors[] = $response_ads_native_facebook_id;
        $errors[] = $response_ads_native_admob_id;
        $errors[] = $response_ads_native_item;
        $errors[] = $response_ads_native_type;

        $error = array(
            "code" => $code,
            "message" => $message,
            "values" => $errors,
        );
        return new Response(json_encode($error));

    }

    public function first()
    {
        $page_limit = 10;
        $limit = 0;

        $domain = $_SERVER['SERVER_NAME'];


        if (checkBlockIp()) {
            $data = Sites::where('site_web', $domain)->first()
                ->categories()
                ->where('category_checked_ip', 1)
                ->get();
            $wallpaper = [];
            foreach ($data as $item) {

                foreach ($item->wallpaper()->with('tags')->orderBy('updated_at', 'desc')->get()->toArray() as $wall) {
                    $wallpaper[] = $wall;
                }
            }
        } else {
            $data = Sites::where('site_web', $domain)->first()
                ->categories()
                ->where('category_checked_ip', 0)
                ->get();
            $wallpaper = [];
            foreach ($data as $item) {
                foreach ($item->wallpaper()->with('tags')->orderBy('updated_at', 'desc')->get()->toArray() as $wall) {
                    $wallpaper[] = $wall;
                }
            }
        }

        $temp = array_unique(array_column($wallpaper, 'id'));
        $unique_arr = array_intersect_key($wallpaper, $temp);
        $result = array_slice($unique_arr, $limit, $page_limit);
        $data_arr['categories'] = $this->getCategories($data);
        $data_arr['wallpapers'] = $this->getWallpaper($result);
        return json_encode($data_arr, JSON_UNESCAPED_UNICODE);

    }

    public function categoryAll()
    {
        $domain = $_SERVER['SERVER_NAME'];

        if (checkBlockIp()) {
            $data = Sites::where('site_web',$domain)->first()
                ->categories()
                ->where('category_checked_ip',1)
                ->inRandomOrder()
                ->get();
        } else {
            $data = Sites::where('site_web',$domain)->first()
                ->categories()
                ->where('category_checked_ip',0)
                ->inRandomOrder()
                ->get();
        }
        $data_arr = $this->getCategories($data);
        return json_encode($data_arr);

    }

    public function wallpapersAll($order, $page)
    {
        $page_limit = 10;
        $limit = $page * $page_limit;
        $domain = $_SERVER['SERVER_NAME'];

        if (checkBlockIp()) {
            $data = Sites::where('site_web', $domain)->first()
                ->categories()
                ->where('category_checked_ip', 1)
                ->get();
            $wallpaper = [];
            foreach ($data as $item) {
                foreach (
                    $item->wallpaper()
                        ->with('tags')
                        ->get()
                        ->toArray() as $wall) {
                    $wallpaper[] = $wall;
                }
            }
        } else {
            $data = Sites::where('site_web', $domain)->first()
                ->categories()
                ->where('category_checked_ip', 0)
                ->get();
            $wallpaper = [];
            foreach ($data as $item) {
                foreach (
                    $item->wallpaper()
                        ->with('tags')
                        ->get()
                        ->toArray() as $wall) {
                    $wallpaper[] = $wall;
                }
            }
        }

        $temp = array_unique(array_column($wallpaper, 'id'));
        $unique_arr = array_intersect_key($wallpaper, $temp);
        usort($unique_arr, function($a, $b) use ($order) {
            return $b['wallpaper_'.$order] <=> $a['wallpaper_'.$order];
        });
        $result = array_slice($unique_arr, $limit, $page_limit);

        $data_arr = $this->getWallpaper($result);
        return json_encode($data_arr, JSON_UNESCAPED_UNICODE);

    }

    public function wallpapersRandom($page)
    {
        $page_limit = 10;
        $limit = $page * $page_limit;
        $domain = $_SERVER['SERVER_NAME'];

        if (checkBlockIp()) {
            $data = Sites::where('site_web', $domain)->first()
                ->categories()
                ->where('category_checked_ip', 1)
                ->get();
            $wallpaper = [];
            foreach ($data as $item) {
                foreach (
                    $item->wallpaper()
                        ->with('tags')
                        ->get()
                        ->toArray() as $wall) {
                    $wallpaper[] = $wall;
                }
            }
        } else {
            $data = Sites::where('site_web', $domain)->first()
                ->categories()
                ->where('category_checked_ip', 0)
                ->get();
            $wallpaper = [];
            foreach ($data as $item) {
                foreach (
                    $item->wallpaper()
                        ->with('tags')
                        ->get()
                        ->toArray() as $wall) {
                    $wallpaper[] = $wall;
                }
            }
        }

        $temp = array_unique(array_column($wallpaper, 'id'));
        $unique_arr = array_intersect_key($wallpaper, $temp);
        shuffle($unique_arr);
        $result = array_slice($unique_arr, $limit, $page_limit);


        $data_arr = $this->getWallpaper($result);
        return json_encode($data_arr, JSON_UNESCAPED_UNICODE);

    }

    public function wallpapersByCategory($page, $category)
    {
        $page_limit = 10;
        $limit = $page * $page_limit;
        $wallpaper = Categories::findOrFail($category)
            ->wallpaper()
            ->with('tags')
            ->distinct()
            ->inRandomOrder()
            ->limit($page_limit)
            ->offset($limit)
            ->get()->toArray();
        $data_arr = $this->getWallpaper($wallpaper);
        return json_encode($data_arr, JSON_UNESCAPED_UNICODE);

    }

    public function wallpapersBysearch($page, $query)
    {
        $page_limit = 10;
        $limit = $page * $page_limit;
        $domain = $_SERVER['SERVER_NAME'];

        if (checkBlockIp()) {
            $data = Sites::where('site_web', $domain)->first()
                ->categories()
                ->where('category_checked_ip', 1)
                ->get();
            $wallpaper = [];
            foreach ($data as $item) {
                foreach (
                    $item->wallpaper()
                        ->with('tags')
                        ->where('wallpaper_name', 'like', '%' . $query . '%')
                        ->get()
                        ->toArray() as $wall) {
                    $wallpaper[] = $wall;
                }
            }
        } else {
            $data = Sites::where('site_web', $domain)->first()
                ->categories()
                ->where('category_checked_ip', 0)
                ->get();
            $wallpaper = [];
            foreach ($data as $item) {
                foreach (
                    $item->wallpaper()
                        ->with('tags')
                        ->where('wallpaper_name', 'like', '%' . $query . '%')
                        ->get()
                        ->toArray() as $wall) {
                    $wallpaper[] = $wall;
                }
            }
        }
        $temp = array_unique(array_column($wallpaper, 'id'));
        $unique_arr = array_intersect_key($wallpaper, $temp);
        shuffle($unique_arr);
        $result = array_slice($unique_arr, $limit, $page_limit);
        $data_arr = $this->getWallpaper($result);

        return json_encode($data_arr, JSON_UNESCAPED_UNICODE);

    }

    public function getCategories($data)
    {
        $jsonObj = [];
        foreach ($data as $item) {
            $data_arr['id'] = $item['id'];
            $data_arr['title'] = $item['category_name'];
            $data_arr['image'] = asset('storage/categories/' . $item['category_image']);

            array_push($jsonObj, $data_arr);
        }
        return $jsonObj;
    }

    public function api_add_set($id)
    {
        $wallpaper = Wallpapers::find($id);
        $wallpaper->view_count = $wallpaper->wallpaper_view_count + 1;
        $wallpaper->save();
        return json_encode($wallpaper->view_count, JSON_UNESCAPED_UNICODE);
    }

    public function api_add_view($id)
    {

        $wallpaper = Wallpapers::find($id);
        $wallpaper->view_count = $wallpaper->wallpaperview_count + 1;
        $wallpaper->save();
        return json_encode($wallpaper->view_count, JSON_UNESCAPED_UNICODE);
    }

    public function api_add_download($id)
    {
        $wallpaper = Wallpapers::find($id);
        $wallpaper->view_count = $wallpaper->wallpaper_download_count + 1;
        $wallpaper->save();
        return json_encode($wallpaper->view_count, JSON_UNESCAPED_UNICODE);
    }

    private function getWallpaper($data)
    {
        $jsonObj = [];
        foreach ($data as $item) {

            $sourceImage = public_path('storage/wallpapers/'.$item['wallpaper_image']);
            $color = ColorThief::getColor($sourceImage,25,null,'hex');

            $tags = [];
            foreach ($item['tags'] as $tag){
                $tags[] = $tag['tag_name'];
            }
            $data_arr['id'] = $item['id'];
            $data_arr['kind'] = $item['image_extension'] != 'image/gif' ? 'image' : 'gif';
            $data_arr['title'] = $item['wallpaper_name'];
            $data_arr['description'] = $item['wallpaper_name'];
//            $data_arr['color'] = substr(md5(rand()), 0, 6);
            $data_arr['color'] = str_replace('#','',$color);
            $data_arr['downloads'] = $item['wallpaper_download_count'];
            $data_arr['views'] = $item['wallpaper_view_count'];
            $data_arr['shares'] = rand(500,2000);
            $data_arr['sets'] = rand(500,2000);

            $data_arr['type'] = $item['image_extension'];
            $data_arr['extension'] = $item['image_extension'];

            $data_arr['thumbnail'] = asset('storage/wallpapers/' . $item['wallpaper_image']);
            $data_arr['image'] = asset('storage/wallpapers/' . $item['wallpaper_image']);
            $data_arr['original'] = asset('storage/wallpapers/' . $item['wallpaper_image']);
            $data_arr['created'] = Carbon::parse($item['created_at'])->format('Y-m-d');
            $data_arr['tags'] =  implode(",", $tags);
            array_push($jsonObj, $data_arr);
        }

        return $jsonObj;
    }




}
