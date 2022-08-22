<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\MusicsResource;
use App\Musics;
use App\Sites;
use App\VisitorFavorite;
use App\Visitors;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class FavoriteController extends Controller
{
    public function like(Request $request)
    {
        $domain=$_SERVER['SERVER_NAME'];
        $visitor = Visitors::where('device_id', $request->device_id)->first();
        if (!$visitor) {
            Visitors::create([
                'device_id' => $request->device_id
            ]);
        }
        $visitorFavorite = VisitorFavorite::where([
            'music_id' => $request->music_id,
            'visitor_id' => Visitors::where('device_id', $request->device_id)->value('id'),
            'site_id' => Sites::where('site_web', $domain)->value('id'),
            ])->first();
        $response = array();
        if ($visitorFavorite) {
            return response()->json(['warning' => ['This Wallpaper has already in your List']], 200);
        } else {
            $response['save_music'] = ['success' => 'Save Successfully'];
            VisitorFavorite::create([
                'music_id' => $request->wallpaper_id,
                'visitor_id' => Visitors::where('device_id', $request->device_id)->value('id'),
                'site_id' => Sites::where('site_web', $domain)->value('id'),
                ])
                ->first();
            $music= Musics::where('id', $request->music_id)->first();
            $music->increment('music_like_count');
        }
        return response()->json($response, ResponseAlias::HTTP_OK);
    }

    public function disLike(Request $request)
    {
        $domain=$_SERVER['SERVER_NAME'];
        $visitorFavorite = VisitorFavorite::where([
            'music_id' => $request->music_id,
            'visitor_id' => Visitors::where('device_id', $request->device_id)->value('id'),
            'site_id' => Sites::where('site_web', $domain)->value('id')
            ])
            ->first();
        $response = array();
        if ($visitorFavorite) {
            VisitorFavorite::where([
                'music_id' => $request->music_id,
                'visitor_id' => Visitors::where('device_id', $request->device_id)->value('id'),
                'site_id' => Sites::where('site_web', $domain)->value('id')
                ])
                ->delete();
            $music = Musics::where('id', $request->music_id)->first();
            $music->decrement('music_like_count');
            return response()->json(['success' => ['Completely Delete this out of your List']], 200);
        } else {
            $response['warning'] = ['success' => 'This is not in your list'];
        }
        return response()->json($response, Response::HTTP_OK);
    }

    public function getSaved($device_id)
    {
        $page_limit = 12;
        $limit=(isset($_GET['page'])? $_GET['page'] -1 : 0) * $page_limit;
        $domain=$_SERVER['SERVER_NAME'];
//        $visitor = Visitors::where('device_id', $device_id)->first();
        try {
            $data =  VisitorFavorite::where([
                'visitor_id' => Visitors::where('device_id', $device_id)->value('id'),
                'site_id' => Sites::where('site_web', $domain)->value('id')
                ])
                ->with('music')
                ->skip($limit)
                ->take($page_limit)
                ->get();

            $music = [];
            foreach ($data as $item){
                $music[] = $item->music;
            }
            $getResource = MusicsResource::collection($music);
            if ($data->isEmpty()) {
                return response()->json([], 200);
            }
            return $getResource;
        } catch (\Exception $e) {
            return response()->json([], 200);
        }
    }
}
