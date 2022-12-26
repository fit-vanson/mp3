<?php

use App\BlockIPs;
use App\Musics;
use App\Sites;
use Illuminate\Support\Facades\Log;
use YouTube\YouTubeDownloader;

function get_ip(){
    $realIp = request()->ip();
    foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
        if (array_key_exists($key, $_SERVER) === true) {
            foreach (explode(',', $_SERVER[$key]) as $ip) {
                $ip = trim($ip);
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) == false){
                    $realIp = $ip;
                }
            }
        }
    }
    return $realIp;
}
function checkBlockIp(){

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
//    $site = SiteManage::with('blockIps')->where('site_name',$domain)->first();
    $blockIps = BlockIPs::where('status',1)->get();
    $block=false;
    foreach ($blockIps as $blockIp){

        for($k=0;$k<=255;$k++){
            $a=$blockIp->ip_address;
            $b[$k]=str_replace("*", $k,$a);
            if ($ipaddress == $b[$k]){
                $block=true;
            }
        }
    }
    return $block;

}

function checkLink($url){
    if (filter_var($url, FILTER_VALIDATE_URL)) {
        $headers = get_headers($url);
        return stripos($headers[0],"200 OK") ? $url : false;
    } else {
        return false;
    }
}

function getLinkUrl($id_ytb, $option=null)
{

    try {
        $youtube = new YouTubeDownloader();
        $downloadOptions = $youtube->getDownloadLinks("https://www.youtube.com/watch?v=" . $id_ytb);
        if ( $downloadOptions->getAllFormats() && $downloadOptions->getInfo()) {

            switch ($option){
                case 'url':
                    return $downloadOptions->getFirstCombinedFormat()->url;
                    break;
                case 'lengthSeconds':
                    return  $downloadOptions->getInfo()->getLengthSeconds();

                default :
                    $result = [
                        'url' => $downloadOptions->getFirstCombinedFormat()->url,
                        'title' =>  $downloadOptions->getInfo()->getTitle(),
                        'lengthSeconds' =>  $downloadOptions->getInfo()->getLengthSeconds(),
                    ];
                    return response()->json($result);
            }

        } else {
            return  false;
        }

    }catch (\Exception $ex) {
        Log::error('Error: Not link ID YTB: '.$id_ytb);
        return  false;
    }

}


function getDomain(){
    return $_SERVER['SERVER_NAME'];
}

function getSite(){
    return Sites::where('site_web',getDomain())->firstorFail();
}

function load_categories($site){
    return $site->load_categories;
}

function load_wallpapers_category($site){
    return $site->load_wallpapers_category;
}

function getHome($site){
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
    return true;

}


function get_categories($site,$page_limit)
{
    $data = false;
    $isFake = checkBlockIp() ? 1 : 0;


        switch (load_categories($site)){
            case 0:
                $data =
                    $site
                        ->categories()
                        ->where('category_checked_ip', $isFake)
                        ->inRandomOrder()
                        ->withCount('music')
                        ->having('music_count', '>', 0)
                        ->paginate($page_limit);
                break;
            case 1:
                $data =
                    $site
                        ->categories()
                        ->where('category_checked_ip',$isFake)
                        ->orderBy('category_view_count','desc')
                        ->withCount('music')
                        ->having('music_count', '>', 0)
                        ->paginate($page_limit);
                break;
            case 2:
                $data =
                    $site
                        ->categories()
                        ->where('category_checked_ip', $isFake)
                        ->orderBy('updated_at','desc')
                        ->withCount('music')
                        ->having('music_count', '>', 0)
                        ->paginate($page_limit);
                break;
            case 3:
                $data =
                    $site
                        ->categories()
                        ->where('category_checked_ip', $isFake)
                        ->orderBy('category_order','asc')
                        ->withCount('music')
                        ->having('music_count', '>', 0)
                        ->paginate($page_limit);
                break;
            case 4:
                $data =
                    $site
                        ->categories()
                        ->where('category_checked_ip', $isFake)
                        ->orderBy('category_name','asc')
                        ->withCount('music')
                        ->having('music_count', '>', 0)
                        ->paginate($page_limit);
                break;
        }
    return $data;
}

function get_category_details($site,$category,$page_limit){
    $data = false;


    switch (load_wallpapers_category($site)){
        case 0:
            $data = $category
                ->music()
                ->with(['categories' => function($query) {
                    $query->where('site_id', getSite()->id);
                }])
                ->distinct()
                ->inRandomOrder()
                ->paginate($page_limit);
            break;
        case 1:
            $data = $category
                ->music()
                ->with(['categories' => function($query) {
                    $query->where('site_id', getSite()->id);
                }])
                ->distinct()
                ->orderBy('music_like_count','desc')
                ->paginate($page_limit);
            break;
        case 2:
            $data = $category
                ->music()
                ->with(['categories' => function($query) {
                    $query->where('site_id', getSite()->id);
                }])
                ->distinct()
                ->orderBy('music_view_count','desc')
                ->paginate($page_limit);
            break;
        case 3:
            $data = $category
                ->music()
                ->with(['categories' => function($query) {
                    $query->where('site_id', getSite()->id);
                }])
                ->distinct()
                ->orderBy('updated_at','desc')
                ->paginate($page_limit);
            break;
    }
    return $data;
}

function get_songs($site,$page_limit,$order){
    $data = false;
    $isFake = checkBlockIp() ? 1 : 0;
    $data = \App\Musics
        ::with(['categories' => function($query) use ($isFake, $site) {
            $query
                ->where('category_checked_ip', $isFake)
                ->where('site_id', $site->id);
        }])

        ->whereHas('categories', function ($query) use ($isFake, $site) {
            $query
                ->where('category_checked_ip', $isFake)
                ->where('site_id', $site->id);
        })
        ->distinct()
        ->orderByDesc($order)
        ->paginate($page_limit);
    return $data;
}

function update_song_view($id){
    $return = false;
    $music = Musics::findorfail($id);
    if ($music) {
        $music->music_view_count = $music->music_view_count + 1;
        $music->save();
        $return = $music;
    }
    return $return;
}
function update_song_download($id){
    $return = false;
    $music = Musics::findorfail($id);
    if ($music) {
        $music->music_download_count = $music->music_download_count + 1;
        $music->save();
        $return = $music;
    }
    return $return;
}



