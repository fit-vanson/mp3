<?php

namespace App\Http\Controllers\Api\v0;

use App\Categories;
use App\Http\Controllers\Controller;

use App\Http\Resources\v0\CategoriesResource;
use App\Http\Resources\v0\WallpapersResource;
use App\Sites;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{

    public function index()
    {
        $domain=$_SERVER['SERVER_NAME'];
        if(checkBlockIp()){
            $data = Sites::where('sites.site_web',$domain)
                ->with(['categories'=>function ($q){
                    $q->withCount('wallpaper')
                        ->where('category_checked_ip', 1);
                }])
                ->first();
        } else{
            $data = Sites::where('sites.site_web',$domain)
                ->with(['categories'=>function ($q){
                    $q->withCount('wallpaper')
                        ->where('category_checked_ip', 0);
                }])
                ->first();
        }
        return CategoriesResource::collection($data->categories);
    }
    public function getWallpapers($id)
    {
        $page_limit = 12;
        $limit= ($_GET['page']-1) * $page_limit ;
        try{
            $wallpapers = Categories::findOrFail($id)
                ->wallpaper()
                ->where('image_extension', '<>', 'image/gif')
                ->orderBy('wallpaper_like_count', 'desc')
                ->skip($limit)
                ->take($page_limit)
                ->get();
            Categories::findOrFail($id)->increment('category_view_count');
            return WallpapersResource::collection($wallpapers);
        }catch (\Exception $e){
            return response()->json(['warning' => ['This Category is not exist']], 200);
        }

    }
}
