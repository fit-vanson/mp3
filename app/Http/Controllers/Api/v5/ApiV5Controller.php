<?php

namespace App\Http\Controllers\Api\v5;

use App\Categories;
use App\Http\Controllers\Controller;
use App\Http\Resources\v5\CategoryHomeResource;
use App\Http\Resources\v5\CategoryResource;
use App\Http\Resources\v5\MusicResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiV5Controller extends Controller
{
    function checkSignSalt($data_info){

//        $key="vietmmozxcv";
        $key="nemosofts";

        $data_json = $data_info;

        $data_arr = json_decode(urldecode(base64_decode($data_json)),true);

        //echo $data_arr['salt'];
        //exit;

        if($data_arr['sign'] == '' && $data_arr['salt'] == '' ){
            //$data['data'] = array("status" => -1, "message" => "Invalid sign salt.");

            $set['ONLINE_MP3_APP'][] = array("status" => -1, "message" => "Invalid sign salt.");
            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            exit();


        }else{

            $data_arr['salt'];

            $md5_salt=md5($key.$data_arr['salt']);

            if($data_arr['sign']!=$md5_salt){

                //$data['data'] = array("status" => -1, "message" => "Invalid sign salt.");
                $set['ONLINE_MP3_APP'][] = array("status" => -1, "message" => "Invalid sign salt.");
                header( 'Content-Type: application/json; charset=utf-8' );
                echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
                exit();
            }
        }

        return $data_arr;

    }
    public function get_data(){
        $get_data= $this->checkSignSalt($_POST['data']);
        dd($get_data);

        $method_name = $get_data['method_name'];
        switch ($method_name){
            case 'app_details':
                $result = $this->app_details($get_data);
                break;
        }
        header('Content-Type: application/json; charset=utf-8');
        return \Response::json(array(
            'ONLINE_MP3_APP' => $result,
        ));

    }

    public function signin(){
        $data = '{
            "user_id": "939",
            "user_name": "zxcv",
            "user_email": "zxcv@gmail.com",
            "user_password": "123456",
            "user_profile_pic": "uploads/user/",
            "created_date": "2023-01-05 02:17:40",
            "message": "Welcome zxcv"
            }';
        return $data;
    }

    public function settingsFlag(){



//        $home_components[] = [
//            'settings_flag_value' => "ENABLE",
//            'settings_flag_parameters' => [
//                'ads_id_value' =>[
//
//                ]
//            ]
//        ];

        $home_components = [];


        $data = ['Notification','Package','Ads'];
        foreach ($data as $key=>$value){
            $home_components[] =[
                'settings_flag_value' => "ENABLE",
                'settings_flag_parameters' => [[
                    'appads' => [
                        'ads_id_value' => "ca-app-pub-3940256099942544~3347511713"
                    ],
                    'bannerads' => [
                        'ads_id_value' => "ca-app-pub-3940256099942544~3347511713"
                    ],
                    'interstitialads' => [
                        'ads_id_value' => "ca-app-pub-3940256099942544~3347511713"
                    ],
                ]] ,
            ];
        }




        return \Response::json($home_components);


        $site = getSite();
        $app_name = $site->site_app_name;
        $app_package_name = $site->site_package ?? "com.vietmmonet.abcdzxcv";
        $site_ads = json_decode($site->site_ads, true);
        $status_ads = $site->ad_switch == 1 ? "ENABLE" : "DISABLE";

        $response[] = array(
            'app_name' => $app_name,
            'app_logo' =>  'https://'.getDomain().'/storage/sites/'.$site->id.'/'.$site->site_image,
            'app_version' => $site->site_app_version,
            'app_author' => $site->site_app_version,
            "app_contact"=> "",
            "app_email"=>"info@".getDomain(),
            "app_website"=> getDomain(),
            "app_description"=> getDomain(),
            "app_developed_by"=> getDomain(),
            "app_privacy_policy"=> getDomain(),
            'package_name' => $app_package_name,

            'publisher_id' => $site_ads ? $site_ads['AdMob_Publisher_ID'] : "",
            'interstitial_id' => $status_ads,
            'interstital_ad_type' => "admob",
            'interstital_ad_id' => $site_ads ? $site_ads['AdMob_Interstitial_Ad_Unit_ID'] : "",
            'interstital_ad_click' => 5,

            'banner_ad_type' => "admob",
            'banner_ad' => $status_ads,
            'banner_ad_id' => $site_ads ? $site_ads['AdMob_Banner_Ad_Unit_ID'] : "",
            'banner_size' => "BANNER",

            'native_ad' => $status_ads,
            'native_ad_type' => "admob",
            'native_ad_id' => $site_ads ? $site_ads['AdMob_Native_Ad_Unit_ID'] : "",
            'native_position' => 5,

            'isUpdate' => false,
            'version' => $site->site_app_version,
            'version_name' => $site->site_app_version,
            'description' => $site->site_app_version,
            'url' => $site->site_link,
            'isRTL' => false,

            'isSongDownload' => true,
            'isMoviePromote' => true,
            'isNews' => true,
            'isAppMaintenance' => false,
            'isScreenshot' => false,
            'facebook_login' => false,
            'google_login' => false,

            'envato_purchase_code' => "5w9wsru9-8685-hx977-uv839-8545x4ykx4t2",
            'app_api_key' => "dh9bmctw-5265-jfl2-h85y-42pcnzkjb3n9",

        );
        return $response;
    }

    public function home_components(){
        $home_components = [];
        $data = ['Banner Slider','Category','Top Sounds','Popular Sounds','Recently Played','Sleep Stories'];
        foreach ($data as $key=>$value){
            $home_components[] =[
                'home_components_id' => $key+1,
                'home_components_name' => $value,
                'home_components_order' => $key+1,
                'home_components_status' => "ENABLE"
            ];
        }
        return \Response::json($home_components);
    }

    public function home(Request $request){
        $site = getSite();
        getHome($site);
        $home_components_name = $request->home_components_name;
        switch ($home_components_name){
            case 'Banner Slider':
                $data = get_categories($site,5);
                $getResource = CategoryHomeResource::collection($data);
                break;
            case 'Category':
                $data = get_categories($site,5);
                $getResource = CategoryResource::collection($data);
                break;
            case 'Top Sounds':
                $data = get_songs($site,5,'music_like_count');
                $getResource = MusicResource::collection($data);
                break;
            case 'Popular Sounds':
            case 'Recently Played':
                $data = get_songs($site,5,'updated_at');
                $getResource = MusicResource::collection($data);
                break;
            case 'Sleep Stories':
                $data = get_songs($site,5);
                $getResource = MusicResource::collection($data);
                break;
            default:
                $data = get_songs($site,1000,$request->order);
                $getResource = MusicResource::collection($data);
                break;

        }
        return \Response::json($getResource);
    }

    public function getCategories(){
        $site = getSite();
        $data = get_categories($site,1000);
        $getResource = CategoryResource::collection($data);
        return \Response::json($getResource);
    }

    public function getCategoryMusic(Request $request){
        $category_id = $request->category_id;
        $androidId = $request->android_id;
        $site = getSite();
        $category = Categories::findOrFail($category_id);
        $data = get_category_details($site,$category,500);
        $getResource = [];
        foreach ($data as $item ){
            $item->fav = check_favourite($site,$androidId,$item->id);
            $getResource[] = new MusicResource($item);
        }
        return response()->json($getResource);
    }

    public function playMusic(Request $request){
        $music_id = $request->music_id;
        $androidId = $request->android_id;
        $site = getSite();
        $music = update_song_view($music_id);
        $check_favourite = check_favourite($site,$androidId,$music_id) ? 1 : 0;
        $music->fav = $check_favourite;
        $result= new MusicResource($music);
        return response()->json($result);
    }

    public function update_song_favourite(Request $request){
        $music_id = $request->like_type_id;
        $androidId = $request->android_id;
        $site = getSite();
        $result = update_song_favourite($site,$androidId,$music_id);
        return response()->json($result);
    }

    public function getAllMusics(Request $request){
        $androidId = $request->android_id;
;        $site = getSite();
        $getMusic = get_all_music($site,1000);
        $getResource = [];
        foreach($getMusic as $music){
            $check_favourite = check_favourite($site,$androidId,$music->id) ? 1 : 0;
            try {
                $music->fav = $check_favourite;
                $getResource[] = new MusicResource($music);
            }catch (\Exception $ex) {
                Log::error('Message: favorite ' . $ex->getMessage() .'--: '.$music->id. ' -----' . $ex->getLine());
            }
        }
        return response()->json($getResource);
    }


    public function favorite(Request $request){
        $androidId = $request->android_id;
        $site = getSite();
        $getMusic = get_song_favourite($site,$androidId,1000);
        $getResource = [];

        foreach($getMusic as $music){
            try {
                $music->music->fav = 1;
                $getResource[] = new MusicResource($music->music);
            }catch (\Exception $ex) {
                Log::error('Message: favorite ' . $ex->getMessage() .'--: '.$music->id. ' -----' . $ex->getLine());
            }

        }
        return response()->json($getResource);
    }

    public function search(Request $request){
        $search = $request->search;
        $androidId = $request->android_id;
        $site = getSite();
        $result_music = get_search_music($site,$search,10);
        $getResource = [];
        foreach ($result_music as $item ){
            $item->fav = check_favourite($site,$androidId,$item->id);
            $item->search_text = $search;
            $getResource[] = new MusicResource($item);
        }
        return response()->json($getResource);
    }



}
