<?php

namespace App\Http\Controllers\Api\v7;

use App\Http\Controllers\Controller;
use App\ListIP;
use App\Sites;
use App\Wallpapers;
use Carbon\Carbon;
use http\Url;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function getJson()
    {
        $domain = $_SERVER['SERVER_NAME'];

        $site =  Sites::where('site_web', $domain)->first();


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
                    "img_lista" => url('/api/wallpaperThumb') . '/[ID]',
                    "img_duze" => url('/api/wallpaper') . '/[ID]',
                    "img_share" => url('/api/wallpaper') . '/[ID]',
                    "img_pobierz" => url('/api/wallpaper') . '/[ID]',
                    "img_ustaw_na_ekranie" => url('/api/wallpaper') . '/[ID]',
                    "img_info" => $domain,
                    "pring<" => 9,
                    "ping+" => 0
                ],
            ],
            "Lista_new" => $this->getWallpaper($site->id, 'id', 'id'),
            "Lista_like" => $this->getWallpaper($site->id, 'wallpaper_like_count', 'id'),
            "Lista_download" => $this->getWallpaper($site->id, 'wallpaper_download_count', 'id'),
            "kadr_new" => $this->getWallpaper($site->id, 'id', 'wallpaper_view_count'),
            "kadr_like" => $this->getWallpaper($site->id, 'wallpaper_like_count', 'wallpaper_view_count'),
            "kadr_download" => $this->getWallpaper($site->id, 'wallpaper_download_count', 'wallpaper_view_count'),
        ];
        return $data;
    }

    public function getJsonV8()
    {
        $domain = $_SERVER['SERVER_NAME'];

        $site = Sites::where('site_web', $domain)->first();


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

        $data = [
            'data_gen' =>(string) (time()),
            "disable_reports"=>false,
            "guzik_pobierz"=>false,
            "pokaz_wyjscie"=>false,
            "pokaz_wyjscie_glosowanie"=>false,
            "reklama_full_opcja_przerwa_sekund"=>0,
            "reklama_full_opcja_pokaz"=>"111111",
            "reklama_full_loading_ms"=>300,
            "reklama_full_ilosc"=>35,
            "reklama_full_set_glowny"=>$site->ad_switch == 1 ?  "przed" :  false,
            "reklama_full_ustaw"=>  $site->ad_switch == 1 ? "przed" :  false,
            "reklama_full_pobierz"=>$site->ad_switch == 1 ?  "przed" :  false,
            "reklama_full_share"=> $site->ad_switch == 1 ? "przed" :  false,
            "reklama_full_wiecej"=> $site->ad_switch == 1 ?  "przed" :  false,
            "reklama_dol"=> $site->ad_switch == 1 ?  "przed" :  false,
            "reklama_nad_guziki"=> $site->ad_switch == 1 ?  "przed" :  false,
            "blokuj_i_przekieruj"=>"",

            'default_server' => [
                'adres' => $domain,
                'images_big' => url('/api/wallpaperThumb') . '/[ID]',
                'images_set_wallpapers' => url('/api/wallpaper') . '/[ID]',
                "images_pobierz" => url('/api/wallpaper') . '/[ID]',
                'img_share' => url('/api/wallpaperThumb') . '/[ID]',
                "if_less_than" =>0,
                "ping_add" =>0,

            ],
            'serwery' => [
                [
                    "adres" => $domain,
                    "server_status" => route('v8.status'),
                    'images_big' => url('/api/wallpaperThumb') . '/[ID]',
                    'images_set_wallpapers' => url('/api/wallpaper') . '/[ID]',
                    "images_pobierz" => url('/api/wallpaper') . '/[ID]',
                    'img_share' => url('/api/wallpaperThumb') . '/[ID]',
                    'if_less_than' => 90,
                    'ping_add' => 0,

                ],
            ],
            "new" => $this->getWallpaper($site->id, 'id', 'id'),
            "top" => $this->getWallpaper($site->id, 'wallpaper_like_count', 'id'),
        ];
        return $data;
    }

    public function status(){
        return 'ok';
    }


    public function action(Request $request)
    {
        $wallpaper = Wallpapers::find($request->id);
        if (isset($request->lubi)) {
            $wallpaper->wallpaper_like_count = $wallpaper->wallpaper_like_count + 1;
        }
        if (isset($request->pobierz)) {
            $wallpaper->wallpaper_download_count = $wallpaper->wallpaper_download_count + 1;
        }
        $wallpaper->save();
        return response()->json(['mgs' => 'success']);

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
            $wallpaper->wallpaper_view_count = $wallpaper->wallpaper_view_count + 1;
            $wallpaper->save();
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