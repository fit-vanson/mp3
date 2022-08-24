<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use App\Sites;
use Illuminate\Http\Request;

class ApiV2Controler extends Controller
{
    public function init(){


        $domain = $_SERVER['SERVER_NAME'];
        $site = Sites::where('site_web',$domain)->first();
        $load_categories = $site->load_categories;
        $isFake = checkBlockIp()?1:0;


        if($load_categories == 0 ){
            $data = $site
                ->categories()
                ->where('category_checked_ip', $isFake)
                ->withCount('music')
                ->having('music_count', '>', 0)
                ->inRandomOrder()
                ->get();
        }
        elseif($load_categories == 1 ){
            $data = $site
                ->categories()
                ->where('category_checked_ip', $isFake)
                ->withCount('music')
                ->having('music_count', '>', 0)
                ->orderBy('category_view_count','desc')
                ->get();
        }
        elseif($load_categories == 2 ){
            $data = $site
                ->categories()
                ->where('category_checked_ip', $isFake)
                ->withCount('music')
                ->having('music_count', '>', 0)
                ->orderBy('updated_at','desc')
                ->get();
        }
        if(isset(\request()->loadingplaylist)){
            $result = [];
            foreach ($data as $item){
                $result[] = [
                    'catID' => $item->id,
                    'catName' => $item->category_name,
                    'thumbnail' => $item->category_image ?  asset('storage/sites/'.$item->site_id.'/categories/'.$item->category_image) : asset('storage/default.png'),
                ];
            }
        }else{
            $result = [
                'cho_tai_khong' => true,
                'them_ung_dung_store_link' => 'Nghe nhạc hay nhất',
                'quangcaofull' => false,
                'banner_id' => 'ca-app-pub-3044948700774356/858630886',
                'inter_ads_id' => 'ca-app-pub-3044948700774356/8322584984',
                'gioi_thieu_ung_dung' => true,
                'id_ung_dung_gioi_thieu' => 'com.my_music',
                'thong_bao_gioi_thieu_app_moi' => '(ADS)Ứng dụng nghe nhạc remix DJ với giao diện mới và tốc độ cực nhanh, khắc phục vấn đề nghe nhạc khi tắt màn hình,chắc chắn bạn sẽ hài lòng,bạn có muốn thử nghiệm không',
                'co_phien_ban_thay_the' => false,
                'id_phien_ban_thay_the' => 'com.pinterest',
                'thong_bao_thay_the' => 'com.pinterest',
            ];
        }



        return json_encode($result);
    }
}
