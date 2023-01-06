<?php

namespace App\Http\Controllers\Api\v5;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiV5Controller extends Controller
{
    function checkSignSalt($data_info){

        $key="vietmmozxcv";

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

    public function app_details($get_data){

        $site = getSite();
        $app_name = $site->site_app_name;
        $app_package_name = $site->site_package ?? "com.vietmmonet.abcdzxcv";
        $site_ads = json_decode($site->site_ads, true);
        $status_ads = $site->ad_switch == 1 ? true : false;

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

            'envato_purchase_code' => "",
            'app_api_key' => "",

        );
        return $response;
    }
}
