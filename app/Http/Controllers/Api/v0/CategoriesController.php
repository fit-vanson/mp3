<?php

namespace App\Http\Controllers\Api\v0;

use App\Categories;
use App\Http\Controllers\Controller;

use App\Http\Resources\v0\CategoriesResource;
use App\Http\Resources\v0\WallpapersResource;
use App\Sites;
use Illuminate\Http\Request;
//use \Staudenmeir\EloquentEagerLimit\HasEagerLimit;

class CategoriesController extends Controller
{

    public function index()
    {
        $domain=$_SERVER['SERVER_NAME'];
        if(checkBlockIp()){
            $data = Sites::where('sites.site_web',$domain)
                ->first()
                ->categories()
                ->where('category_checked_ip', 1)
                ->get();
        } else{
            $data = Sites::where('sites.site_web',$domain)
                ->first()
                ->categories()
                ->where('category_checked_ip', 0)
                ->get();
        }

        return CategoriesResource::collection($data);
    }
    public function getWallpapers($id)
    {
        $page_limit = 12;
        $limit= ($_GET['page']-1) * $page_limit ;
        try{

            $data = Categories::where('id',$id)
                ->with(['wallpaper'=>function ($q) use ($page_limit) {
                    $q
                        ->where('image_extension', '<>', 'image/gif')
                        ->distinct()
                        ->orderBy('wallpaper_like_count', 'desc')
                        ->paginate($page_limit);

                }])
                ->first();
//
//            dd($data);
//
//
//            $wallpapers = Categories::findOrFail($id)
//                ->wallpaper()
//                ->where('image_extension', '<>', 'image/gif')
//                ->distinct()
//                ->orderBy('wallpaper_like_count', 'desc')
//                ->skip($limit)
//                ->take($page_limit)
//                ->get();
//            dd($wallpapers);
//            Categories::findOrFail($id)->increment('category_view_count');
            return WallpapersResource::collection($data->wallpaper);
        }catch (\Exception $e){
            return response()->json(['warning' => ['This Category is not exist']], 200);
        }

    }
}
