<?php

use App\BlockIPs;
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

