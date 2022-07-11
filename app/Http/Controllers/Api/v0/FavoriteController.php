<?php

namespace App\Http\Controllers\Api\v0;

use App\Http\Controllers\Controller;
use App\Http\Resources\v0\WallpapersResource;
use App\Sites;
use App\VisitorFavorite;
use App\Visitors;
use App\Wallpapers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class FavoriteController extends Controller
{
    public function likeWallpaper(Request $request)
    {
        $domain=$_SERVER['SERVER_NAME'];
        $visitor = Visitors::where('device_id', $request->device_id)->first();
        if (!$visitor) {
            Visitors::create([
                'device_id' => $request->device_id
            ]);
        }
        $visitorFavorite = VisitorFavorite::where([
            'wallpaper_id' => $request->wallpaper_id,
            'visitor_id' => Visitors::where('device_id', $request->device_id)->value('id'),
            'site_id' => Sites::where('site_web', $domain)->value('id'),
            ])->first();
        $response = array();
        if ($visitorFavorite) {
            return response()->json(['warning' => ['This Wallpaper has already in your List']], 200);
        } else {
            $response['save_wallpaper'] = ['success' => 'Save Wallpaper Successfully'];
            VisitorFavorite::create([
                'wallpaper_id' => $request->wallpaper_id,
                'visitor_id' => Visitors::where('device_id', $request->device_id)->value('id'),
                'site_id' => Sites::where('site_web', $domain)->value('id'),
                ])
                ->first();
            $wallpaper = Wallpapers::where('id', $request->wallpaper_id)->first();
            $wallpaper->increment('like_count');
        }
        return response()->json($response, ResponseAlias::HTTP_OK);
    }

    public function disLikeWallpaper(Request $request)
    {
        $domain=$_SERVER['SERVER_NAME'];
        $visitorFavorite = VisitorFavorite::where([
            'wallpaper_id' => $request->wallpaper_id,
            'visitor_id' => Visitors::where('device_id', $request->device_id)->value('id'),
            'site_id' => Sites::where('site_web', $domain)->value('id')
            ])
            ->first();
        $response = array();
        if ($visitorFavorite) {
            VisitorFavorite::where([
                'wallpaper_id' => $request->wallpaper_id,
                'visitor_id' => Visitors::where('device_id', $request->device_id)->value('id'),
                'site_id' => Sites::where('site_web', $domain)->value('id')
                ])
                ->delete();
            $wallpaper = Wallpapers::where('id', $request->wallpaper_id)->first();
            $wallpaper->decrement('like_count');
            return response()->json(['success' => ['Completely Delete this Wallpaper out of your List']], 200);
        } else {
            $response['warning'] = ['success' => 'This Wallpaper is not in your list'];
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
                ->with('wallpaper')
                ->skip($limit)
                ->take($page_limit)
                ->get();

            $wallpaper = [];
            foreach ($data as $item){
                $wallpaper[] = $item->wallpaper;
            }
            $getResource = WallpapersResource::collection($wallpaper);
            if ($data->isEmpty()) {
                return response()->json([], 200);
            }
            return $getResource;
        } catch (\Exception $e) {
            return response()->json([], 200);
        }
    }
}
