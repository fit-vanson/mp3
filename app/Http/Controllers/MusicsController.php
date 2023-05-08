<?php

namespace App\Http\Controllers;


use Alaouy\Youtube\Youtube;
use App\Musics;

use App\Tags;
use Carbon\Carbon;
use DateTime;

use Facade\FlareClient\Http\Response;

use GuzzleHttp\Pool;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Mavinoo\Batch\Batch;
use YouTube\YouTubeDownloader;

class MusicsController extends Controller
{

    const PART_LENGTH = 5000;

    public function __construct()
    {
        $this->middleware('auth')->except('streamID','getLinkUrl','getLinkYTB');
    }

    public function index()
    {
        $header = [
            'title' => 'Musics',
            'button' => [
//                'Create'            => ['id'=>'createMusics','style'=>'primary'],
                'Create YTB'        => ['id'=>'createYTB','style'=>'success'],
                'Play List'         => ['id'=>'videoList','style'=>'primary'],
            ]

        ];
        $tags = Tags::latest()->get();
        return view('musics.index')->with(compact('header','tags'));
    }

    public function getIndex(Request $request)
    {

        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // total number of rows per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index

        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        // Total records
        $totalRecords = Musics::select('count(*) as allcount')->count();

        if ($request->get('status') !== null){
            $totalRecordswithFilter = Musics::select('count(*) as allcount')
                ->where('status', $request->get('status'))
                ->where(function ($query) use ($searchValue) {
                    $query
                        ->where('music_id_ytb', 'like', '%' . $searchValue . '%')
                        ->orwhere('music_title', 'like', '%' . utf8_encode($searchValue) . '%')
                        ->orwhereRelation('tags','tag_name','like', '%' . $searchValue . '%');
                });
            // Get records, also we have included search filter as well
            $records = Musics::with('tags')
                ->where('status', $request->get('status'))
                ->where(function ($query) use ($searchValue) {
                    $query
                        ->where('music_id_ytb', 'like', '%' . $searchValue . '%')
                        ->orwhere('music_title', 'like', '%' . utf8_encode($searchValue) . '%')
                        ->orwhereRelation('tags','tag_name','like', '%' . $searchValue . '%');
                });
        }else{
            $totalRecordswithFilter = Musics::select('count(*) as allcount')
                ->where(function ($query) use ($searchValue) {
                    $query
                        ->where('music_id_ytb', 'like', '%' . $searchValue . '%')
                        ->orwhere('music_title', 'like', '%' . utf8_encode($searchValue) . '%')
                        ->orwhereRelation('tags', 'tag_name', 'like', '%' . $searchValue . '%');
                });

            // Get records, also we have included search filter as well
            $records = Musics::with('tags')
                ->where(function ($query) use ($searchValue) {
                    $query
                        ->where('music_id_ytb', 'like', '%' . $searchValue . '%')
                        ->orwhere('music_title', 'like', '%' . utf8_encode($searchValue) . '%')
                        ->orwhereRelation('tags', 'tag_name', 'like', '%' . $searchValue . '%');
                });
        }

        if ($request->get('null_tag') == 1){
            $totalRecordswithFilter = $totalRecordswithFilter->whereDoesntHave('tags');
            $records = $records->whereDoesntHave('tags');
        }
        $totalRecordswithFilter = $totalRecordswithFilter->count();
        $records = $records->select('*')
            ->orderBy($columnName, $columnSortOrder)
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();
        foreach ($records as $record) {
            $btn = ' <a href="javascript:void(0)" data-id="'.$record->id.'" class="btn btn-warning editMusic"><i class="ti-pencil-alt"></i></a>';
            $btn .= ' <a href="javascript:void(0)" data-id="'.$record->id.'" class="btn btn-danger deleteMusic"><i class="ti-trash"></i></a>';

            $image_url = '<a target="_blank" href="https://www.youtube.com/watch?v='.$record->music_id_ytb.'"><img alt="'.$record->music_id_ytb.'"  src="'.$record->music_thumbnail_link .'" width="150" "></a>';
            $style_time = $record->expire > time() ?: 'red';
            $music_ytb  =  '<a class="popup-music btn btn-secondary  align-middle" href="'.asset('getLinkYTB').'/'.$record->music_id_ytb.'">Open Music</a><p  style="color: '.$style_time.'">'.date('h:i:s d-m-Y',$record->expire).'</p>';
            $data_arr[] = array(
                "id" => $record->id,
                "music_thumbnail_link" => $image_url,
                "music_id_ytb" =>$music_ytb,
                "music_view_count" => $record->music_view_count,
                "music_download_count" => $record->music_download_count,
                "music_like_count" => $record->music_like_count,
                "tags" => $record->tags,
                "status" => $record->status,
                "action" => $btn,
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr,
        );

        echo json_encode($response);


    }

    public function create(Request $request){

        $now = new \DateTime('now'); //Datetime
        $monthNum = $now->format('m');
        $dateObj   = DateTime::createFromFormat('!m', $monthNum);
        $monthName = $dateObj->format('F'); // Month
        $year = $now->format('Y'); // Year
        $monthYear = $monthName.$year;

        $dataArray = [];
        try {
            foreach ($request->file as $file){

                $filenameWithExt=$file->getClientOriginalName();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $fileNameToStore = time().'_'.Str::random(10);

                switch ($extension){
                    case 'txt':
                        $file = file_get_contents($file);
                        $tags = explode(',',$file);
                        $tags = array_map('trim', $tags);
                        $getTags = Tags::select('id')->whereIn('tag_name',$tags)->get()->pluck('id')->toArray();
                        $dataArray[$filename]['tags']=  $getTags;
                        break;
                    case 'jpg':
                        $path_image   =  storage_path('app/public/musics/images/'.$monthYear.'/');
                        if (!file_exists($path_image)) {
                            mkdir($path_image, 0777, true);
                        }

                        $NameToStore = $fileNameToStore.'.'.$extension;
                        $img = Image::make($file);
                        $img->resize(500, 500)
                            ->save($path_image.$NameToStore,60);
                        $dataArray[$filename]['music_image'] = $monthYear.'/'.$NameToStore;
                        break;
                    case 'mp3':
                        $NameToStore = $fileNameToStore.'.'.$extension;
                        $path = Storage::disk('music_files')->putFileAs($monthYear,$file, $NameToStore);
                        $dataArray[$filename]['music_file'] = $path;
                        break;
                }
            }



            foreach ($dataArray as $key=>$data){
                if(isset($data['tags']) && !empty($data['tags']) && isset($data['music_file']) && !empty($data['music_file']) ){
                    $music = Musics::updateOrCreate(
                        [
                            'music_name' => $key,
                        ],
                        [
                            'uuid' => uniqid(),
                            'music_image'=> $data['music_image'] ?? null,
                            'music_file'=> $data['music_file'],
                            'music_view_count' => 1000,
                            'music_like_count' => 1000,
                            'music_download_count' => 1000,
                            'music_feature' => rand(0,1),
                            'music_status' => 0,
                            'music_type' => 'mp3'
                        ]);

                    $music->tags()->sync($data['tags']);
                }else{
                    try {
                        $pathFile    =   storage_path('app/public/musics/files/').$data['music_file'];
                        if(file_exists($pathFile)){
                            unlink($pathFile);
                        }
                    }catch (\Exception $ex) {
                        Log::error('Message: File ' . $ex->getMessage() .'--: '.$key. ' -----' . $ex->getLine());
                    }
                    try {
                        $pathImage   =   storage_path('app/public/musics/images/').$data['music_image'];
                        if(file_exists($pathImage)){
                            unlink($pathImage);
                        }
                    }catch (\Exception $ex) {
                        Log::error('Message: Image' . $ex->getMessage() .'--: '.$key. ' -----' . $ex->getLine());
                    }

                }
            }
        }catch (\Exception $exception) {
            Log::error('Message:' . $exception->getMessage() . '--- create music : ' . $exception->getLine());
        }

        return response()->json(['success'=>'Thành công']);

    }

    public function edit($id){

        $music = Musics::with('tags')->find($id);
        return response()->json( $music);
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $data= Musics::find($id);
//        $data->music_id_ytb = $request->music_id_ytb;
        $data->music_thumbnail_link = $request->music_thumbnail_link;
        $data->save();
        $data->tags()->sync($request->select_tags);
        return response()->json(['success'=>'Cập nhật thành công']);
    }

    public function update_multiple(Request $request)
    {
        $data_multiple = array_filter(explode("\r\n",$request->update_multiple));

        $music = new Musics();

        $data_arr = [];

        foreach ($data_multiple as $item){
            try {
                [$id, $link_1, $link_2, $id_ytb] = explode("|",$item);

                $data_arr [] = [
                    'id' =>(int)$id,
                    'music_link_1' =>trim($link_1),
                    'music_link_2' =>trim($link_2),
                    'music_id_ytb' =>trim($id_ytb),
                ];
            }catch (\Exception $exception) {
                Log::error('Message: Update multiple music ' . $exception->getMessage() . ' ---- '.$item .'------'. $exception->getLine());
            }

        }
        $index = 'id';
        batch()->update($music, $data_arr, $index);
        return response()->json(['success'=>'Cập nhật thành công']);
    }

    public function delete($id)
    {
        $music = Musics::find($id);
        $pathFile    =   storage_path('app/public/musics/files/').$music->music_file;
        $pathImage    =   storage_path('app/public/musics/files/').$music->music_image;

        try {
            if(file_exists($pathFile)){
                unlink($pathFile);
            }
            if(file_exists($pathImage)){
                unlink($pathImage);
            }

        }catch (\Exception $ex) {
            Log::error($ex->getMessage());
        }
        $music->tags()->detach();
//        $music->visitor_favorites()->delete();
        $music->delete();
        return response()->json(['success'=>'Xoá thành công']);
    }

    public function deleteSelect(Request $request)
    {
        $id= $request->id;
        $musics = Musics::whereIn('id',$id)->get();

        foreach ( $musics as $music){
            $pathFile    =   storage_path('app/public/musics/files/').$music->music_file;
            $pathImage    =   storage_path('app/public/musics/files/').$music->music_image;

            try {
                if(file_exists($pathFile)){
                    unlink($pathFile);
                }
                if(file_exists($pathImage)){
                    unlink($pathImage);
                }

            }catch (\Exception $ex) {
                Log::error($ex->getMessage());
            }
            $music->tags()->detach();
//            $wallpaper->visitor_favorites()->delete();
            $music->delete();
        }
        return response()->json(['success'=>'Xóa thành công.']);
    }


    public function streamID($id){
        $music = Musics::where('uuid',$id)->firstOrFail();

        if (isset($music->music_url_link_ytb) && Carbon::now()->timestamp < $music->time_get_url_ytb ){
            $link = $music->music_url_link_ytb;
        }else{
            $link = $this->checkLink($music->music_link_1) ? $this->checkLink($music->music_link_1) :
                ( $this->checkLink($music->music_link_2) ? $this->checkLink($music->music_link_2) : url('/storage/musics/files').'/'.$music->music_file) ;
            $music->update([
                'music_url_link_ytb'=>$this->getLinkUrl($music->music_id_ytb,'url') ? $this->getLinkUrl($music->music_id_ytb,'url') : null,
                'music_duration'=>$this->getLinkUrl($music->music_id_ytb,'lengthSeconds'),
                'time_get_url_ytb'=>time()+21500]);
        }
        if (isset(\request()->action)){
            $action = \request()->action;
            switch ($action){
                case 'view':
//                    dd($music);
                    $music->increment('music_view_count');
                    break;
                case 'download':
                    $music->increment('music_download_count');
                    break;
            }
        }

        return redirect($link);
    }

    function checkLink($url){
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            $headers = get_headers($url);
            return stripos($headers[0],"200 OK") ? $url : false;
        } else {
            return false;
        }
    }

