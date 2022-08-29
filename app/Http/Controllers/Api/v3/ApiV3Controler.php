<?php

namespace App\Http\Controllers\Api\v3;

use App\Categories;
use App\Http\Controllers\Controller;
use App\Http\Resources\v3\CategoriesResource;
use App\Http\Resources\v3\MusicsResource;
use App\Musics;
use App\Sites;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiV3Controler extends Controller
{
    public function getAds(){
        $domain = $_SERVER['SERVER_NAME'];
        $site = Sites::where('site_web',$domain)->firstorFail();
        $ads = json_decode($site->site_ads, true);
        $ads_arr = [];
        if($ads){
            $ads_arr = [
                [
                    'position' => $ads['AdMob_Banner_Ad_Unit_ID'] ? 'ADS_BANNER' : null,
                    'code' => $ads['AdMob_Banner_Ad_Unit_ID'] ? $ads['AdMob_Banner_Ad_Unit_ID'] : null
                ],
                [
                    'position' => $ads['AdMob_Interstitial_Ad_Unit_ID'] ? 'ADS_INTER' : null,
                    'code' => $ads['AdMob_Interstitial_Ad_Unit_ID'] ? $ads['AdMob_Interstitial_Ad_Unit_ID'] : null
                ]
            ];
        }
        return response()->json($ads_arr);
    }

    public function getHome(){
        $domain=$_SERVER['SERVER_NAME'];
        $site = Sites::where('site_web',$domain)->first();
        $isFake = checkBlockIp()?1:0;

        $data = Musics::orderBy('music_like_count','desc')
            ->whereHas('categories', function ($query) use ($isFake, $site) {
                $query->where('category_checked_ip', $isFake)
                    ->where('site_id',$site->id);
            })
            ->paginate(15);

        $result = [
            'videos' => MusicsResource::collection($data),
            'current_page' => $data->currentPage(),
            'total_items' => $data->total(),
            'total_pages' => $data->lastPage(),
        ];

        return response()->json($result);
    }

    public function getCategory(){
        $domain=$_SERVER['SERVER_NAME'];
        $site = Sites::where('site_web',$domain)->first();
        $load_categories = $site->load_categories;
        $isFake = checkBlockIp()?1:0;


        if($load_categories == 0 ){
            $data = $site
                ->categories()
                ->where('category_checked_ip', $isFake)
                ->withCount('music')
                ->having('music_count', '>', 0)
                ->inRandomOrder()
                ->get();
        }
        elseif($load_categories == 1 ){
            $data = $site
                ->categories()
                ->where('category_checked_ip', $isFake)
                ->withCount('music')
                ->having('music_count', '>', 0)
                ->orderBy('category_view_count','desc')
                ->get();
        }
        elseif($load_categories == 2 ){
            $data = $site
                ->categories()
                ->where('category_checked_ip', $isFake)
                ->withCount('music')
                ->having('music_count', '>', 0)
                ->orderBy('updated_at','desc')
                ->get();
        }
        return response()->json(CategoriesResource::collection($data));
    }

    public function getCategoryDetail(){

        $cat = $_GET['cat'];

        $domain=$_SERVER['SERVER_NAME'];
        $site = Sites::where('site_web',$domain)->first();
        $category = Categories::where('site_id',$site->id)
            ->where('category_name',$cat)->firstOrFail();

        $load_by_categories = $site->load_view_by_category;

        if($load_by_categories == 0 ){
            $data = $category
                ->music()
                ->inRandomOrder()
                ->paginate(15);
        }
        elseif($load_by_categories == 1 ){
            $data = $category
                ->music()
                ->orderByDesc('music_like_count')
                ->paginate(15);
        }
        elseif($load_by_categories == 2 ){
            $data = $category
                ->music()
                ->orderByDesc('music_view_count')
                ->paginate(15);
        }
        elseif($load_by_categories == 3 ){
            $data = $category
                ->music()
                ->orderByDesc('updated_at')
                ->paginate(15);
        }
        $result = [
            'videos' => MusicsResource::collection($data),
            'current_page' => $data->currentPage(),
            'total_items' => $data->total(),
            'total_pages' => $data->lastPage(),
        ];

        return response()->json($result);
    }



    public function CURL($url){
        $dataArr = [
            'Content-Type'=>'application/json',
        ];
        $response = Http::withOptions(['verify' => false])->withHeaders($dataArr)->get($url);
        if ($response->successful()) {
            $data = $response->json();
        }
        return $data;
    }
}
