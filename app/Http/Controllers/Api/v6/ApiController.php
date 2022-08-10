<?php

namespace App\Http\Controllers\Api\v6;

use App\Categories;
use App\Http\Controllers\Controller;
use App\Http\Resources\v6\WallpaperResource;
use App\Http\Resources\v6\CategoriesResource;
use App\ListIP;
use App\Sites;
use App\Wallpapers;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ApiController extends Controller
{


    public function login(){

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

        $listIp = ListIP::where('ip_address',$ipaddress)->where('id_site',$site->id)->whereDate('created_at', Carbon::today())->first();
        if(!$listIp){
            ListIP::create([
                'ip_address'=>$ipaddress,
                'id_site' => $site->id,
                'count' => 1
            ]);
        }else{
            $listIp->update(['count' => $listIp->count +1]);
        }

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


        $result['page'] = $data->currentPage();
        $result['last_page'] = $data->lastPage();
        $result['total'] = $data->total();
        $result['data'] = CategoriesResource::collection($data);
        return $result;
    }

    public function newest(){
        $domain = $_SERVER['SERVER_NAME'];
        $site =  Sites::where('site_web', $domain)->first();
        $limit = isset($_GET['per_page']) ? $_GET['per_page'] : 10;

        if (checkBlockIp()) {
            $data = $this->getWallpaper('id',$site->id,1,$limit);
        } else {
            $data = $this->getWallpaper('id',$site->id,0,$limit);
        }
        $dataResult['page'] = $data->currentPage();
        $dataResult['per_page'] = $limit;
        $dataResult['last_page'] = $data->lastPage();
        $dataResult['total'] = $data->total();
        $dataResult['data'] = $data;
        return $dataResult;
    }


    public function trending(){
        $domain = $_SERVER['SERVER_NAME'];
        $site =  Sites::where('site_web', $domain)->first();
        $limit = isset($_GET['per_page']) ? $_GET['per_page'] : 10;

        if (checkBlockIp()) {
            $data = $this->getWallpaper('wallpaper_like_count',$site->id,1,$limit);
        } else {
            $data = $this->getWallpaper('wallpaper_like_count',$site->id,0,$limit);
        }
        $dataResult['page'] = $data->currentPage();
        $dataResult['per_page'] = $limit;
        $dataResult['last_page'] = $data->lastPage();
        $dataResult['total'] = $data->total();
        $dataResult['data'] = $data;
        return $dataResult;
    }

    public function random(){
        $domain = $_SERVER['SERVER_NAME'];
        $site =  Sites::where('site_web', $domain)->first();
        $limit = isset($_GET['per_page']) ? $_GET['per_page'] : 10;

        $category = isset($_GET['category_id']) ? $_GET['category_id'] : null;

        if (checkBlockIp()) {
            $data = $this->getWallpaper('wallpaper_like_count',$site->id,1,$limit,$category);
        } else {
            $data = $this->getWallpaper('wallpaper_like_count',$site->id,0,$limit,$category);
        }
        $dataResult['page'] = $data->currentPage();
        $dataResult['per_page'] = $limit;
        $dataResult['last_page'] = $data->lastPage();
        $dataResult['total'] = $data->total();
        $dataResult['data'] = $data;
        return $dataResult;
    }

    public function download(Request $request){
        $wallpaper = Wallpapers::find($request->id);
        if ($wallpaper){
            $wallpaper->wallpaper_download_count = $wallpaper->wallpaper_download_count+1;
            $wallpaper->save();

            $dataResult['success'] = 'true';
            $dataResult['download'] = $wallpaper->wallpaper_download_count;
        }else{
            $dataResult['success'] = 'false';
            $dataResult['message'] = '"Not found';
        }

        return $dataResult;
    }

    public function use(Request $request){
        $wallpaper = Wallpapers::find($request->id);
        if ($wallpaper){
            $wallpaper->wallpaper_like_count = $wallpaper->wallpaper_like_count+1;
            $wallpaper->save();

            $dataResult['success'] = 'true';
            $dataResult['download'] = $wallpaper->wallpaper_like_count;
        }else{
            $dataResult['success'] = 'false';
            $dataResult['message'] = '"Not found';
        }

        return $dataResult;
    }

    private  function getWallpaper($order, $siteID, $checkBlock, $limit,$category=null){
        if(isset($order)){
            $data = Wallpapers::with(['tags',
                'categories'=> function ($query) use ($siteID, $checkBlock) {
                    $query->where('category_checked_ip', $checkBlock)
                        ->where('site_id',$siteID)->select('category_name')
                        ->select('categories.id as category_id', 'category_name')
                    ;
                }
            ])
                ->whereHas('categories', function ($query) use ($siteID, $checkBlock) {
                     $query->where('category_checked_ip', $checkBlock)
                        ->where('site_id',$siteID)->select('category_name');
                })
                ->orderBy($order, 'desc')
                ->paginate($limit);
        }else{
            if ($category){
                $data = Categories::where('id',$category)
                    ->where('site_id',$siteID)
                    ->first()
                    ->wallpaper()
                    ->with(['categories'=> function ($query) use ($category, $siteID, $checkBlock) {
                        $query->where('category_checked_ip', $checkBlock)
                            ->where('site_id',$siteID)->select('category_name')
                            ->select('categories.id as category_id', 'category_name')
                        ;
                    }
                    ])
                    ->inRandomOrder()
                    ->paginate($limit);
            }else{
                $data = Wallpapers::with(['tags',
                    'categories'=> function ($query) use ($category, $siteID, $checkBlock) {
                        $query->where('category_checked_ip', $checkBlock)
                            ->where('site_id',$siteID)->select('category_name')
                            ->select('categories.id as category_id', 'category_name')
                        ;
                    }
                ])
                    ->whereHas('categories', function ($query) use ($category, $siteID, $checkBlock) {
                        $query
                            ->where('category_checked_ip', $checkBlock)
                            ->where('site_id',$siteID);
                    })
                    ->inRandomOrder()
                    ->paginate($limit);
            }

        }
        $dataResult = WallpaperResource::collection($data);
        return $dataResult;
    }

}