    public function getLinkUrl($id_ytb, $option=null)
    {
        try {
            $youtube = new YouTubeDownloader();
            $downloadOptions = $youtube->getDownloadLinks("https://www.youtube.com/watch?v=" . $id_ytb);
            if ( $downloadOptions->getAllFormats() && $downloadOptions->getInfo()) {

                if(isset($_GET['action']) && $_GET['action'] =='all' ){
                    dd($downloadOptions);
                    return response()->json($downloadOptions->getVideoInfo());

                }else{
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
                }



            } else {
                return  false;
            }

        }catch (\Exception $ex) {
            Log::error('Error: Not link ID YTB: '.$id_ytb);
            return  false;
        }

    }

    public function getInfoYTB(Request $request): \Illuminate\Http\JsonResponse
    {
        $ytb_id = base64_decode($request->ytb_id);
        $youtube = new YouTubeDownloader();
        $list_id = preg_split("/[|]+/",$ytb_id);

        $dataArr = [];
        foreach ($list_id as $id){
            try {

                if (filter_var($id, FILTER_VALIDATE_URL) === FALSE) {
                    $downloadOptions = $youtube->getDownloadLinks("https://www.youtube.com/watch?v=" . trim($id));
                }else{
                    $downloadOptions = $youtube->getDownloadLinks(trim($id));
                }

                $info = $downloadOptions->getSplitFormats()->audio;
                $dataArr[] = [
                    'videoId' => $info->getId(),
                    'title' => $info->getTitle(),
                    'viewCount' => $info->getViewCount(),
                    'keywords' => $info->getKeywords()? implode(",\n",$info->getKeywords()):'',
                    'shortDescription' => $info->getShortDescription(),
                    'lengthSeconds' => gmdate("H:i:s",$info->getLengthSeconds()),
                    'image' => $info->getThumbnailMqdefault()['url'],
                    'url_audio' => $downloadOptions->getSplitFormats()->audio->url,
                ];
            }catch (\Exception $ex) {
                Log::error('Error: Not link ID YTB: '.$id);
            }
        }
        return response()->json($dataArr);

    }

