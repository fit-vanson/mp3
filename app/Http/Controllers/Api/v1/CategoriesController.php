<?php

namespace App\Http\Controllers\Api\v1;

use App\Categories;
use App\Http\Controllers\Controller;

use App\Http\Resources\v1\CategoriesResource;
use App\Http\Resources\v1\MusicsResource;
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
                    ->withCount('music')
                    ->having('music_count', '>', 0)
                    ->inRandomOrder()
                    ->get();
            }
            elseif($load_categories == 1 ){
                $data = $site
                    ->categories()
                    ->where('category_checked_ip', 1)
                    ->withCount('music')
                    ->having('music_count', '>', 0)
                    ->orderBy('category_view_count','desc')
                    ->get();
            }
            elseif($load_categories == 2 ){
                $data = $site
                    ->categories()
                    ->where('category_checked_ip', 1)
                    ->withCount('music')
                    ->having('music_count', '>', 0)
                    ->orderBy('updated_at','desc')
                    ->get();
            }
        } else{
            if($load_categories == 0 ){
                $data = $site
                    ->categories()
                    ->where('category_checked_ip', 0)
                    ->withCount('music')
                    ->having('music_count', '>', 0)
                    ->inRandomOrder()
                    ->get();
            }
            elseif($load_categories == 1 ){
                $data = $site
                    ->categories()
                    ->where('category_checked_ip', 0)
                    ->withCount('music')
                    ->having('music_count', '>', 0)
                    ->orderBy('category_view_count','desc')
                    ->get();
            }
            elseif($load_categories == 2 ){
                $data = $site
                    ->categories()
                    ->where('category_checked_ip', 0)
                    ->withCount('music')
                    ->having('music_count', '>', 0)
                    ->orderBy('updated_at','desc')
                    ->get();
            }
        }

        dd($data);
        return CategoriesResource::collection($data);
    }

    public function getMusics($id)
    {
        $domain = $_SERVER['SERVER_NAME'];
        $site = Sites::where('site_web',$domain)->first();


        $load_view_by_category = $site->load_view_by_category;
        $page_limit = 12;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $limit= ($page-1) * $page_limit ;

        try{
            if($load_view_by_category==0){
                $data = Categories::findOrFail($id)
                    ->music()
                    ->with('tags')
                    ->distinct()
                    ->inRandomOrder()
                    ->skip($limit)
                    ->take($page_limit)
                    ->get();
            }
            elseif($load_view_by_category==1){
                $data = Categories::findOrFail($id)
                    ->music()
                    ->with('tags')
                    ->distinct()
                    ->orderBy('music_like_count','desc')
                    ->skip($limit)
                    ->take($page_limit)
                    ->get();
            }
            elseif($load_view_by_category==2){
                $data = Categories::findOrFail($id)
                    ->music()
                    ->with('tags')
                    ->distinct()
                    ->orderBy('music_view_count','desc')
                    ->skip($limit)
                    ->take($page_limit)
                    ->get();
            }
            elseif($load_view_by_category==3){
                $data = Categories::findOrFail($id)
                    ->music()
                    ->with('tags')
                    ->distinct()
                    ->orderBy('created_at','desc')
                    ->skip($limit)
                    ->take($page_limit)
                    ->get();
            }
            return MusicsResource::collection($data);
        }catch (\Exception $e){
            return response()->json(['warning' => ['This Category is not exist']], 200);
        }

    }

    public function getPopulared()
    {
        $domain=$_SERVER['SERVER_NAME'];
        $site = Sites::where('site_web',$domain)->first();
        if (checkBlockIp()){
            $data = $site
                ->categories()
                ->where('category_checked_ip', 1)
                ->withCount('music')
                ->having('music_count', '>', 0)
                ->orderBy('category_view_count','desc')
                ->get();
        }else{
            $data = $site
                ->categories()
                ->where('category_checked_ip', 0)
                ->withCount('music')
                ->having('music_count', '>', 0)
                ->orderBy('category_view_count','desc')
                ->get();
        }
        return CategoriesResource::collection($data);
    }
}
