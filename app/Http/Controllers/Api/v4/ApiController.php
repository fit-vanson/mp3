<?php

namespace App\Http\Controllers\Api\v4;

use App\Categories;
use App\Http\Controllers\Controller;
use App\Http\Resources\v4\CategoriesResource;
use App\Http\Resources\v4\WallpaperResource;
use App\Sites;
use App\Wallpapers;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function admob(){
        $domain = $_SERVER['SERVER_NAME'];
        $site =  Sites::where('site_web', $domain)->first();
        $ads = json_decode($site->site_ads,true);



        $result = [
            'provider' => $ads ? $ads['ads_provider'] : ''  ,
            'admob_banner' => $ads ? $ads['AdMob_Banner_Ad_Unit_ID']: ''  ,
            'admob_reward' => $ads ? $ads['AdMob_App_Reward_Ad_Unit_ID']: ''  ,
            'admob_open' => $ads ? $ads['AdMob_App_Open_Ad_Unit_ID']: ''  ,
            'admob_native' => $ads ? $ads['AdMob_Native_Ad_Unit_ID']: ''  ,
            'admob_interstitial' => $ads ? $ads['AdMob_Interstitial_Ad_Unit_ID']: ''  ,

            'applovin_banner' => $ads ? $ads['applovin_banner']: ''  ,
            'applovin_interstitial' => $ads ? $ads['applovin_interstitial']: ''  ,
            'applovin_reward' => $ads ? $ads['applovin_reward']: ''  ,

            'startapp_id' => $ads ? $ads['startapp_id']: ''  ,

            'ironsource_id' => $ads ? $ads['ironsource_id']: ''  ,

            'banner_enable' => $site->ad_switch,
            'interstitial_enable' => $site->ad_switch,
            'reward_enable' => $site->ad_switch,
            'open_enable' => $site->ad_switch,
        ];

        return $result;

    }
    public function settings(){

        $settings = [
            "onesignal_id"=> "01f96de5-e775-43a8-b9d0-91a720d65912",
            "onesignal_rest"=> "NmMyOGNmNzQtNWM4MC00MjgxLWJiOTEtNTljNjA0YmI3YjA4",
            "packagename"=> "https=>//play.google.com/store/apps/dev?id=5703447331110116266",
            "privacy"=> "https=>//google.com",
            "layout"=> "dark-layout",
            "server_key"=> "XjjXvKKAxjYmJjjOdFSKdAOlZwTkvlQrXRShNQlIzRedUzPifp",
            "wallpaper_columns"=> "3",
            "show_view_count"=> "false",
            "show_categories"=> "true",
            "setting_icon"=> "icon/1649458789_06bbb5ee95a644288cdb.png",
            "home_icon"=> "icon/1649681235_555f82c4bc2ec4b64eb2.png",
            "categories_icon"=> "icon/1649681235_e2fb6d0d3a9eb20749cc.png",
            "popular_icon"=> "icon/1649681235_e232efe0fe4cbcc039ad.png",
            "favourite_icon"=> "icon/1649681235_dd3df73bc9e08ec4e699.png",
            "back_icon"=> "icon/1649648137_4f61c645b41a456a3460.png",
            "download_icon"=> "icon/1649648137_02f2c6b2aa2168c0dc85.png",
            "set_wallpaper_icon"=> "icon/1649680653_5e6fb36cd6418c1f575e.png",
            "favourite_enable_icon"=> "icon/1649648137_09fd2adad5969e30aea6.png",
            "favourite_disable_icon"=> "icon/1649648137_41d6e7c4b84867caff64.png",
            "background_color"=> "#191B21",
            "header_color"=> "#0F1013",
            "filter_icon"=> "icon/1649613118_8d1ea92b2aca4a160143.png"
        ];

        return json_encode($settings);

    }
    public function home(){
        $domain = $_SERVER['SERVER_NAME'];
        $data = array();
        if (checkBlockIp()) {
            array_push($data, [
                'name' => 'latest', 'data' => $this->getWallpaper('id',$domain,1,'<>',10)
            ]);
            array_push($data, [
                'name' => 'popular', 'data' => $this->getWallpaper('view',$domain,1,'<>',10)
            ]);
            array_push($data, [
                'name' => 'random', 'data' => $this->getWallpaper('wallpaper_name',$domain,1,'<>',10)
            ]);
            array_push($data, [
                'name' => 'downloaded', 'data' => $this->getWallpaper('like',$domain,1,'<>',10)
            ]);
            array_push($data, [
                'name' => 'live', 'data' => $this->getWallpaper('id',$domain,1,'=',10)
            ]);
        } else {
            array_push($data, [
                'name' => 'latest', 'data' => $this->getWallpaper('id',$domain,0,'<>',10)
            ]);
            array_push($data, [
                'name' => 'popular', 'data' => $this->getWallpaper('view',$domain,0,'<>',10)
            ]);
            array_push($data, [
                'name' => 'random', 'data' => $this->getWallpaper(null,$domain,0,'<>',10)
            ]);
            array_push($data, [
                'name' => 'downloaded', 'data' => $this->getWallpaper('like',$domain,0,'<>',10)
            ]);
            array_push($data, [
                'name' => 'live', 'data' => $this->getWallpaper('id',$domain,0,'=',10)
            ]);
        }
        return $data;

    }


    private  function getWallpaper($order, $domain, $checkBlock, $gif, $limit){
        $data = Sites::where('site_web', $domain)->first()
            ->categories()
            ->where('category_checked_ip', $checkBlock)
            ->get();



        $jsonObj = [];
        foreach ($data as $item) {
            foreach (
                $item->wallpaper()
                    ->where('image_extension', $gif, 'image/gif')
                    ->with('tags')
                    ->limit($limit)
                    ->get()
                     as $wall) {

                $tags = [];
                foreach ($wall->tags as $tag){
                    $tags[] = $tag->tag_name;
                }
                $data_arr = [
                    'id' =>$wall->id,
//                    'cid' =>$wall->cate_id,
                    'image' => asset('storage/wallpapers/'.$wall->wallpaper_image),
                    'type' =>$wall->image_extension != 'image/gif' ? 'IMAGE' : 'GIF'  ,
                    'premium' => 0,
                    'tags' => implode(",", $tags),
                    'view' =>$wall->wallpaper_view_count,
                    'download' =>$wall->wallpaper_download_count,
                    'like' =>$wall->wallpaper_like_count,
                ];
                array_push($jsonObj, $data_arr);
            }
        }

        $temp = array_unique(array_column($jsonObj, 'id'));
        $unique_arr = array_intersect_key($jsonObj, $temp);
        $result = array_slice($unique_arr, 0, $limit);

        if(isset($order)){
            usort($result, function($a, $b) use ($order) {
                return $b[$order] <=> $a[$order];
            });
        }else{
             shuffle($result);
        }
        return json_decode(json_encode($result), FALSE);

    }

    public function viewWallpaper(Request $request){

        $model = Wallpapers::with('tags')->findorFail($_GET['id']);
        $model->wallpaper_view_count = $model->wallpaper_view_count + 1;
        $model->save();
        $tags = [];
        foreach ($model->tags as $tag){
            $tags[] = $tag->tag_name;
        }
        $data_arr = [
            'id' =>$model->id,
            'image' => asset('storage/wallpapers/'.$model->wallpaper_image),
            'type' =>$model->image_extension == 'image/jpeg' ? 'IMAGE' : 'GIF'  ,
            'premium' => 0,
            'tags' => implode(",", $tags),
            'view' =>$model->wallpaper_view_count,
            'download' =>$model->wallpaper_download_count,
            'like' =>$model->wallpaper_like_count,
        ];
        return collect($data_arr);
    }

    public function wallpaper(){
        $page_limit = 21;
        $domain = $_SERVER['SERVER_NAME'];
        $site = Sites::where('site_web', $domain)->first();
        if (checkBlockIp()) {
            $data = Wallpapers::with('tags')
                ->where('image_extension', '<>','image/gif')
                ->whereRelation('categories','category_checked_ip',1)
                ->whereRelation('categories','site_id',$site->id)
                ->orderBy('id', 'desc')
                ->paginate($page_limit);
        } else {
            $data = Wallpapers::with('tags')
                ->where('image_extension', '<>','image/gif')
                ->whereRelation('categories','category_checked_ip',0)
                ->whereRelation('categories','site_id',$site->id)
                ->orderBy('id', 'desc')
                ->paginate($page_limit);
        }
        $dataResult['current_page'] = $data->currentPage();
        $dataResult['last_page'] = $data->lastPage();
        $dataResult['total'] = $data->total();
        $dataResult['data'] = WallpaperResource::collection($data);
        return $dataResult;
    }

    public function popular(){
        $page_limit = 21;
        $domain = $_SERVER['SERVER_NAME'];
        $site = Sites::where('site_web', $domain)->first();
        if (checkBlockIp()) {
            $data = Wallpapers::with('tags')
                ->where('image_extension', '<>','image/gif')
                ->whereRelation('categories','category_checked_ip',1)
                ->whereRelation('categories','site_id',$site->id)
                ->orderBy('wallpaper_view_count', 'desc')
                ->paginate($page_limit);
        } else {
            $data = Wallpapers::with('tags')
                ->where('image_extension', '<>','image/gif')
                ->whereRelation('categories','category_checked_ip',0)
                ->whereRelation('categories','site_id',$site->id)
                ->orderBy('wallpaper_view_count', 'desc')
                ->paginate($page_limit);
        }
        $dataResult['current_page'] = $data->currentPage();
        $dataResult['last_page'] = $data->lastPage();
        $dataResult['total'] = $data->total();
        $dataResult['data'] = WallpaperResource::collection($data);
        return $dataResult;
    }

    public function download(){
        $page_limit = 21;
        $domain = $_SERVER['SERVER_NAME'];
        $site = Sites::where('site_web', $domain)->first();
        if (checkBlockIp()) {
            $data = Wallpapers::with('tags')
                ->where('image_extension', '<>','image/gif')
                ->whereRelation('categories','category_checked_ip',1)
                ->whereRelation('categories','site_id',$site->id)
                ->orderBy('wallpaper_download_count', 'desc')
                ->paginate($page_limit);
        } else {
            $data = Wallpapers::with('tags')
                ->where('image_extension', '<>','image/gif')
                ->whereRelation('categories','category_checked_ip',0)
                ->whereRelation('categories','site_id',$site->id)
                ->orderBy('wallpaper_download_count', 'desc')
                ->paginate($page_limit);
        }
        $dataResult['current_page'] = $data->currentPage();
        $dataResult['last_page'] = $data->lastPage();
        $dataResult['total'] = $data->total();
        $dataResult['data'] = WallpaperResource::collection($data);
        return $dataResult;
    }

    public function random(){
        $page_limit = 21;
        $domain = $_SERVER['SERVER_NAME'];
        $site = Sites::where('site_web', $domain)->first();
        if (checkBlockIp()) {
            $data = Wallpapers::with('tags')
                ->where('image_extension', '<>','image/gif')
                ->whereRelation('categories','category_checked_ip',1)
                ->whereRelation('categories','site_id',$site->id)
                ->inRandomOrder()
                ->paginate($page_limit);
        } else {
            $data = Wallpapers::with('tags')
                ->where('image_extension', '<>','image/gif')
                ->whereRelation('categories','category_checked_ip',0)
                ->whereRelation('categories','site_id',$site->id)
                ->inRandomOrder()
                ->paginate($page_limit);
        }
        $dataResult['current_page'] = $data->currentPage();
        $dataResult['last_page'] = $data->lastPage();
        $dataResult['total'] = $data->total();
        $dataResult['data'] = WallpaperResource::collection($data);
        return $dataResult;
    }

    public function live(){
        $page_limit = 21;
        $domain = $_SERVER['SERVER_NAME'];
        $site = Sites::where('site_web', $domain)->first();
        if (checkBlockIp()) {
            $data = Wallpapers::with('tags')
                ->where('image_extension','image/gif')
                ->whereRelation('categories','category_checked_ip',1)
                ->whereRelation('categories','site_id',$site->id)
                ->inRandomOrder()
                ->paginate($page_limit);
        } else {
            $data = Wallpapers::with('tags')
                ->where('image_extension','image/gif')
                ->whereRelation('categories','category_checked_ip',0)
                ->whereRelation('categories','site_id',$site->id)
                ->inRandomOrder()
                ->paginate($page_limit);
        }
        $dataResult['current_page'] = $data->currentPage();
        $dataResult['last_page'] = $data->lastPage();
        $dataResult['total'] = $data->total();
        $dataResult['data'] = WallpaperResource::collection($data);
        return $dataResult;
    }

    public function categories(){
        $domain=$_SERVER['SERVER_NAME'];
        if (checkBlockIp()) {
            $data = Sites::where('site_web',$domain)->first()
                ->categories()
                ->where('category_checked_ip',1)
                ->inRandomOrder()
                ->paginate(10);
        } else {
            $data = Sites::where('site_web',$domain)->first()
                ->categories()
                ->where('category_checked_ip',0)
                ->inRandomOrder()
                ->paginate(10);
        }
        $result['current_page'] = $data->currentPage();
        $result['last_page'] = $data->lastPage();
        $result['total'] = $data->total();
        $result['data'] = CategoriesResource::collection($data);
        return $result;
    }

    public function cid(Request $request){

        $wallpapers = Categories::findOrFail($request['id'])
            ->wallpaper()
            ->distinct()
            ->inRandomOrder()
            ->paginate(21);

        $data['current_page'] = $wallpapers->currentPage();
        $data['last_page'] = $wallpapers->lastPage();
        $data['total'] = $wallpapers->total();
        $data['data'] = WallpaperResource::collection($wallpapers);
        return $data;
    }

    public function hashtag(Request $request){
        $page_limit = 21;
        $domain = $_SERVER['SERVER_NAME'];
        $site = Sites::where('site_web', $domain)->first();
        if (checkBlockIp()) {
            $data = Wallpapers::with('tags')
                ->whereRelation('categories','category_checked_ip',1)
                ->whereRelation('categories','site_id',$site->id)
                ->where('wallpaper_name', 'like', '%'.$request['query'].'%')
                ->orwhereRelation('tags','tag_name','like', '%' . $request['query'] . '%')
                ->inRandomOrder()
                ->paginate($page_limit);
        } else {
            $data = Wallpapers::with('tags')
                ->whereRelation('categories','category_checked_ip',0)
                ->whereRelation('categories','site_id',$site->id)
                ->where('wallpaper_name', 'like', '%'.$request['query'].'%')
                ->orwhereRelation('tags','tag_name','like', '%' . $request['query'] . '%')
                ->inRandomOrder()
                ->paginate($page_limit);
        }
        $dataResult['current_page'] = $data->currentPage();
        $dataResult['last_page'] = $data->lastPage();
        $dataResult['total'] = $data->total();
        $dataResult['data'] = WallpaperResource::collection($data);
        return $dataResult;

    }
}
