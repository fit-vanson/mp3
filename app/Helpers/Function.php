<?php

use App\BlockIPs;
use App\Http\Resources\v4\MusicFeatureResource;
use App\Http\Resources\v4\MusicForCategoryResource;
use App\ListIP;
use App\Musics;
use App\Sites;
use App\VisitorFavorite;
use App\Visitors;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use YouTube\YouTubeDownloader;

function get_ip()
{
    $realIp = request()->ip();
    foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
        if (array_key_exists($key, $_SERVER) === true) {
            foreach (explode(',', $_SERVER[$key]) as $ip) {
                $ip = trim($ip);
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) == false) {
                    $realIp = $ip;
                }
            }
        }
    }
    return $realIp;
}

function checkBlockIp()
{

    if (isset($_GET['ip'])) {
        $ipaddress = $_GET["ip"];
    } else {

        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if (isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else if (isset($_SERVER["HTTP_CF_CONNECTING_IP"]))
            $ipaddress = $_SERVER["HTTP_CF_CONNECTING_IP"];
        else
            $ipaddress = 'UNKNOWN';
    }

    $ip = '';
    $blockIps = BlockIPs::where('status', 1)->get();
    $block = false;
    foreach ($blockIps as $blockIp) {
        if (strpos($ipaddress, $blockIp->ip_address) !== false) {
            $block = true;
            $ip = $blockIp->ip_address;
        }
    }
    if (isset($_GET['ip'])) {
        echo json_encode(['msg' => $block, 'ip' => $ip]);
    }
    return $block;

}

function checkLink($url)
{
    if (filter_var($url, FILTER_VALIDATE_URL)) {
        $headers = get_headers($url);
        return stripos($headers[0], "200 OK") ? $url : false;
    } else {
        return false;
    }
}

function getLinkUrl($id_ytb, $option = null)
{

    try {
        $youtube = new YouTubeDownloader();
        $downloadOptions = $youtube->getDownloadLinks("https://www.youtube.com/watch?v=" . $id_ytb);
        if ($downloadOptions->getAllFormats() && $downloadOptions->getInfo()) {

            switch ($option) {
                case 'url':
                    return $downloadOptions->getFirstCombinedFormat()->url;
                    break;
                case 'lengthSeconds':
                    return $downloadOptions->getInfo()->getLengthSeconds();

                default :
                    $result = [
                        'url' => $downloadOptions->getFirstCombinedFormat()->url,
                        'title' => $downloadOptions->getInfo()->getTitle(),
                        'lengthSeconds' => $downloadOptions->getInfo()->getLengthSeconds(),
                    ];
                    return response()->json($result);
            }

        } else {
            return false;
        }

    } catch (\Exception $ex) {
        Log::error('Error: Not link ID YTB: ' . $id_ytb);
        return false;
    }

}


function getDomain()
{
    return $_SERVER['SERVER_NAME'];
}

function getSite()
{
    return Sites::where('site_web', getDomain())->firstorFail();
}

function load_categories($site)
{
    return $site->load_categories;
}

function load_view_by_category($site)
{
    return $site->load_view_by_category;
}

function load_feature($site)
{
    $data = false;
    $isFake = checkBlockIp() ? 1 : 0;
    $load_feature = $site->load_view_by;
    switch ($load_feature) {
        case 1:
            $data = get_songs($site, 10, 'music_like_count');
            $getResource = MusicFeatureResource::collection($data);
            break;
        case 2:
            $data = get_songs($site, 10, 'music_view_count');
            $getResource = MusicFeatureResource::collection($data);
            break;
        case 3:
            $data = get_categories($site, 10);
            $getResource = MusicForCategoryResource::collection($data);
            break;
        default:
            $data = get_songs($site, 10);
            $getResource = MusicFeatureResource::collection($data);
            break;
    }
    return $getResource;
}

function load_banner($site)
{
    $data = false;
    $isFake = checkBlockIp() ? 1 : 0;
    $load_feature = $site->load_view_by;
    switch ($load_feature) {
        case 1:
            $data = get_songs($site, 10, 'music_like_count');
            $getResource = MusicFeatureResource::collection($data);
            break;
        case 2:
            $data = get_songs($site, 10, 'music_view_count');
            $getResource = MusicFeatureResource::collection($data);
            break;
        case 3:
            $data = get_categories($site, 10);
            $getResource = MusicForCategoryResource::collection($data);
            break;
        default:
            $data = get_songs($site, 10);
            $getResource = MusicFeatureResource::collection($data);
            break;
    }
    return $getResource;


}

function getHome($site)
{
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if (isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else if (isset($_SERVER["HTTP_CF_CONNECTING_IP"]))
        $ipaddress = $_SERVER["HTTP_CF_CONNECTING_IP"];
    else
        $ipaddress = 'UNKNOWN';

    $listIp = ListIP::where('ip_address', $ipaddress)->where('id_site', $site->id)->whereDate('created_at', Carbon::today())->first();
    if (!$listIp) {
        ListIP::create([
            'ip_address' => $ipaddress,
            'id_site' => $site->id,
            'count' => 1
        ]);
    } else {
        $listIp->update(['count' => $listIp->count + 1]);
    }
    return true;

}

function getVisitors($device_id)
{
    $visitor = Visitors::where('device_id', $device_id)->first();
    if (!$visitor) {
        Visitors::create([
            'device_id' => $device_id
        ]);
    }
    return true;

}

function get_all_music($site, $page_limit)
{
    $data = false;
    $isFake = checkBlockIp() ? 1 : 0;
    $load_song = $site->load_categories;
    switch ($load_song) {
        case 1:
            $data = get_songs($site, $page_limit, 'music_view_count');
            break;
        case 2:
            $data = get_songs($site, $page_limit, 'updated_at');
            break;
        case 3:
            $data = get_songs($site, $page_limit, 'music_like_count');
            break;
        case 4:
            $data = get_songs($site, $page_limit, 'music_title');
            break;
        default:
            $data = get_songs($site, $page_limit);

            break;
    }
    return $data;
}

function get_categories($site, $page_limit)
{
    $data = false;
    $isFake = checkBlockIp() ? 1 : 0;
    switch (load_categories($site)) {
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
                    ->where('category_checked_ip', $isFake)
                    ->orderBy('category_view_count', 'desc')
                    ->withCount('music')
                    ->having('music_count', '>', 0)
                    ->paginate($page_limit);
            break;
        case 2:
            $data =
                $site
                    ->categories()
                    ->where('category_checked_ip', $isFake)
                    ->orderBy('updated_at', 'desc')
                    ->withCount('music')
                    ->having('music_count', '>', 0)
                    ->paginate($page_limit);
            break;
        case 3:
            $data =
                $site
                    ->categories()
                    ->where('category_checked_ip', $isFake)
                    ->orderBy('category_order', 'asc')
                    ->withCount('music')
                    ->having('music_count', '>', 0)
                    ->paginate($page_limit);
            break;
        case 4:
            $data =
                $site
                    ->categories()
                    ->where('category_checked_ip', $isFake)
                    ->orderBy('category_name', 'asc')
                    ->withCount('music')
                    ->having('music_count', '>', 0)
                    ->paginate($page_limit);
            break;
    }
    return $data;
}

function get_category_details($site, $category, $page_limit)
{
    $data = false;
    switch (load_view_by_category($site)) {
        case 0:
            $data = $category
                ->music()
                ->with(['categories' => function ($query) {
                    $query->where('site_id', getSite()->id);
                }])
                ->where('status', 0)
                ->distinct()
                ->inRandomOrder()
                ->paginate($page_limit);
            break;
        case 1:
            $data = $category
                ->music()
                ->with(['categories' => function ($query) {
                    $query->where('site_id', getSite()->id);
                }])
                ->where('status', 0)
                ->distinct()
                ->orderBy('music_like_count', 'desc')
                ->paginate($page_limit);
            break;
        case 2:
            $data = $category
                ->music()
                ->with(['categories' => function ($query) {
                    $query->where('site_id', getSite()->id);
                }])
                ->where('status', 0)
                ->distinct()
                ->orderBy('music_view_count', 'desc')
                ->paginate($page_limit);
            break;
        case 3:
            $data = $category
                ->music()
                ->with(['categories' => function ($query) {
                    $query->where('site_id', getSite()->id);
                }])
                ->where('status', 0)
                ->distinct()
                ->orderBy('updated_at', 'desc')
                ->paginate($page_limit);
            break;
    }
    return $data;
}

function get_songs($site, $page_limit, $order = null)
{
    $data = false;
    $isFake = checkBlockIp() ? 1 : 0;
    if ($order) {
        $data = Musics
            ::with(['categories' => function ($query) use ($isFake, $site) {
                $query
                    ->where('category_checked_ip', $isFake)
                    ->where('site_id', $site->id);
            }])
            ->where('status', 0)
            ->whereHas('categories', function ($query) use ($isFake, $site) {
                $query
                    ->where('category_checked_ip', $isFake)
                    ->where('site_id', $site->id);
            })
            ->distinct()
            ->orderByDesc($order)
            ->paginate($page_limit);
    } else {
        $data = Musics
            ::with(['categories' => function ($query) use ($isFake, $site) {
                $query
                    ->where('category_checked_ip', $isFake)
                    ->where('site_id', $site->id);
            }])
            ->where('status', 0)
            ->whereHas('categories', function ($query) use ($isFake, $site) {
                $query
                    ->where('category_checked_ip', $isFake)
                    ->where('site_id', $site->id);
            })
            ->distinct()
            ->inRandomOrder()
            ->paginate($page_limit);
    }
    return $data;
}

function update_song_view($id)
{
    $return = false;
    $music = Musics::findorfail($id);
    if ($music) {
        $music->music_view_count = $music->music_view_count + 1;
        $music->save();
        $return = $music;
    }
    return $return;
}

function update_song_download($id)
{
    $return = false;
    $music = Musics::findorfail($id);
    if ($music) {
        $music->music_download_count = $music->music_download_count + 1;
        $music->save();
        $return = $music;
    }
    return $return;
}

function update_song_favourite($site, $androidId, $musicId)
{
    $visitorFavorite = VisitorFavorite::where([
        'music_id' => $musicId,
        'visitor_id' => Visitors::where('device_id', $androidId)->value('id'),
        'site_id' => Sites::find($site->id)->value('id'),
    ])->first();


    if ($visitorFavorite) {
        VisitorFavorite::where([
            'music_id' => $musicId,
            'visitor_id' => Visitors::where('device_id', $androidId)->value('id'),
            'site_id' => Sites::find($site->id)->value('id'),
        ])
            ->delete();
        $music = Musics::where('id', $musicId)->first();
        $music->decrement('music_like_count');
        $result = [
            'success' => false,
            'msg' => 'Favourite delete',
        ];

    } else {
        VisitorFavorite::create([
            'music_id' => $musicId,
            'visitor_id' => Visitors::where('device_id', $androidId)->value('id'),
            'site_id' => Sites::find($site->id)->value('id'),
        ])
            ->first();
        $music = Musics::where('id', $musicId)->first();
        $music->increment('music_like_count');
        $result = [
            'success' => true,
            'msg' => 'Favourite successfully',
        ];
    }
    return $result;


}

function get_song_favourite($site, $androidId, $page_limit)
{
    return VisitorFavorite::where([
        'visitor_id' => Visitors::where('device_id', $androidId)->value('id'),
        'site_id' => $site->id
    ])
        ->with('music')
        ->paginate($page_limit);
}

function check_favourite($site, $androidId, $musicId)
{
    $visitorFavorite = VisitorFavorite::where([
        'music_id' => $musicId,
        'visitor_id' => Visitors::where('device_id', $androidId)->value('id'),
        'site_id' => Sites::find($site->id)->value('id'),
    ])->first();
    if ($visitorFavorite) {
        return true;
    } else {
        return false;
    }
}

function get_search_music($site, $search, $page_limit)
{
    $data = false;
    $isFake = checkBlockIp() ? 1 : 0;
    return Musics::
    where('music_title', 'LIKE', '%' . ($search) . '%')
        ->whereHas('categories', function ($query) use ($search, $isFake, $site) {
            $query
                ->where('category_checked_ip', $isFake)
                ->where('site_id', $site->id);
        })
        ->where('status', 0)
        ->distinct()
        ->inRandomOrder()
        ->paginate($page_limit);
//        ->take($page_limit)->get();

}

function get_search_categories($site, $search, $page_limit)
{
    $data = false;
    $isFake = checkBlockIp() ? 1 : 0;
    return
        $site
            ->categories()
            ->where('category_checked_ip', $isFake)
            ->where('category_name', 'LIKE', '%' . ($search) . '%')
            ->inRandomOrder()
            ->withCount('music')
            ->having('music_count', '>', 0)
            ->paginate($page_limit);
}