    public function createYTB(Request $request){
        $rules = [
            'select_tags' =>'required',
        ];
        $message = [
            'select_tags.required'=>'Vui lòng chọn tags',
        ];
        $error = Validator::make($request->all(),$rules, $message );

        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }
        $data = $request->getInfo;
        foreach ($data as $key=>$value){
            if(isset($value['download'])){

                $source = $value['url_audio'];

                header('Content-Type: application/octet-stream');
                header("Content-Transfer-Encoding: Binary");
                header("Content-disposition: attachment; filename=\"" . basename($source) . "\"");
                readfile($source);

            }
            $music = Musics::updateOrCreate(
                [
                    'music_id_ytb' =>$key
                ],
                [
                    'music_url_link_audio_ytb'=>$value['url_audio'],
                    'music_title'=> ($value['title']),
                    'music_thumbnail_link'=> $value['image'],
                    'expire' => time(),
                ]
            );
            $music->tags()->syncWithoutDetaching($request->select_tags);
        }
        return response()->json(['success'=>'Thành công.']);
    }

    function asyncDownload($dir, $url, $contentlength = 0){
        $start = 0;
        $request = [];
        file_put_contents($dir,'');
        for($i = 0; $i<= ($contentlength/$this::PART_LENGTH); $i++){
            $end = ($start + $this::PART_LENGTH > $contentlength) ? $contentlength : $start + $this::PART_LENGTH;
            $request [] = new Request('GET',$url, ($contentlength == 0 ) ? [] : ['range'=> "bytes={$start}-{$end}"] );
            dd(123);
            $start = $start + $this::PART_LENGTH+1;

        }
        $partlength = $this::PART_LENGTH;
        $pool = new Pool($this->client, $request,
            [
                'concurrency' =>'100',
                'fulfilled' => function(Response $response, $index) use ($partlength,$dir){
                    file_put_contents($dir, substr_replace(file_get_contents($dir),$response->getBody(),$index*($partlength + 1),0));
                }

            ]
        );
        $pool->promise()->wait();

    }

    function download($streamURL, $name, $contentLength)
    {

        $maxRead = 1 * 1024 * 1024; // 1MB

        $fh = fopen($streamURL, 'r');

        header('Content-Type: application/octet-stream');
        header('Content-Length: ' . $contentLength);
        header('Content-Disposition: attachment; filename="' . basename($name) . '"');

        while (!feof($fh)) {
            print fread($fh, $maxRead);
            ob_flush();
        }

        exit;
    }

    public function getLinkYTB($id,$action=null){
        $info = Musics::where('music_id_ytb',$id)->firstOrFail();
        if($info->expire < time()){
            try {
                $youtube = new YouTubeDownloader();
                $downloadOptions = $youtube->getDownloadLinks("https://www.youtube.com/watch?v=" . trim($id));

                $music_url_link_audio_ytb = $downloadOptions->getSplitFormats()->audio->url;
                $expire = $this->parse_query($music_url_link_audio_ytb)['expire'];

                $info->update([
                    'music_url_link_audio_ytb'=>$music_url_link_audio_ytb,
                    'lengthSeconds'=>$downloadOptions->getInfo()->getLengthSeconds(),
                    'expire' => $expire,
                    'status'=> 0
                ]);
            }catch (\Exception $exception) {
                $info->update(['status'=>1]);
            }
        }
        $link = $info->music_url_link_audio_ytb;
        if (isset($action) && $action== 'url'){
            return $link;
        }else{
            return redirect($link);
        }

    }

    public function getYtbError($status = 1, $limit = 10, $time = 3) : JsonResponse {
        $YouTubeDownloader = new YouTubeDownloader();
        $status = $_GET['status'] ?? $status;
        $limit = $_GET['limit'] ?? $limit;
        $time = $_GET['time'] ?? $time;
        $musics = Musics::where('status', $status)->paginate($limit);
        if (isset($_GET['view'])){
            $musics = Musics::where('status', '<>', $status)->paginate($limit);
            dd($musics);
        }

        if ($musics->count() === 0) {
            return response()->json(['success' => true]);
        }

        $updatedMusics = [];
        $musics->each(function($music) use ($YouTubeDownloader, &$updatedMusics) {
            try {
                $downloadOptions = $YouTubeDownloader->getDownloadLinks("https://www.youtube.com/watch?v=" . trim($music->music_id_ytb));
                $music->music_url_link_audio_ytb = $downloadOptions->getSplitFormats()->audio->url;
                $music->status = 0;
            } catch (\Exception $e) {
                $music->music_url_link_audio_ytb = null;
                $music->status += 1;
                Log::error("Error getting YouTube download links for music ID {$music->music_id_ytb}: " . $e->getMessage());
            }
            $updatedMusics[] =[
                'id' => $music->id,
                'music_url_link_audio_ytb' => $music->music_url_link_audio_ytb,
                'status' => $music->status,
                'updated_at' => now(),
            ];
        });
        $musicsInstance = new Musics;
        $index = 'id';
        $result = batch()->update($musicsInstance, $updatedMusics, $index);

        if ($musics->hasMorePages()) {
            header("Refresh: $time; URL=" . $musics->nextPageUrl() . "&status=$status&time=$time&limit=$limit");
            return response()->json([
                'success' => false,
                'result' => $result,
                'next_page_url' => $musics->nextPageUrl(),
                'music_update' => $updatedMusics,
            ]);
        }

        return response()->json([
            'success' => true, 'result' => $result,    'music_update' => $updatedMusics,
        ]);
    }





    function parse_query($var)
    {
        /**
         *  Use this function to parse out the query array element from
         *  the output of parse_url().
         */
        $var  = parse_url($var, PHP_URL_QUERY);
        $var  = html_entity_decode($var);
        $var  = explode('&', $var);
        $arr  = array();

        foreach($var as $val)
        {
            $x          = explode('=', $val);
            $arr[$x[0]] = $x[1];
        }
        unset($val, $x, $var);
        return $arr;
    }

    public function listVideos(Request $request, $page_limit = 50){

        if (isset($request->link_mucsic)){
            $ytb_id = base64_decode($request->link_mucsic);
            $youtube = new YouTubeDownloader();
            if (filter_var($ytb_id, FILTER_VALIDATE_URL) === FALSE) {
                $downloadOptions = $youtube->getDownloadLinks("https://www.youtube.com/watch?v=" . trim($ytb_id));
            }else{
                $downloadOptions = $youtube->getDownloadLinks(trim($ytb_id));
            }
            $channelId = $downloadOptions->getInfo()->getChannelId();
            return response()->json($channelId);
        }else{
            $limit = $_GET['page_limit']?? $page_limit;
            $API_KEY = env('YOUTUBE_API_KEY','AIzaSyD-fR2VVsOhrx6cF80FEmGOaminbeLPl2k');

            try {
                $video = new Youtube($API_KEY);
                $data_arr = [];
                if(isset($request->channel_id)){
                    $list_video = $video->listChannelVideos(base64_decode($request->channel_id),$limit);
                    foreach ($list_video as $item ){
                        $data_arr[] = array(
                            "videoId" => $item->id->videoId,
                            "title" =>$item->snippet->title,
                            "thumbnails" => '<img alt="'.$item->id->videoId.'"  src="https://i.ytimg.com/vi_webp/'.$item->id->videoId.'/mqdefault.webp" width="150" ">',
                        );
                    }
                }elseif (isset($request->playlist_id)){
                    $list_video = $video->getPlaylistItemsByPlaylistId(base64_decode($request->playlist_id));
                    foreach ($list_video['results'] as $item ){
                        $data_arr[] = array(
                            "videoId" => $item->snippet->resourceId->videoId,
                            "title" =>$item->snippet->title,
                            "thumbnails" => '<img alt="'.$item->snippet->resourceId->videoId.'"  src="https://i.ytimg.com/vi_webp/'.$item->snippet->resourceId->videoId.'/mqdefault.webp" width="150" ">',
                        );
                    }
                }
//                $draw = $request->get('draw');
//                $response = array(
//                    "draw" => intval($draw),
//                    "iTotalRecords" => $video->page_info['totalResults'],
//                    "aaData" => $data_arr,
//                );
//                return  json_encode($response);

                return response()->json($data_arr);
            } catch (\Exception $e) {
                Log::error('Error: listChannelVideos: ');
            }
        }


    }

    public function createListVideos(Request $request){
        $rules = [
            'videoID' =>'required',
        ];
        $message = [
            'videoID.required'=>'Vui lòng chọn ít nhất 1 video',

        ];
        $error = Validator::make($request->all(),$rules, $message );

        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }
        $videosId = $request->videoID;
        foreach ($videosId as $video){
            $music = Musics::updateOrCreate(
                [
                    'music_id_ytb' =>$video['value']
                ],
                [
                    'music_title'=> $video['name'],
                    'music_thumbnail_link'=>  'https://i.ytimg.com/vi_webp/'.$video['value'].'/mqdefault.webp',
                    'expire' => time(),
                ]
            );
            $music->tags()->syncWithoutDetaching($request->tags);
        }
        return response()->json(['success'=>'Thành công.']);
    }


}
