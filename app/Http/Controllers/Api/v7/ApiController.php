<?php

namespace App\Http\Controllers\Api\v7;

use App\Http\Controllers\Controller;
use App\Sites;
use App\Wallpapers;
use http\Url;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function getJson()
    {
        $domain = $_SERVER['SERVER_NAME'];

        $site =  Sites::where('site_web', $domain)->first();

        $data = [
            'connect' => "OK",
            'date' => time(),
            'przewijanie_prawo_lewo' => true,
            'przekieruj' => '',
            'adres_wyjdz' => $domain,
            'reklama_full_ilosc' => 30,

            'reklama_full_pobierz' => $site->ad_switch == 1 ?  true : false ,
            'reklama_full_ustaw' => $site->ad_switch == 1 ?  true : false ,
            'reklama_full_ustaw_po' => $site->ad_switch == 1 ?  true : false ,
            'reklama_full_share' => $site->ad_switch == 1 ?  true : false ,
            'reklama_full_po_ptaszek' => $site->ad_switch == 1 ?  true : false ,
            'reklama_lista' => $site->ad_switch == 1 ?  true : false ,
            'reklama_podglad' => $site->ad_switch == 1 ?  true : false ,


            'domyslny_server' => [
                'adres' => $domain,
                'search_tips' => $domain,
                'img_lista' => url('/api/wallpaper').'/[ID]',
                'img_duze' => url('/api/wallpaperThumb').'/[ID]',
                "img_share" => url('/api/wallpaper').'/[ID]',
                "img_pobierz" => url('/api/wallpaper').'/[ID]',
                "img_ustaw_na_ekranie" =>url('/api/wallpaper').'/[ID]',
                "img_info" => $domain
            ],
            'servers' => [
                [
                    "adres" => $domain,
                    "search_tips" => $domain,
                    "img_lista" => url('/api/wallpaperThumb').'/[ID]',
                    "img_duze" => url('/api/wallpaper').'/[ID]',
                    "img_share" => url('/api/wallpaper').'/[ID]',
                    "img_pobierz" => url('/api/wallpaper').'/[ID]',
                    "img_ustaw_na_ekranie" => url('/api/wallpaper').'/[ID]',
                    "img_info" => $domain,
                    "pring<" => 9,
                    "ping+" => 0
                ],
            ],

//            "Lista_new" => "dd",
            "Lista_new" => $this->getWallpaper($site->id,'id','id'),
            "Lista_like" => $this->getWallpaper($site->id,'wallpaper_like_count','id'),
            "Lista_download" => $this->getWallpaper($site->id,'wallpaper_download_count','id'),
            "kadr_new" => $this->getWallpaper($site->id,'id','wallpaper_view_count'),
            "kadr_like" => $this->getWallpaper($site->id,'wallpaper_like_count','wallpaper_view_count'),
            "kadr_download" => $this->getWallpaper($site->id,'wallpaper_download_count','wallpaper_view_count'),
//            "kadr_like" => "43,49,51,57,49,50,60,68,75,41,50,72,51,56,44,53,41,28,56,51,37,46,56,50,64,51,50,37,54,50,44,32,59,50,34,54,51,48,48,49,34,51,47,51,42,51,45,47,47,51,48,45,46,54,57,32,56,51,68,31,48,39,51,48,52,51,48,51,53,34,45,47,49,57,48,50,56,50,56,45,45,57,49,57,36,49,25,46,48,48,32,49,59,53,56,53,59,51,52,65,53,49,28,50,56,54,47,50,35,54,70,27,76,56,51,35,45,48,34,67,46,56,51,49,30,51,49,57,62,50,28,43,43,50,56,49,49,52,52,49,40,57,53,39",
//            "kadr_download" => "43,49,51,57,49,50,60,68,75,41,50,72,51,56,44,53,41,28,56,51,37,46,56,50,64,51,50,37,54,50,44,32,59,50,34,54,51,48,48,49,34,51,47,51,42,51,45,47,47,51,48,45,46,54,57,32,56,51,68,31,48,39,51,48,52,51,48,51,53,34,45,47,49,57,48,50,56,50,56,45,45,57,49,57,36,49,25,46,48,48,32,49,59,53,56,53,59,51,52,65,53,49,28,50,56,54,47,50,35,54,70,27,76,56,51,35,45,48,34,67,46,56,51,49,30,51,49,57,62,50,28,43,43,50,56,49,49,52,52,49,40,57,53,39"
        ];

        return $data;

    }


    private  function getWallpaper($siteID, $order, $pluck){

        if (checkBlockIp()) {
            $data = Wallpapers::with(['tags'])
                ->whereHas('categories', function ($query) use ($siteID) {
                    $query->where('category_checked_ip',1)
                        ->where('site_id',$siteID);
                })
                ->orderBy($order, 'desc')
                ->get()->pluck('id')->toArray();
        }else{
            $data = Wallpapers::with(['tags'])
                ->whereHas('categories', function ($query) use ($siteID) {
                    $query->where('category_checked_ip',0)
                        ->where('site_id',$siteID);
                })
                ->orderBy($order, 'desc')
                ->get()->pluck($pluck)->toArray();
        }


        $dataResult = implode(',',$data);
        return $dataResult;
    }
    public function showWallpaper($id){
        $wallpaper = Wallpapers::find($id);
        if (isset($wallpaper)){
                return response()->file(public_path('/storage/wallpapers/').$wallpaper->wallpaper_image);
        }else{
            return response()->json(['mgs'=>'error']);
        }
    }
    public function showWallpaperThumb($id){
        $wallpaper = Wallpapers::find($id);
        if (isset($wallpaper)){
                return response()->file(public_path('/storage/wallpapers/thumbnails/').$wallpaper->wallpaper_image);
        }else{
            return response()->json(['mgs'=>'error']);
        }
    }
}
