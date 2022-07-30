<?php

namespace App\Http\Controllers\Api\v6;

use App\Http\Controllers\Controller;
use App\Http\Resources\v6\CategoriesResource;
use App\Sites;
use Illuminate\Http\Request;

class ApiController extends Controller
{


    public function login(){

        $result = [
            "message" => "success",
            'data' => [
                'exp' => time(),
                'access_token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIxIiwiaWF0IjoxNjU5MTY0NzYxLCJqdGkiOiJmNDY5YzI2Yi1kMmVkLTQyMWQtYWUyNC0zMjI0ZTkwNzI3ZTkiLCJleHAiOjE2NTkyNTExNjF9.hyQQZKQi2_5ULlGCbThKKOcUiU7-fBWYQbTGC7F3uD0'
            ]
        ];
        return response()->json($result);

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
                    ->withCount('wallpaper')
                    ->inRandomOrder()
                    ->paginate(10);
            }
            elseif($load_categories == 1 ){
                $data = $site
                    ->categories()
                    ->where('category_checked_ip', 1)
                    ->withCount('wallpaper')
                    ->orderBy('category_view_count','desc')
                    ->paginate(10);
            }
            elseif($load_categories == 2 ){
                $data = $site
                    ->categories()
                    ->where('category_checked_ip', 1)
                    ->withCount('wallpaper')
                    ->orderBy('updated_at','desc')
                    ->paginate(10);
            }

        } else {

            if($load_categories == 0 ){
                $data = $site
                    ->categories()
                    ->where('category_checked_ip', 0)
                    ->withCount('wallpaper')
                    ->inRandomOrder()
                    ->paginate(10);
            }
            elseif($load_categories == 1 ){
                $data = $site
                    ->categories()
                    ->where('category_checked_ip',0)
                    ->withCount('wallpaper')
                    ->orderBy('category_view_count','desc')
                    ->paginate(10);
            }
            elseif($load_categories == 2 ){
                $data = $site
                    ->categories()
                    ->where('category_checked_ip', 0)
                    ->withCount('wallpaper')
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
}
