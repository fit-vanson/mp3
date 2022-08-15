<?php

namespace App\Http\Controllers\Api\v0;

use App\Categories;
use App\Http\Controllers\Controller;
use App\Sites;
use App\Visitors;
use Illuminate\Http\Request;

class RingtonesController extends Controller
{

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
                        ->with('tags')
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
            $getResource = RingtoneResource::collection($ringtones);
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
