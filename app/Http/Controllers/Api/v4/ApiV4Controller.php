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
use Illuminate\Support\Facades\Log;

class ApiV4Controller extends Controller
{
    function checkSignSalt($data_info)
    {

        $key = "viaviweb";

        $data_json = $data_info;

        $data_arr = json_decode(urldecode(base64_decode($data_json)), true);

        //echo $data_arr['salt'];
        //exit;

        if ($data_arr['sign'] == '' && $data_arr['salt'] == '') {
            //$data['data'] = array("status" => -1, "message" => "Invalid sign salt.");

            $set['ONLINE_MP3_APP'][] = array("status" => -1, "message" => "Invalid sign salt.");
            header('Content-Type: application/json; charset=utf-8');
            echo $val = str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            exit();


        } else {

            $data_arr['salt'];

            $md5_salt = md5($key . $data_arr['salt']);

            if ($data_arr['sign'] != $md5_salt) {

                //$data['data'] = array("status" => -1, "message" => "Invalid sign salt.");
                $set['ONLINE_MP3_APP'][] = array("status" => -1, "message" => "Invalid sign salt.");
                header('Content-Type: application/json; charset=utf-8');
                echo $val = str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
                exit();
            }
        }

        return $data_arr;

    }

    public function app_details()
    {

        $get_data = $this->checkSignSalt($_POST['data']);
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
            "ad_id" => 1,
            "ads_name" => "Admob",
            'ads_info' => [
                'publisher_id' => $site_ads ? $site_ads['AdMob_Publisher_ID'] : "",
                'banner_on_off' => $status_ads,
                'banner_id' => $site_ads ? $site_ads['AdMob_Banner_Ad_Unit_ID'] : "",
                'interstitial_on_off' => $status_ads,
                'native_on_off' => $status_ads,
                'interstitial_id' => $site_ads ? $site_ads['AdMob_Interstitial_Ad_Unit_ID'] : "",
                'native_id' => $site_ads ? $site_ads['AdMob_Native_Ad_Unit_ID'] : "",
                'interstitial_clicks' => 5,
                'native_position' => 5,
            ],
            'status' => 'true',
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

            "app_email" => "info@" . getDomain(),
            'app_logo' => 'https://' . getDomain() . '/storage/sites/' . $site->id . '/' . $site->site_image,
            "app_company" => $app_name,
            "app_website" => getDomain(),
            "app_contact" => "",
            'facebook_link' => $site->site_direct_link ?? 'https://facebook.com',
            'twitter_link' => "https://twitter.com",
            'instagram_link' => "https://instagram.com",
            'youtube_link' => "https://youtube.com",
            'google_play_link' => $site->site_link,
            'apple_store_link' => "#ap",
            'app_version' => $site->site_app_version,
            'app_update_hide_show' => false,
            'app_update_version_code' => $site->site_app_version,
            'app_update_desc' => "Please update new app",
            'app_update_link' => $site->site_link,
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

    public function home()
    {
        $get_data = $this->checkSignSalt($_POST['data']);
        $recently_songs = [];
        $get_trending_songs = [];
        $get_popular_songs = [];
        $site = getSite();
        getHome($site);
        getVisitors($get_data['androidId']);

        $categories = get_categories($site, 10);

        $slide = load_feature($site);
        $getCategory = CategoryHomeResource::collection($categories);
        if (isset($get_data['songs_ids'])) {
            $songs_ids = explode(',', $get_data['songs_ids']);
            $musics = Musics::whereIN('id', $songs_ids)->get();
            foreach ($musics as $music) {
                $music->fav = check_favourite($site, $get_data['androidId'], $music->id);
                $recently_songs[] = new MusicResource($music);
            }
        }
        $trending_songs = get_songs($site, 10, 'music_view_count');
        foreach ($trending_songs as $item) {
            $item->fav = check_favourite($site, $get_data['androidId'], $item->id);
            $get_trending_songs[] = new MusicResource($item);
        }


        $popular_songs = get_songs($site, 10, 'music_like_count');
        foreach ($popular_songs as $item) {
            $item->fav = check_favourite($site, $get_data['androidId'], $item->id);
            $get_popular_songs[] = new MusicResource($item);
        }

        $category = [
            'home_id' => 'category',
            'home_title' => 'Category',
            'home_type' => 'category',
            'home_content' => $getCategory,
        ];

        $popular_songs = [
            'home_id' => 'popular_songs',
            'home_title' => 'Popular Songs',
            'home_type' => 'song',
            'home_content' => $get_popular_songs,
        ];
        $home_sections = [
            $category, $popular_songs
        ];

        $data = [
            'ONLINE_MP3_APP' => [
                'slider' => $slide,
                'recently_songs' => $recently_songs,
                'trending_songs' => $get_trending_songs,
                'popular_songs' => $get_popular_songs,
                'home_sections' => $home_sections
            ],
            "status_code" => 200,
        ];
        return response()->json($data);


    }

    public function trending_songs()
    {

        $get_data = $this->checkSignSalt($_POST['data']);
        $site = getSite();
        $trending_songs = get_songs($site, 10, 'music_view_count');
        $getResource = [];
        foreach ($trending_songs as $item) {
            $item->fav = check_favourite($site, $get_data['androidId'], $item->id);
            $getResource[] = new MusicResource($item);
        }
        $data = [
            'ONLINE_MP3_APP' => $getResource,
            "total_records" => $trending_songs->total(),
            "status_code" => 200
        ];
        return response()->json($data);
    }

    public function home_collections()
    {
        $get_data = $this->checkSignSalt($_POST['data']);

        $id = $get_data['id'];
        $site = getSite();
        $getResource = [];
        switch ($id) {
            case 'category':
                $getdata = get_categories($site, 5);
                $getResource = CategoryResource::collection($getdata);
                break;
            case 'popular_songs':
                $getdata = get_songs($site, 10, 'music_like_count');
                foreach ($getdata as $item) {
                    $item->fav = check_favourite($site, $get_data['androidId'], $item->id);
                    $getResource[] = new MusicResource($item);
                }
                break;
        }
        $data = [
            'ONLINE_MP3_APP' => $getResource,
            "total_records" => $getdata->total(),
            "status_code" => 200
        ];
        return response()->json($data);
    }

    public function song_by_category($catID = null)
    {

        $get_data = $this->checkSignSalt($_POST['data']);
        $category_id = $catID ?? $get_data['category_id'];

        $site = getSite();
        $category = Categories::findOrFail($category_id);
        $data = get_category_details($site, $category, 20);
        $getResource = [];
        foreach ($data as $item) {
            $item->fav = check_favourite($site, $get_data['androidId'], $item->id);
            $getResource[] = new MusicResource($item);
        }
        $data = [
            'ONLINE_MP3_APP' => $getResource,
            'total_records' => $data->total(),
            "status_code" => 200
        ];
        return response()->json($data);
    }

    public function home_slider_songs()
    {
        $get_data = $this->checkSignSalt($_POST['data']);
        $category_id = $get_data['slider_id'];
        return $this->song_by_category($category_id);
    }

    public function home_recently_songs()
    {
        $site = getSite();
        $get_data = $this->checkSignSalt($_POST['data']);
        $songs_ids = explode(',', $get_data['songs_ids']);
        $musics = Musics::whereIN('id', $songs_ids)->get();
        foreach ($musics as $music) {
            $music->fav = check_favourite($site, $get_data['androidId'], $music->id);
            $recently_songs[] = new MusicResource($music);
        }
        $data = [
            'ONLINE_MP3_APP' => $recently_songs,
            "status_code" => 200
        ];
        return response()->json($data);
    }

    public function category()
    {
        $get_data = $this->checkSignSalt($_POST['data']);
        $site = getSite();
        $categories = get_categories($site, 10);
        $getResource = CategoryResource::collection($categories);
        $data = [
            'ONLINE_MP3_APP' => $getResource,
            "total_records" => $categories->count(),
            "status_code" => 200
        ];
        return response()->json($data);
    }

    public function all_musics()
    {
        $get_data = $this->checkSignSalt($_POST['data']);
        $site = getSite();
        switch ($site->load_categories) {

            case 1:
                $all_musics = get_songs($site, 10, 'music_view_count');
                break;
            case 2:
                $all_musics = get_songs($site, 10, 'updated_at');
                break;
            case 3:
                $all_musics = get_songs($site, 10, 'music_like_count');
                break;
            case 4:
                $all_musics = get_songs($site, 10, 'music_title');
                break;
            default:
                $all_musics = get_songs($site, 10);
                break;
        }
        $getResource = MusicResource::collection($all_musics);
        $data = [
            'ONLINE_MP3_APP' => $getResource,
            "total_records" => $all_musics->total(),
            "status_code" => 200
        ];
        return response()->json($data);

    }

    public function latest_songs()
    {
        $get_data = $this->checkSignSalt($_POST['data']);
        $site = getSite();
        $latest_songs = get_songs($site, 10, 'id');
        $getResource = MusicResource::collection($latest_songs);
        $data = [
            'ONLINE_MP3_APP' => $getResource,
            "total_records" => $latest_songs->total(),
            "status_code" => 200
        ];
        return response()->json($data);

    }

    public function song_view()
    {
        $get_data = $this->checkSignSalt($_POST['data']);
        $song_id = $get_data['post_id'];
        $update_view = update_song_view($song_id);
        $data = [
            'ONLINE_MP3_APP' => [
                [
                    'views' => $update_view->music_view_count
                ]
            ],
            "status_code" => 200
        ];

        return response()->json($data);
    }

    public function song_download()
    {
        $get_data = $this->checkSignSalt($_POST['data']);
        $song_id = $get_data['post_id'];
        $update_view = update_song_download($song_id);
        $data = [
            'ONLINE_MP3_APP' => [
                [
                    'download' => $update_view->music_download_count
                ]
            ],
            "status_code" => 200
        ];

        return response()->json($data);
    }

    public function song_favourite()
    {
        $site = getSite();
        $get_data = $this->checkSignSalt($_POST['data']);
        $androidId = $get_data['androidId'];
        $musicId = $get_data['post_id'];
        $response = update_song_favourite($site, $androidId, $musicId);
        $data = [
            'ONLINE_MP3_APP' => [$response],
            "status_code" => 200
        ];
        return response()->json($data);
    }

    public function user_favourite_songs()
    {
        $get_data = $this->checkSignSalt($_POST['data']);
        $androidId = $get_data['androidId'];
        $site = getSite();
        $getMusic = get_song_favourite($site, $androidId, 10);


        foreach ($getMusic as $music) {
            try {
                $music->music->fav = true;
                $getResource[] = new MusicResource($music->music);
            } catch (\Exception $ex) {
                Log::error('Message: favorite ' . $ex->getMessage() . '--: ' . $music->id . ' -----' . $ex->getLine());
            }
        }


        $data = [
            'ONLINE_MP3_APP' => $getResource,
            "total_records" => $getMusic->total(),
            "status_code" => 200
        ];
        return response()->json($data);
    }

    public function search()
    {
        $get_data = $this->checkSignSalt($_POST['data']);
        $site = getSite();
        $getMusicResource = [];
        $search = $get_data['search_text'];

        $data['ONLINE_MP3_APP'] = [];
        $result_music = get_search_music($site, $search, 10);
        foreach ($result_music as $music) {
            $music->fav = true;
            $getMusicResource[] = new MusicResource($music);

        }

        $result_categories = get_search_categories($site, $search, 10);
        $getCategoriesResource = CategoryResource::collection($result_categories);
        $data = [
            'ONLINE_MP3_APP' => [
                'category_list' => $getCategoriesResource,
                'songs_list' => $getMusicResource,
            ],
            "status_code" => 200
        ];
        return response()->json($data);
    }

    public function search_single()
    {
        $get_data = $this->checkSignSalt($_POST['data']);
        $search_type = $get_data['search_type'];
        $search = $get_data['search_text'];
        $site = getSite();
        switch ($search_type) {
            case 'category':
                $result = get_search_categories($site, $search, 10);
                $resource = CategoryResource::collection($result);
                break;
            case 'songs':
                $resource = [];
                $result = get_search_music($site, $search, 10);
                foreach ($result as $music) {
                    $music->fav = true;
                    $resource[] = new MusicResource($music);
                }
        }
        $data = [
            'ONLINE_MP3_APP' => $resource,
            'total_records' => $result->total(),
            "status_code" => 200
        ];
        return response()->json($data);

    }


}
