<?php

namespace App\Http\Controllers\Api\v4;

use App\Categories;
use App\Http\Controllers\Controller;
use App\Http\Resources\v4\CategoriesResource;
use App\Http\Resources\v4\WallpaperResource;
use App\ListIP;
use App\Sites;
use App\Wallpapers;
use Carbon\Carbon;
use Illuminate\Http\Request;


class ApiController extends Controller
{


    public function admob(){

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
        $site =  Sites::where('site_web', $domain)->first();
        $ads = json_decode($site->site_ads,true);

        $listIp = ListIP::where('ip_address',$ipaddress)->where('id_site',$site->id)->whereDate('created_at', Carbon::today())->first();

        if (!$listIp) {
            ListIP::create([
                'ip_address' => $ipaddress,
                'id_site' => $site->id,
                'count' => 1
            ]);
        } else {
            $listIp->update(['count' => $listIp->count + 1]);
        }



        $result = [
            'provider' => $ads ? $ads['ads_provider'] : ''  ,
            'admob_banner' => $ads ? ( $ads['AdMob_Banner_Ad_Unit_ID'] ? $ads['AdMob_Banner_Ad_Unit_ID'] : 'ca-app-pub-3940256099942544/6300978111') : 'ca-app-pub-3940256099942544/6300978111' ,
            'admob_reward' => $ads ?  ($ads['AdMob_App_Reward_Ad_Unit_ID'] ?  $ads['AdMob_App_Reward_Ad_Unit_ID']: 'ca-app-pub-3940256099942544/5224354917' ): 'ca-app-pub-3940256099942544/5224354917',
            'admob_open' => $ads ? ( $ads['AdMob_App_Open_Ad_Unit_ID'] ? $ads['AdMob_App_Open_Ad_Unit_ID']: 'ca-app-pub-3940256099942544/3419835294' ) :'ca-app-pub-3940256099942544/3419835294',
            'admob_native' => $ads ? ( $ads['AdMob_Native_Ad_Unit_ID'] ? $ads['AdMob_Native_Ad_Unit_ID'] : 'ca-app-pub-3940256099942544/2247696110' ): 'ca-app-pub-3940256099942544/2247696110'  ,
            'admob_interstitial' => $ads ? ($ads['AdMob_Interstitial_Ad_Unit_ID'] ?  $ads['AdMob_Interstitial_Ad_Unit_ID']: 'ca-app-pub-3940256099942544/1033173712' ): 'ca-app-pub-3940256099942544/1033173712' ,

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
            "packagename"=> "https://play.google.com/store/apps/dev?id=5703447331110116266",
            "privacy"=> "https://google.com",
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
        $site =  Sites::where('site_web', $domain)->first();

        $checkBlock = 0;
        if (checkBlockIp()) {
            $checkBlock = 1;
        }



//        dd( $this->getWallpaper('id',$site->id,0,'<>',10));



        $dataArray = Wallpapers::with('tags')
            ->whereHas('categories', function ($query) use ($checkBlock, $site) {
                $query->where('category_checked_ip', $checkBlock)
                    ->where('site_id',$site->id);
            })
                ->limit(10)
                ->get()
            ->toArray()
        ;

        $data = array();
//        if (checkBlockIp()) {
            array_push($data, [
//                'name' => 'latest', 'data' => $this->getWallpaper('id',$site->id,$checkBlock,'<>',10)
                'name' => 'latest', 'data' => $this->getWallpaper1($dataArray,'id')

            ]);
            array_push($data, [
//                'name' => 'popular', 'data' => $this->getWallpaper('wallpaper_view_count',$site->id,$checkBlock,'<>',10)
                'name' => 'popular', 'data' => $this->getWallpaper1($dataArray,'wallpaper_view_count')
            ]);
            array_push($data, [
//                'name' => 'random', 'data' => $this->getWallpaper(null,$site->id,$checkBlock,'<>',10)
                'name' => 'random', 'data' => $this->getWallpaper1($dataArray)
            ]);
            array_push($data, [
//                'name' => 'downloaded', 'data' => $this->getWallpaper('wallpaper_like_count',$site->id,$checkBlock,'<>',10)
                'name' => 'downloaded', 'data' => $this->getWallpaper1($dataArray,'wallpaper_download_count')
            ]);
            array_push($data, [
//                'name' => 'live', 'data' => $this->getWallpaper('id',$site->id,$checkBlock,'=',10)
                'name' => 'live', 'data' => $this->getWallpaper1($dataArray,'id')
            ]);
//        } else {
//            array_push($data, [
//                'name' => 'latest', 'data' => $this->getWallpaper('id',$site->id,0,'<>',10)
//            ]);
//            array_push($data, [
//                'name' => 'popular', 'data' => $this->getWallpaper('wallpaper_view_count',$site->id,0,'<>',10)
//            ]);
//            array_push($data, [
//                'name' => 'random', 'data' => $this->getWallpaper(null,$site->id,0,'<>',10)
//            ]);
//            array_push($data, [
//                'name' => 'downloaded', 'data' => $this->getWallpaper('wallpaper_like_count',$site->id,0,'<>',10)
//            ]);
//            array_push($data, [
//                'name' => 'live', 'data' => $this->getWallpaper('id',$site->id,0,'=',10)
//            ]);
//        }

        return $data;
    }

    public function getWallpaper1($data,$order = null){
        if ($order){
            usort($data, function($a, $b) {
                $dataResult =  $a['id'] <=> $b['id'];
            });
        }else{
            $dataResult = shuffle($data);
        }
        $dataResult = WallpaperResource::collection($data);
        return $dataResult;

    }


    private  function getWallpaper($order, $siteID, $checkBlock, $gif, $limit){
        if(isset($order)){
            $data = Wallpapers::with('tags')
                ->where('image_extension', $gif,'image/gif')
                ->whereHas('categories', function ($query) use ($siteID, $checkBlock) {
                    $query->where('category_checked_ip', $checkBlock)
                        ->where('site_id',$siteID);
                })
                ->orderBy($order, 'desc')
                ->paginate($limit)->toArray();
        }else{
            $data = Wallpapers::with('tags')
                ->where('image_extension', $gif,'image/gif')
                ->whereHas('categories', function ($query) use ($siteID, $checkBlock) {
                    $query->where('category_checked_ip', $checkBlock)
                        ->where('site_id',$siteID);
                })
                ->inRandomOrder()
                ->paginate($limit)->toArray();
        }
        $dataResult = WallpaperResource::collection($data);
        return $dataResult;
    }

    public function viewWallpaper(Request $request){
        $data = Wallpapers::with('tags')->findOrFail($request['id']);
        $data->wallpaper_view_count = $data->wallpaper_view_count + 1;
        $data->save();
        return New WallpaperResource($data);
    }

    public function wallpaper(){
        $domain = $_SERVER['SERVER_NAME'];
        $site =  Sites::where('site_web', $domain)->first();
        if (checkBlockIp()) {
            $data = $this->getWallpaper('id',$site->id,1,'<>',10);
        } else {
            $data = $this->getWallpaper('id',$site->id,0,'<>',10);
        }
        $dataResult['current_page'] = $data->currentPage();
        $dataResult['last_page'] = $data->lastPage();
        $dataResult['total'] = $data->total();
        $dataResult['data'] = $data;
        return $dataResult;
    }

    public function popular(){
        $domain = $_SERVER['SERVER_NAME'];
        $site =  Sites::where('site_web', $domain)->first();

        if (checkBlockIp()) {
            $data = $this->getWallpaper('wallpaper_view_count',$site->id,1,'<>',10);
        } else {
            $data = $this->getWallpaper('wallpaper_view_count',$site->id,0,'<>',10);
        }
        $dataResult['current_page'] = $data->currentPage();
        $dataResult['last_page'] = $data->lastPage();
        $dataResult['total'] = $data->total();
        $dataResult['data'] = $data;
        return $dataResult;
    }

    public function download(){

        $domain = $_SERVER['SERVER_NAME'];
        $site =  Sites::where('site_web', $domain)->first();

        if (checkBlockIp()) {
            $data = $this->getWallpaper('wallpaper_view_count',$site->id,1,'<>',10);
        } else {
            $data = $this->getWallpaper('wallpaper_download_count',$site->id,0,'<>',10);
        }
        $dataResult['current_page'] = $data->currentPage();
        $dataResult['last_page'] = $data->lastPage();
        $dataResult['total'] = $data->total();
        $dataResult['data'] = $data;
        return $dataResult;
    }

    public function random(){
        $domain = $_SERVER['SERVER_NAME'];
        $site =  Sites::where('site_web', $domain)->first();
        if (checkBlockIp()) {
            $data = $this->getWallpaper(null,$site->id,1,'<>',10);
        } else {
            $data = $this->getWallpaper(null,$site->id,0,'<>',10);
        }
        $dataResult['current_page'] = $data->currentPage();
        $dataResult['last_page'] = $data->lastPage();
        $dataResult['total'] = $data->total();
        $dataResult['data'] = $data;
        return $dataResult;
    }

    public function live(){
        $domain = $_SERVER['SERVER_NAME'];
        $site =  Sites::where('site_web', $domain)->first();
        if (checkBlockIp()) {
            $data = $this->getWallpaper(null,$site->id,1,'=',10);
        } else {
            $data = $this->getWallpaper(null,$site->id,0,'=',10);
        }
        $dataResult['current_page'] = $data->currentPage();
        $dataResult['last_page'] = $data->lastPage();
        $dataResult['total'] = $data->total();
        $dataResult['data'] = $data;
        return $dataResult;
    }

    public function categories(){
        $domain=$_SERVER['SERVER_NAME'];
        $site = Sites::where('site_web',$domain)->first();
        $load_categories = $site->load_categories;

        if (checkBlockIp()) {

            if($load_categories == 0 ){
                $data = $site
                    ->categories()
                    ->where('category_checked_ip', 1)
                    ->inRandomOrder()
                    ->paginate(10);
            }
            elseif($load_categories == 1 ){
                $data = $site
                    ->categories()
                    ->where('category_checked_ip', 1)
                    ->orderBy('category_view_count','desc')
                    ->paginate(10);
            }
            elseif($load_categories == 2 ){
                $data = $site
                    ->categories()
                    ->where('category_checked_ip', 1)
                    ->orderBy('updated_at','desc')
                    ->paginate(10);
            }

        } else {

            if($load_categories == 0 ){
                $data = $site
                    ->categories()
                    ->where('category_checked_ip', 0)
                    ->inRandomOrder()
                    ->paginate(10);
            }
            elseif($load_categories == 1 ){
                $data = $site
                    ->categories()
                    ->where('category_checked_ip',0)
                    ->orderBy('category_view_count','desc')
                    ->paginate(10);
            }
            elseif($load_categories == 2 ){
                $data = $site
                    ->categories()
                    ->where('category_checked_ip', 0)
                    ->orderBy('updated_at','desc')
                    ->paginate(10);
            }
        }
        $result['current_page'] = $data->currentPage();
        $result['last_page'] = $data->lastPage();
        $result['total'] = $data->total();
        $result['data'] = CategoriesResource::collection($data);
        return $result;
    }

    public function cid(Request $request){

        $domain = $_SERVER['SERVER_NAME'];
        $site = Sites::where('site_web',$domain)->first();
        $load_wallpapers_category = $site->load_wallpapers_category;


        if($load_wallpapers_category==0){
            $wallpapers = Categories::findOrFail($request['id'])
                ->wallpaper()
                ->with('tags')
                ->distinct()
                ->inRandomOrder()
                ->paginate(21);
        }
        elseif($load_wallpapers_category==1){
            $wallpapers = Categories::findOrFail($request['id'])
                ->wallpaper()
                ->with('tags')
                ->distinct()
                ->orderBy('wallpaper_like_count','desc')
                ->paginate(21);
        }
        elseif($load_wallpapers_category==2){
            $wallpapers = Categories::findOrFail($request['id'])
                ->wallpaper()
                ->with('tags')
                ->distinct()
                ->orderBy('wallpaper_view_count','desc')
                ->paginate(21);
        }
        elseif($load_wallpapers_category==3){
            $wallpapers = Categories::findOrFail($request['id'])
                ->wallpaper()
                ->with('tags')
                ->distinct()
                ->orderBy('created_at','desc')
                ->paginate(21);
        }
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
