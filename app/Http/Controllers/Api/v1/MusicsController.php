<?php

namespace App\Http\Controllers\Api\v1;

use App\Categories;
use App\Http\Controllers\Controller;
use App\Http\Resources\v1\CategoriesResource;
use App\Http\Resources\v1\FeatureMusicsResource;
use App\Http\Resources\v1\MusicsResource;
use App\ListIP;
use App\Musics;
use App\Sites;
use App\VisitorFavorite;
use App\Visitors;
use Carbon\Carbon;
use Illuminate\Http\Request;


class MusicsController extends Controller
{

    public function show($id,$device_id)
    {
        $music = Musics::with('tags')->findOrFail($id);
        $music->increment('music_view_count');
        $visitorFavorite = VisitorFavorite::where([
            'music_id' => $id,
            'visitor_id' => Visitors::where('device_id', $device_id)->value('id')])->first();

        foreach ($music->tags as $tag){
            $tags[] = $tag->tag_name;
        }

        if($visitorFavorite){
            return response()->json([
                'liked' => 1,
                'id' => $music->id,
                'name' => $music->music_name,
                'image' => asset('storage/musics/images/'.$music->music_image),
                'file' => asset('storage/musics/files/'.$music->music_file),
                'tags' => implode(",", $tags),
                'download_count' => $music->music_download_count,
                'like_count' => $music->music_like_count,
                'view_count' => $music->music_view_count,
                'feature' => $music->music_feature,
                'created_at' => $music->created_at->format('d/m/Y'),
            ]);
        }else{
            return response()->json([
                'liked' => 0,
                'id' => $music->id,
                'name' => $music->music_name,
                'image' => asset('storage/musics/images/'.$music->music_image),
                'file' => asset('storage/musics/files/'.$music->music_file),
                'tags' => implode(",", $tags),
                'download_count' => $music->music_download_count,
                'like_count' => $music->music_like_count,
                'view_count' => $music->music_view_count,
                'feature' => $music->music_feature,
                'created_at' => $music->created_at->format('d/m/Y'),
            ]);
        }
    }

//    public function getFeatured()
//    {
//        $domain=$_SERVER['SERVER_NAME'];
//        if (isset($_SERVER['HTTP_CLIENT_IP']))
//            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
//        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
//            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
//        else if(isset($_SERVER['HTTP_X_FORWARDED']))
//            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
//        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
//            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
//        else if(isset($_SERVER['HTTP_FORWARDED']))
//            $ipaddress = $_SERVER['HTTP_FORWARDED'];
//        else if(isset($_SERVER['REMOTE_ADDR']))
//            $ipaddress = $_SERVER['REMOTE_ADDR'];
//        else if (isset($_SERVER["HTTP_CF_CONNECTING_IP"]))
//            $ipaddress= $_SERVER["HTTP_CF_CONNECTING_IP"];
//        else
//            $ipaddress = 'UNKNOWN';
//
//
//        $site = Sites::where('site_web',$domain)->first();
//        $listIp = ListIP::where('ip_address',$ipaddress)->where('id_site',$site->id)->whereDate('created_at', Carbon::today())->first();
//        if(!$listIp){
//            ListIP::create([
//                'ip_address'=>$ipaddress,
//                'id_site' => $site->id,
//                'count' => 1
//            ]);
//        }else{
//            $listIp->update(['count' => $listIp->count +1]);
//        }
//        $load_feature=$site->load_view_by;
//
//        if (checkBlockIp()) {
//            if($load_feature ==0){
//                $data = Sites::where('site_web',$domain)->first()
//                    ->categories()
//                    ->where('category_checked_ip',1)
//                    ->withCount('music')
//                    ->having('music_count', '>', 0)
//                    ->inRandomOrder()
//                    ->get();
//                $getResource= FeatureMusicsResource::collection($data);
//            }elseif($load_feature ==1){
//                $data = Sites::where('site_web',$domain)->first()
//                    ->categories()
//                    ->where('category_checked_ip',1)
//                    ->withCount('music')
//                    ->having('music_count', '>', 0)
//                    ->orderBy('category_order', 'desc')
//                    ->get();
//                $getResource= FeatureMusicsResource::collection($data);
//
//            }elseif($load_feature ==2){
//                $data = Sites::where('site_web',$domain)->first()
//                    ->categories()
//                    ->where('category_checked_ip',1)
//                    ->withCount('music')
//                    ->having('music_count', '>', 0)
//                    ->orderBy('category_view_count', 'desc')
//                    ->get();
//                $getResource= FeatureMusicsResource::collection($data);
//            }elseif($load_feature ==3){
//                $data = Musics::with('tags')
//                    ->whereHas('categories', function ($query) use ($site) {
//                        $query->where('category_checked_ip', 1)
//                            ->where('site_id',$site->id);
//                    })
//                    ->inRandomOrder()
//                    ->take(12)
//                    ->get();
//                $getResource= MusicsResource::collection($data);
//            }
//        } else {
//            if($load_feature ==0){
//                $data = Sites::where('site_web',$domain)->first()
//                    ->categories()
//                    ->where('category_checked_ip',0)
//                    ->withCount('music')
//                    ->having('music_count', '>', 0)
//                    ->inRandomOrder()
//                    ->get();
//                $getResource= FeatureMusicsResource::collection($data);
//            }elseif($load_feature ==1){
//
//                $data = Sites::where('site_web',$domain)->first()
//                    ->categories()
//                    ->where('category_checked_ip',0)
//                    ->withCount('music')
//                    ->having('music_count', '>', 0)
//                    ->orderBy('category_order', 'desc')
//                    ->get();
//                $getResource= FeatureMusicsResource::collection($data);
//
//
//            }elseif($load_feature ==2){
//                $data = Sites::where('site_web',$domain)->first()
//                    ->categories()
//                    ->where('category_checked_ip',0)
//                    ->withCount('music')
//                    ->having('music_count', '>', 0)
//                    ->orderBy('category_view_count', 'desc')
//                    ->get();
//                $getResource= FeatureMusicsResource::collection($data);
//            }elseif($load_feature ==3){
//                $data = Musics::with('tags')
//                    ->whereHas('categories', function ($query) use ($site) {
//                        $query->where('category_checked_ip', 0)
//                            ->where('site_id',$site->id);
//                    })
//                    ->inRandomOrder()
//                    ->take(12)
//                    ->get();
//                $getResource= MusicsResource::collection($data);
//            }
//        }
//
//        return response()->json([
//            'message'=>'save ip successs',
//            'ip_address'=>$ipaddress,
//            'id_site' => $site->id,
//            'ad_switch'=>$site->ad_switch,
//            'data'=>$getResource,
//        ]);
//
//    }

