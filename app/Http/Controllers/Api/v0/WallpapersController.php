<?php

namespace App\Http\Controllers\Api\v0;

use App\Categories;
use App\Http\Controllers\Controller;

use App\Http\Resources\v0\CategoriesResource;
use App\Http\Resources\v0\FeatureWallpaperResource;
use App\Http\Resources\v0\WallpapersResource;
use App\ListIP;
use App\Sites;
use App\VisitorFavorite;
use App\Visitors;
use App\Wallpapers;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WallpapersController extends Controller
{
    public function show($id,$device_id)
    {
        $domain=$_SERVER['SERVER_NAME'];

        $wallpaper = Wallpapers::findOrFail($id);

        $data = Sites::with('categories')
            ->where('site_web',$domain)->first();
        $visitorFavorite = VisitorFavorite::where([
            'wallpaper_id' => $id,
            'site_id' => Sites::where('site_web', $domain)->value('id'),
            'visitor_id' => Visitors::where('device_id', $device_id)->value('id')
            ]
        )->first();

        if($visitorFavorite){
            return response()->json([
                'categories' =>
                    CategoriesResource::collection($data->categories),
//                    array(new CategoryResource($wallpaper->category)),
                'id' => $wallpaper->id,
                'name' => $wallpaper->wallpaper_name,
                'thumbnail_image' => asset('storage/wallpapers/thumbnails/'. $wallpaper->wallpaper_image),
                'detail_image' => asset('storage/wallpapers/thumbnails/'. $wallpaper->wallpaper_image),
                'download_image' => asset('storage/wallpapers/'. $wallpaper->wallpaper_image),
                'liked' => 1,
                'like_count' => $wallpaper->wallpaper_like_count,
                'views' => $wallpaper->wallpaper_view_count,
                'feature' => $wallpaper->wallpaper_feature,
                'created_at' => $wallpaper->created_at->format('d/m/Y'),
            ]);
        }else{
            return response()->json([
                'categories' =>
                    CategoriesResource::collection($wallpaper->categories),
//                    array(new CategoryResource($wallpaper->category)),
                'id' => $wallpaper->id,
                'name' => $wallpaper->wallpaper_name,
                'thumbnail_image' => asset('storage/wallpapers/thumbnails/'. $wallpaper->wallpaper_image),
                'detail_image' => asset('storage/wallpapers/thumbnails/'. $wallpaper->wallpaper_image),
                'download_image' => asset('storage/wallpapers/'. $wallpaper->wallpaper_image),
                'liked' => 0,
                'like_count' => $wallpaper->wallpaper_like_count,
                'views' => $wallpaper->wallpaper_view_count,
                'feature' => $wallpaper->wallpaper_feature,
                'created_at' => $wallpaper->created_at->format('d/m/Y'),
            ]);
        }
    }

    public function getFeatured()
    {

        $domain=$_SERVER['SERVER_NAME'];
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
        $load_feature=$site->load_view_by;

        if (checkBlockIp()) {
            if($load_feature ==0){
                $data = Sites::where('site_web',$domain)->first()
                    ->categories()
                    ->where('category_checked_ip',1)
                    ->inRandomOrder()
                    ->get();
                $getResource= FeatureWallpaperResource::collection($data);
            }elseif($load_feature ==1){
                $data = Sites::where('site_web',$domain)->first()
                    ->categories()
                    ->where('category_checked_ip',1)
                    ->orderBy('category_order', 'desc')
                    ->get();
                $getResource= FeatureWallpaperResource::collection($data);
            }elseif($load_feature ==2){
                $data = Sites::where('site_web',$domain)->first()
                    ->categories()
                    ->where('category_checked_ip',1)
                    ->orderBy('category_view_count', 'desc')
                    ->get();
                $getResource= FeatureWallpaperResource::collection($data);
            }elseif($load_feature ==3){
                $data = Wallpapers::with('tags')
                    ->where('image_extension', '<>','image/gif')
                    ->whereHas('categories', function ($query) use ($site) {
                        $query->where('category_checked_ip', 1)
                            ->where('site_id',$site->id);
                    })
                    ->inRandomOrder()
                    ->take(12)
                    ->get();
                $getResource = WallpapersResource::collection($data);
            }
        } else {
            if($load_feature ==0){
                $data = Sites::where('site_web',$domain)->first()
                    ->categories()
                    ->where('category_checked_ip',0)
                    ->inRandomOrder()
                    ->get();
                $getResource= FeatureWallpaperResource::collection($data);
            }elseif($load_feature ==1){
                $data = Sites::where('site_web',$domain)->first()
                    ->categories()
                    ->where('category_checked_ip',0)
                    ->orderBy('category_order', 'desc')
                    ->get();
                $getResource= FeatureWallpaperResource::collection($data);
            }elseif($load_feature ==2){
                $data = Sites::where('site_web',$domain)->first()
                    ->categories()
                    ->where('category_checked_ip',0)
                    ->orderBy('category_view_count', 'desc')
                    ->get();
                $getResource= FeatureWallpaperResource::collection($data);

            }elseif($load_feature ==3){
                $data = Wallpapers::with('tags')
                    ->where('image_extension', '<>','image/gif')
                    ->whereHas('categories', function ($query) use ($site) {
                        $query->where('category_checked_ip', 0)
                            ->where('site_id',$site->id);
                    })
                    ->inRandomOrder()
                    ->take(12)
                    ->get();
                $getResource = WallpapersResource::collection($data);
            }
        }

        return response()->json([
            'message'=>'save ip successs',
            'ad_switch'=>$site->ad_switch,
            'data'=>$getResource,
        ]);
    }
    public function getPopulared()
    {
        $page_limit = 12;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $limit=($page-1) * $page_limit;
        $domain=$_SERVER['SERVER_NAME'];
        $site = Sites::where('site_web',$domain)->first();

        if (checkBlockIp()){
            $data = Wallpapers::with('tags')
                ->where('image_extension', '<>','image/gif')
                ->whereHas('categories', function ($query) use ($site) {
                    $query->where('category_checked_ip', 1)
                        ->where('site_id',$site->id);
                })
                ->orderBy('wallpaper_like_count','desc')
                ->skip($limit)
                ->take($page_limit)
                ->get();

        }else{
            $data = Wallpapers::with('tags')
                ->where('image_extension', '<>','image/gif')
                ->whereHas('categories', function ($query) use ($site) {
                    $query->where('category_checked_ip', 0)
                        ->where('site_id',$site->id);
                })
                ->orderBy('wallpaper_like_count','desc')
                ->skip($limit)
                ->take($page_limit)
                ->get();
        }
        return WallpapersResource::collection($data);
    }
    public function getNewest()
    {
        $page_limit = 12;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $limit=($page-1) * $page_limit;

        $domain=$_SERVER['SERVER_NAME'];
        $site = Sites::where('site_web',$domain)->first();


        if (checkBlockIp()){
            $data = Wallpapers::with('tags')
                ->where('image_extension', '<>','image/gif')
                ->whereHas('categories', function ($query) use ($site) {
                    $query->where('category_checked_ip', 1)
                        ->where('site_id',$site->id);
                })
                ->orderBy('created_at','desc')
                ->skip($limit)
                ->take($page_limit)
                ->get();

        }else{
            $data = Wallpapers::with('tags')
                ->where('image_extension', '<>','image/gif')
                ->whereHas('categories', function ($query) use ($site) {
                    $query->where('category_checked_ip', 0)
                        ->where('site_id',$site->id);
                })
                ->orderBy('created_at','desc')
                ->skip($limit)
                ->take($page_limit)
                ->get();
        }

        return WallpapersResource::collection($data);
    }
}
