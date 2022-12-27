<?php

namespace App\Http\Controllers\Api\v4;

use App\Categories;
use App\Http\Controllers\Controller;
use App\Http\Resources\v4\CategoryHomeResource;
use App\Http\Resources\v4\CategoryResource;
use App\Http\Resources\v4\MusicForCategoryResource;
use App\Http\Resources\v4\MusicResource;
use App\Musics;
use Illuminate\Http\Request;

class ApiV4Controller extends Controller
{
    function checkSignSalt($data_info){

        $key="viaviweb";

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
    public function app_details()
    {
        $get_data= $this->checkSignSalt($_POST['data']);
        $site = getSite();
        $app_name = $site->site_app_name;
        $app_logo = \URL::to('/' . $site->site_image);
        $facebook_link = $site->site_direct_link ? $site->site_direct_link : '';

        $twitter_link = $site->twitter_link ? $site->twitter_link : '';
        $instagram_link = $site->instagram_link ? $site->instagram_link : '';
        $youtube_link = $site->youtube_link ? $site->youtube_link : '';
        $google_play_link = $site->site_chplay_link ? $site->site_chplay_link : '';
        $app_package_name = $site->site_package ?? "com.zxcv.onlinemp3";
        $site_ads = json_decode($site->site_ads, true);
        $status_ads = $site->ad_switch;


        $ads[] = [
            "ad_id"=> 5,
            "ads_name"=> "Wortise",
            'ads_info' =>[
                'publisher_id' => "c2f94dbb-e29a-4a95-bbf9-fbf860c428b3",
                'banner_on_off' =>"1",
                'banner_id' =>"acbb8bfe-be23-4252-a173-3bbc0d8dfb8a",
                'interstitial_on_off' => "1",
                'native_on_off' => "1",
                'interstitial_id' => "70f3bdd5-4cd1-4943-a34d-9d23ba25542d",
                'native_id' => "f78f3300-dc1b-4ece-b85b-2bc5ecbca8bb",
                'interstitial_clicks' => 5,
                'native_position' => 5,
            ],
            'status' =>'true',
        ];
        $page_list = [
            [
                'page_id' => 1,
                'page_title' => 'About Us',
                'page_content' => 'About Us',
            ],

            [
                'page_id' => 2,
                'page_title' => 'Terms Of Use',
                'page_content' => 'Terms Of Use',
            ],
            [
                'page_id' => 3,
                'page_title' => 'Privacy Policy',
                'page_content' => $site->site_policy ?? 'ssssss',
            ],

        ];

        $response[] = array(
            'app_package_name' => $app_package_name,
            'app_name' => $app_name,

            "app_email"=>"info@viavilab.com",
            'app_logo' =>  "https://mp3app.viaviweb.in/upload/app_icon.png",
            "app_company"=>  "Viavi Webtech",
            "app_website"=> "www.viaviweb.com",
            "app_contact"=> "+91 9227777522",


            'facebook_link' =>  "https://facebook.com",
            'twitter_link' => "https://twitter.com",
            'instagram_link' => "https://instagram.com",
            'youtube_link' => "https://youtube.com",
            'google_play_link' => "#gp",
            'apple_store_link' => "#ap",
            'app_version' => "1.1.1",
            'app_update_hide_show' => "false",
            'app_update_version_code' => "1.2",
            'app_update_desc' => "Please update new app",
            'app_update_link' =>  "https://google.com",
            'app_update_cancel_option' => "true",
            'song_download' => "true",
            'ads_list' => $ads,
            'page_list' => $page_list,
            'success' => '1');

        return \Response::json(array(
            'ONLINE_MP3_APP' => $response,
            'status_code' => 200
        ));


    }

    public function home(){
        $get_data= $this->checkSignSalt($_POST['data']);
        $recently_songs = [];
        $get_trending_songs = [];
        $get_popular_songs = [];
        $site = getSite();
        getHome($site);
        getVisitors($get_data['androidId']);
        $categories = get_categories($site,3);
        $getMusicCategory = MusicForCategoryResource::collection($categories);
        $getCategory = CategoryHomeResource::collection($categories);
        if(isset($get_data['songs_ids'])){
            $songs_ids= explode(',',$get_data['songs_ids']);
            $musics = Musics::whereIN('id',$songs_ids)->get();
            foreach($musics as $music){
                $music->fav = check_favourite($site,$get_data['androidId'],$music->id);
                $recently_songs[] = new MusicResource($music);
            }
        }
        $trending_songs = get_songs($site,10,'music_view_count');
        foreach ($trending_songs as $item ){
            $item->fav = check_favourite($site,$get_data['androidId'],$item->id);
            $get_trending_songs[] = new MusicResource($item);
        }
//        $get_trending_songs = MusicResource::collection($trending_songs);

        $popular_songs = get_songs($site,10,'music_like_count');
        foreach ($popular_songs as $item ){
            $item->fav = check_favourite($site,$get_data['androidId'],$item->id);
            $get_popular_songs[] = new MusicResource($item);
        }

//        $get_popular_songs = MusicResource::collection($popular_songs);


        $category = [
            'home_id' => 'category',
            'home_title'=> 'Category',
            'home_type'=> 'category',
            'home_content'=> $getCategory,
        ];

        $popular_songs = [
            'home_id' => 'popular_songs',
            'home_title'=> 'Popular Songs',
            'home_type'=> 'song',
            'home_content'=> $get_popular_songs,
        ];
        $home_sections = [
            $category,$popular_songs
        ];

        $data = [
            'ONLINE_MP3_APP' => [
                'slider' => $getMusicCategory,
                'recently_songs' => $recently_songs,
                'trending_songs' => $get_trending_songs,
                'popular_songs' => $get_popular_songs,
                'home_sections' => $home_sections
            ],
            "status_code"=> 200,
        ];
        return response()->json($data);


    }

    public function trending_songs(){

        $get_data= $this->checkSignSalt($_POST['data']);
        $site = getSite();
        $trending_songs = get_songs($site,10,'music_view_count');
        $getResource =[];
        foreach ($trending_songs as $item ){
            $item->fav = check_favourite($site,$get_data['androidId'],$item->id);
            $getResource[] = new MusicResource($item);
        }
        $data = [
            'ONLINE_MP3_APP' => $getResource,
            "status_code"=> 200
        ];
        return response()->json($data);
    }

    public function home_collections(){
        $get_data= $this->checkSignSalt($_POST['data']);

        $id = $get_data['id'];
        $site = getSite();
        $getResource = [];
        switch ($id){
            case 'category':
                $categories = get_categories($site,5);
                $getResource = CategoryResource::collection($categories);
                break;
            case 'popular_songs':
                $popular_songs = get_songs($site,10,'music_like_count');
                foreach ($popular_songs as $item ){
                    $item->fav = check_favourite($site,$get_data['androidId'],$item->id);
                    $getResource[] = new MusicResource($item);
                }
                break;
        }
        $data = [
           'ONLINE_MP3_APP' => $getResource,
            "status_code"=> 200
        ];
        return response()->json($data);
    }

    public function song_by_category($catID = null){

        $get_data= $this->checkSignSalt($_POST['data']);
        $category_id = $catID ?? $get_data['category_id'];

        $site = getSite();
        $category = Categories::findOrFail($category_id);
        $data = get_category_details($site,$category,20);
        $getResource = [];
        foreach ($data as $item ){
            $item->fav = check_favourite($site,$get_data['androidId'],$item->id);
            $getResource[] = new MusicResource($item);
        }
        $data = [
            'ONLINE_MP3_APP' => $getResource,
            'total_records'=>$data->total(),
            "status_code"=> 200
        ];
        return response()->json($data);
    }

    public function home_slider_songs(){
        $get_data= $this->checkSignSalt($_POST['data']);
        $category_id = $get_data['slider_id'];
        return $this->song_by_category($category_id);
    }

    public function home_recently_songs(){
        $site = getSite();
        $get_data= $this->checkSignSalt($_POST['data']);
        $songs_ids= explode(',',$get_data['songs_ids']);
        $musics = Musics::whereIN('id',$songs_ids)->get();
        foreach($musics as $music){
            $music->fav = check_favourite($site,$get_data['androidId'],$music->id);
            $recently_songs[] = new MusicResource($music);
        }
        $data = [
            'ONLINE_MP3_APP' => $recently_songs,
            "status_code"=> 200
        ];
        return response()->json($data);
    }

    public function category(){
        $get_data= $this->checkSignSalt($_POST['data']);
        $site = getSite();
        $categories = get_categories($site,10);
        $getResource = CategoryResource::collection($categories);
        $data = [
            'ONLINE_MP3_APP' => $getResource,
            "total_records"=> $categories->count(),
            "status_code"=> 200
        ];
        return response()->json($data);
    }

    public function latest_songs(){
        $get_data= $this->checkSignSalt($_POST['data']);
        $site = getSite();
        $latest_songs = get_songs($site,10,'id');
        $getResource = MusicResource::collection($latest_songs);
        $data = [
            'ONLINE_MP3_APP' => $getResource,
            "total_records"=> $latest_songs->total(),
            "status_code"=> 200
        ];
        return response()->json($data);

    }

    public function song_view(){
        $get_data= $this->checkSignSalt($_POST['data']);
        $song_id = $get_data['post_id'];
        $update_view = update_song_view($song_id);
        $data = [
            'ONLINE_MP3_APP' =>[
                [
                'views' => $update_view->music_view_count
                ]
            ] ,
            "status_code"=> 200
        ];

        return response()->json($data);
    }

    public function song_download(){
        $get_data= $this->checkSignSalt($_POST['data']);
        $song_id = $get_data['post_id'];
        $update_view = update_song_download($song_id);
        $data = [
            'ONLINE_MP3_APP' =>[
                [
                'download' => $update_view->music_download_count
                ]
            ] ,
            "status_code"=> 200
        ];

        return response()->json($data);
    }

    public function song_favourite(){
        $site = getSite();
        $get_data= $this->checkSignSalt($_POST['data']);
        $androidId = $get_data['androidId'];
        $musicId = $get_data['post_id'];
        update_song_favourite($site,$androidId,$musicId);
        $response[]=array("success"=>true,"msg"=>'Favourite successfully');
        $data = [
            'ONLINE_MP3_APP' => $response,
            "status_code"=> 200
        ];
        return response()->json($data);
    }

    public function user_favourite_songs(){
        $get_data= $this->checkSignSalt($_POST['data']);
        $androidId = $get_data['androidId'];
        $site = getSite();
        $getMusic = get_song_favourite($site,$androidId,10);


        foreach($getMusic as $music){
            $music->music->fav = true;
            $getResource[] = new MusicResource($music->music);
        }


        $data = [
            'ONLINE_MP3_APP' => $getResource,
            "total_records"=> $getMusic->total(),
            "status_code"=> 200
        ];
        return response()->json($data);
    }

    public function search(){
        $get_data= $this->checkSignSalt($_POST['data']);
        $site = getSite();
        $getMusicResource = [];
        $getCategoriesResource = [];
        $search = $get_data['search_text'];

        $result_music = get_search_music($site,$search,10);
        foreach($result_music as $music){
            $music->fav = true;
            $getMusicResource[] = new MusicResource($music);
        }
        $result_categories = get_search_categories($site,$search,10);
        foreach($result_categories as $cate){
            $getCategoriesResource[] = new CategoryResource($cate);
        }



        $data = [
            'ONLINE_MP3_APP' => [
                'category_list'=> $getCategoriesResource  ,
                'songs_list'=>$getMusicResource,
            ],
            "status_code"=> 200
        ];
        return response()->json($data);
    }


}