    public function getPopulared($deviceId)
    {
        $domain=$_SERVER['SERVER_NAME'];
        $site = Sites::where('site_web',$domain)->first();
        $isFake = checkBlockIp()?1:0;



        $data = Musics::with('tags')
            ->whereHas('categories', function ($query) use ($isFake, $site) {
                $query->where('category_checked_ip', $isFake)
                    ->where('site_id',$site->id);
            })
            ->orderBy('music_like_count','desc')
            ->paginate(70);

        $musics = $this->checkLikedToMusics($deviceId, $data);
        $getResource=MusicsResource::collection($musics);
        return $getResource;
    }
    public function getNewest($deviceId)
    {
        $domain=$_SERVER['SERVER_NAME'];
        $site = Sites::where('site_web',$domain)->first();
        $isFake = checkBlockIp()?1:0;


        $data = Musics::with('tags')
            ->whereHas('categories', function ($query) use ($isFake, $site) {
                $query->where('category_checked_ip', $isFake)
                    ->where('site_id',$site->id);
            })
            ->orderBy('created_at','desc')
            ->paginate(70);

        $ringtones = $this->checkLikedToMusics($deviceId, $data);
        $getResource=MusicsResource::collection($ringtones);
        return $getResource;
    }
    public function getMostDownload($deviceId){
        $domain=$_SERVER['SERVER_NAME'];
        $site = Sites::where('site_web',$domain)->first();
        $isFake = checkBlockIp()?1:0;

        $data = Musics::with('tags')
            ->whereHas('categories', function ($query) use ($isFake, $site) {
                $query->where('category_checked_ip', $isFake)
                    ->where('site_id',$site->id);
            })
            ->orderBy('music_download_count','desc')
            ->paginate(70);

        $ringtones = $this->checkLikedToMusics($deviceId, $data);
        return MusicsResource::collection($ringtones);
    }
    public function getMusicsByCate($id, $deviceId)
    {
        try{
            $domain=$_SERVER['SERVER_NAME'];
            $site = Sites::where('site_web',$domain)->first();
            $load_by_category = $site->load_view_by_category;

            switch ($load_by_category){
                case 0:
                    $data = Categories::findOrFail($id)
                        ->music()
                        ->with('tags')
                        ->distinct()
                        ->inRandomOrder()
                        ->paginate(10);
                    break;
                case 1:
                    $data = Categories::findOrFail($id)
                        ->music()
                        ->with('tags')
                        ->distinct()
                        ->orderBy('music_like_count','desc')
                        ->paginate(70);
                    break;
                case 2:
                    $data = Categories::findOrFail($id)
                        ->music()
                        ->with('tags')
                        ->distinct()
                        ->orderBy('music_view_count','desc')
                        ->paginate(10);
                    break;
                case 3:
                    $data = Categories::findOrFail($id)
                        ->music()
                        ->with('tags')
                        ->distinct()
                        ->orderBy('music_feature','desc')
                        ->paginate(10);
                    break;
                case 4:
                    $data = Categories::findOrFail($id)
                        ->music()
                        ->with('tags')
                        ->distinct()
                        ->orderBy('music_name','desc')
                        ->paginate(10);
                    break;
                default :
                    $data = Categories::findOrFail($id)
                        ->music()
                        ->with('tags')
                        ->distinct()
                        ->orderBy('music_download_count','desc')
                        ->paginate(10);
            }

            $music = $this->checkLikedToMusics($deviceId, $data);
            $getResource = MusicsResource::collection($music);
            return $getResource;
        }catch (\Exception $e){
            return response()->json(['warning' => ['This Category is not exist']], 200);
        }

    }
    private function checkLikedToMusics($deviceId, $data){

        $visitorId = Visitors::where('device_id','=',$deviceId)->first('id');
        $visitorFavorites = array();
        if($visitorId){
            $visitorFavorites = VisitorFavorite::where('visitor_id', $visitorId->id)->get('music_id');
        }

        foreach ($data as $i){
            $i->liked = 0;
            foreach ($visitorFavorites as $favorite){
                if($favorite->music_id==$i->id){
                    $i->liked = 1;
                }
            }
        }
        return $data;
    }

    public function search(Request $request){
        $domain=$_SERVER['SERVER_NAME'];
        $site = Sites::where('site_web',$domain)->first();
        $query = $request->input('query');
        $isFake = checkBlockIp() ?1:0;
        $data = Musics::with('tags')
            ->where('music_name', 'like', '%' . $query . '%')
            ->whereHas('categories', function ($query) use ($isFake, $site) {
                $query->where('category_checked_ip', $isFake)
                    ->where('site_id',$site->id);
            })
            ->orderBy('music_download_count','desc')
            ->paginate(70);
        if($data->isEmpty()){
            $data = Musics::with('tags')

                ->whereHas('categories', function ($query) use ($isFake, $site) {
                    $query->where('category_checked_ip', $isFake)
                        ->where('site_id',$site->id);
                })
                ->take(20)
                ->get();
        }
        return [
            'ringtones_result' => MusicsResource::collection($data)
        ];
    }

}
