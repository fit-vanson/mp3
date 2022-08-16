<?php

namespace App\Http\Controllers\Api\v0;

use App\Categories;
use App\Http\Controllers\Controller;
use App\Http\Resources\v0\CategoriesResource;
use App\Http\Resources\v0\FeatureRingtoneResource;
use App\Http\Resources\v0\FeatureWallpaperResource;
use App\Http\Resources\v0\RingtonesResource;
use App\Http\Resources\v0\WallpapersResource;
use App\ListIP;
use App\Ringtones;
use App\Sites;
use App\VisitorFavorite;
use App\Visitors;
use App\Wallpapers;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RingtonesController extends Controller
{

    public function show($id,$device_id)
    {
        $ringtone = Ringtones::findOrFail($id);
        $ringtone->increment('ringtone_view_count');
        $visitorFavorite = VisitorFavorite::where([
            'ringtone_id' => $id,
            'visitor_id' => Visitors::where('device_id', $device_id)->value('id')])->first();
        if($visitorFavorite){
            return response()->json([
                'categories' =>
                    CategoriesResource::collection($ringtone->categories),
                'id' => $ringtone->id,
                'name' => $ringtone->name,
                'thumbnail_image' => asset('storage/ringtones/'.$ringtone->thumbnail_image),
                'ringtone_file'=>asset('storage/ringtones/'.$ringtone->ringtone_file),
                'like_count' => $ringtone->like_count,
                'views' => $ringtone->view_count,
                'feature' => $ringtone->feature,
                'created_at' => $ringtone->created_at->format('d/m/Y'),
            ]);
        }else{
            return response()->json([
                'liked' => 0,
                'categories' =>
                    CategoriesResource::collection($ringtone->categories),
                'id' => $ringtone->id,
                'name' => $ringtone->name,
                'thumbnail_image' => asset('storage/ringtones/'.$ringtone->thumbnail_image),
                'ringtone_file'=>asset('storage/ringtones/'.$ringtone->ringtone_file),
                'like_count' => $ringtone->like_count,
                'views' => $ringtone->view_count,
                'feature' => $ringtone->feature,
                'created_at' => $ringtone->created_at->format('d/m/Y'),
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

        $load_feature=$site->load_view_by;



        if (checkBlockIp()) {
            if($load_feature ==0){
                $data = Sites::where('site_web',$domain)->first()
                    ->categories()
                    ->where('category_checked_ip',1)
                    ->withCount('ringtone')
                    ->having('ringtone_count', '>', 0)
                    ->inRandomOrder()
                    ->get();
                $getResource= FeatureRingtoneResource::collection($data);
            }elseif($load_feature ==1){
                $data = Sites::where('site_web',$domain)->first()
                    ->categories()
                    ->where('category_checked_ip',1)
                    ->withCount('ringtone')
                    ->having('ringtone_count', '>', 0)
                    ->orderBy('category_order', 'desc')
                    ->get();
                $getResource= FeatureRingtoneResource::collection($data);

            }elseif($load_feature ==2){
                $data = Sites::where('site_web',$domain)->first()
                    ->categories()
                    ->where('category_checked_ip',1)
                    ->withCount('ringtone')
                    ->having('ringtone_count', '>', 0)
                    ->orderBy('category_view_count', 'desc')
                    ->get();
                $getResource= FeatureRingtoneResource::collection($data);
            }elseif($load_feature ==3){
                $data = Ringtones::with('tags')
                    ->whereHas('categories', function ($query) use ($site) {
                        $query->where('category_checked_ip', 1)
                            ->where('site_id',$site->id);
                    })
                    ->inRandomOrder()
                    ->take(12)
                    ->get();
                $getResource= RingtonesResource::collection($data);

            }


        } else {
            if($load_feature ==0){
                $data = Sites::where('site_web',$domain)->first()
                    ->categories()
                    ->where('category_checked_ip',0)
                    ->withCount('ringtone')
                    ->having('ringtone_count', '>', 0)
                    ->inRandomOrder()
                    ->get();
                $getResource= FeatureRingtoneResource::collection($data);
            }elseif($load_feature ==1){
                $data = Sites::where('site_web',$domain)->first()
                    ->categories()
                    ->where('category_checked_ip',0)
                    ->withCount('ringtone')
                    ->having('ringtone_count', '>', 0)
                    ->orderBy('category_order', 'desc')
                    ->get();
                $getResource= FeatureRingtoneResource::collection($data);

            }elseif($load_feature ==2){
                $data = Sites::where('site_web',$domain)->first()
                    ->categories()
                    ->where('category_checked_ip',0)
                    ->withCount('ringtone')
                    ->having('ringtone_count', '>', 0)
                    ->orderBy('category_view_count', 'desc')
                    ->get();
                $getResource= FeatureRingtoneResource::collection($data);
            }elseif($load_feature ==3){
                $data = Ringtones::with('tags')
                    ->whereHas('categories', function ($query) use ($site) {
                        $query->where('category_checked_ip', 0)
                            ->where('site_id',$site->id);
                    })
                    ->inRandomOrder()
                    ->take(12)
                    ->get();
                $getResource= RingtonesResource::collection($data);

            }
        }



        return response()->json([
            'message'=>'save ip successs',
            'ip_address'=>$ipaddress,
            'id_site' => $site->id,
            'ad_switch'=>$site->ad_switch,
            'data'=>$getResource,
        ]);

    }



    public function getPopulared($deviceId)
    {
        $domain=$_SERVER['SERVER_NAME'];
        $site = Sites::where('site_web',$domain)->first();
        $isFake = checkBlockIp()?1:0;



        $data = Ringtones::with('tags')
            ->whereHas('categories', function ($query) use ($isFake, $site) {
                $query->where('category_checked_ip', $isFake)
                    ->where('site_id',$site->id);
            })
            ->orderBy('ringtone_like_count','desc')
            ->paginate(70);

        $ringtones = $this->checkLikedToRingtones($deviceId, $data);
        $getResource=RingtonesResource::collection($ringtones);
        return $getResource;
    }


    public function getNewest($deviceId)
    {
        $domain=$_SERVER['SERVER_NAME'];
        $site = Sites::where('site_web',$domain)->first();
        $isFake = checkBlockIp()?1:0;


        $data = Ringtones::with('tags')
            ->whereHas('categories', function ($query) use ($isFake, $site) {
                $query->where('category_checked_ip', $isFake)
                    ->where('site_id',$site->id);
            })
            ->orderBy('created_at','desc')
            ->paginate(70);

//        if (checkBlockIp()){
//            $data = Ringtone::orderBy('created_at','desc')
//                ->whereHas('categories', function ($q) use ($domain) {
//                    $q->leftJoin('categories_has_site', 'categories_has_site.category_id', '=', 'categories.id')
//                        ->leftJoin('sites', 'sites.id', '=', 'categories_has_site.site_id')
//                        ->where('web_site',$domain)
//                        ->where('turn_to_fake_cate','=', 1);
//                })
//                ->paginate(70);
//        }else {
//            $data = Ringtone::orderBy('created_at','desc')
//                ->whereHas('categories', function ($q) use ($domain) {
//                    $q->leftJoin('categories_has_site', 'categories_has_site.category_id', '=', 'categories.id')
//                        ->leftJoin('sites', 'sites.id', '=', 'categories_has_site.site_id')
//                        ->where('web_site',$domain)
//                        ->where('turn_to_fake_cate','=', 0);
//                })
//                ->paginate(70);
//        }
        $ringtones = $this->checkLikedToRingtones($deviceId, $data);
        $getResource=RingtonesResource::collection($ringtones);
        return $getResource;
    }


    public function getMostDownload($deviceId){
        $domain=$_SERVER['SERVER_NAME'];
        $site = Sites::where('site_web',$domain)->first();
        $isFake = checkBlockIp()?1:0;

        $data = Ringtones::with('tags')
            ->whereHas('categories', function ($query) use ($isFake, $site) {
                $query->where('category_checked_ip', $isFake)
                    ->where('site_id',$site->id);
            })
            ->orderBy('ringtone_download_count','desc')
            ->paginate(70);

//        $data = Ringtone::orderBy('downloads','desc')
//            ->whereHas('categories', function ($q) use ($domain, $isFake) {
//                $q->leftJoin('categories_has_site', 'categories_has_site.category_id', '=', 'categories.id')
//                    ->leftJoin('sites', 'sites.id', '=', 'categories_has_site.site_id')
//                    ->where('web_site',$domain)
//                    ->where('turn_to_fake_cate','=', $isFake);
//            })
//            ->paginate(70);
        $ringtones = $this->checkLikedToRingtones($deviceId, $data);
        return RingtonesResource::collection($ringtones);
    }



    public function getRingtonesByCate($id, $deviceId)
    {

        try{
            $domain=$_SERVER['SERVER_NAME'];
            $site = Sites::where('site_web',$domain)->first();
            $load_by_category = $site->load_wallpapers_category;


            switch ($load_by_category){
                case 0:
                    $data = Categories::findOrFail($id)
                        ->ringtone()
//                        ->with('tags')
                        ->distinct()
                        ->inRandomOrder()
                        ->paginate(70);
                    break;
                case 1:
                    $data = Categories::findOrFail($id)
                        ->ringtone()
                        ->with('tags')
                        ->distinct()
                        ->orderBy('ringtone_like_count','desc')
                        ->paginate(70);
                    break;
                case 2:
                    $data = Categories::findOrFail($id)
                        ->ringtone()
                        ->with('tags')
                        ->distinct()
                        ->orderBy('ringtone_view_count','desc')
                        ->paginate(70);
                    break;
                case 3:
                    $data = Categories::findOrFail($id)
                        ->ringtone()
                        ->with('tags')
                        ->distinct()
                        ->orderBy('ringtone_feature','desc')
                        ->paginate(70);
                    break;
                case 4:
                    $data = Categories::findOrFail($id)
                        ->ringtone()
                        ->with('tags')
                        ->distinct()
                        ->orderBy('ringtone_name','desc')
                        ->paginate(70);
                    break;
                default :
                    $data = Categories::findOrFail($id)
                        ->ringtone()
                        ->with('tags')
                        ->distinct()
                        ->orderBy('ringtone_download_count','desc')
                        ->paginate(70);

            }
            $ringtones = $this->checkLikedToRingtones($deviceId, $data);

            $getResource = RingtonesResource::collection($ringtones);
            return $getResource;
        }catch (\Exception $e){
            return response()->json(['warning' => ['This Category is not exist']], 200);
        }

    }

    private function checkLikedToRingtones($deviceId, $data){
        $visitorId = Visitors::where('device_id','=',$deviceId)->first('id');
        $visitorFavorites = array();
        if($visitorId){
            $visitorFavorites = VisitorFavorite::where('visitor_id', $visitorId->id)->get('ringtone_id');
        }

        foreach ($data as $i){
            $i->liked = 0;
            foreach ($visitorFavorites as $favorite){
                if($favorite->ringtone_id==$i->id){
                    $i->liked = 1;
                }
            }
        }
        return $data;
    }

}
