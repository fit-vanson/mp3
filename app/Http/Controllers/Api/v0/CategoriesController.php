<?php

namespace App\Http\Controllers\Api\v0;

use App\Categories;
use App\Http\Controllers\Controller;

use App\Http\Resources\v0\CategoriesResource;
use App\Http\Resources\v0\WallpapersResource;
use App\Sites;

class CategoriesController extends Controller
{
    public function index()
    {
        $domain = $_SERVER['SERVER_NAME'];
        $site = Sites::where('site_web',$domain)->first();
        $load_categories = $site->load_categories;

        if(checkBlockIp()){
            if($load_categories == 0 ){
                $data = $site
                    ->categories()
                    ->where('category_checked_ip', 1)
                    ->withCount('wallpaper')
                    ->having('wallpaper_count', '>', 0)
                    ->inRandomOrder()
                    ->get();
            }
            elseif($load_categories == 1 ){
                $data = $site
                    ->categories()
                    ->where('category_checked_ip', 1)
                    ->withCount('wallpaper')
                    ->having('wallpaper_count', '>', 0)
                    ->orderBy('category_view_count','desc')
                    ->get();
            }
            elseif($load_categories == 2 ){
                $data = $site
                    ->categories()
                    ->where('category_checked_ip', 1)
                    ->withCount('wallpaper')
                    ->having('wallpaper_count', '>', 0)
                    ->orderBy('updated_at','desc')
                    ->get();
            }
        } else{
            if($load_categories == 0 ){
                $data = $site
                    ->categories()
                    ->where('category_checked_ip', 0)
                    ->withCount('wallpaper')
                    ->having('wallpaper_count', '>', 0)
                    ->inRandomOrder()
                    ->get();
            }
            elseif($load_categories == 1 ){
                $data = $site
                    ->categories()

                    ->where('category_checked_ip', 0)
                    ->withCount('wallpaper')
                    ->having('wallpaper_count', '>', 0)
                    ->orderBy('category_view_count','desc')
                    ->get();
            }
            elseif($load_categories == 2 ){
                $data = $site
                    ->categories()
                    ->where('category_checked_ip', 0)
                    ->withCount('wallpaper')
                    ->having('wallpaper_count', '>', 0)
                    ->orderBy('updated_at','desc')
                    ->get();
            }
        }

        return CategoriesResource::collection($data);
    }
    public function getWallpapers($id)
    {
        $domain = $_SERVER['SERVER_NAME'];
        $site = Sites::where('site_web',$domain)->first();
        $load_wallpapers_category = $site->load_wallpapers_category;
        $page_limit = 12;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $limit= ($page-1) * $page_limit ;
        try{
            if($load_wallpapers_category==0){
                $wallpapers = Categories::findOrFail($id)
                    ->wallpaper()
                    ->distinct()
                    ->inRandomOrder()
                    ->skip($limit)
                    ->take($page_limit)
                    ->get();
            }
            elseif($load_wallpapers_category==1){
                $wallpapers = Categories::findOrFail($id)
                    ->wallpaper()
                    ->distinct()
                    ->orderBy('wallpaper_like_count','desc')
                    ->skip($limit)
                    ->take($page_limit)
                    ->get();
            }
            elseif($load_wallpapers_category==2){
                $wallpapers = Categories::findOrFail($id)
                    ->wallpaper()
                    ->distinct()
                    ->orderBy('wallpaper_view_count','desc')
                    ->skip($limit)
                    ->take($page_limit)
                    ->get();
            }
            elseif($load_wallpapers_category==3){
                $wallpapers = Categories::findOrFail($id)
                    ->wallpaper()
                    ->distinct()
                    ->orderBy('created_at','desc')
                    ->skip($limit)
                    ->take($page_limit)
                    ->get();
            }

            return WallpapersResource::collection($wallpapers);
        }catch (\Exception $e){
            return response()->json(['warning' => ['This Category is not exist']], 200);
        }

    }
}
