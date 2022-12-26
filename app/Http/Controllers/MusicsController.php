<?php

namespace App\Http\Controllers;

use App\Musics;

use App\Tags;
use Carbon\Carbon;
use DateTime;

use Facade\FlareClient\Http\Response;

use GuzzleHttp\Pool;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;


use YouTube\YouTubeDownloader;


class MusicsController extends Controller
{

    const PART_LENGTH = 5000;

    public function __construct()
    {
        $this->middleware('auth')->except('streamID','getLinkUrl','getLinkYTB');
    }
//    public function index()
//    {
//        $page_title =  'Musics';
//        $action = ['update_multiple','delete_multiple',];
//        $tags = Tags::latest()->get();
//
//        $search = null;
//        if (isset($_GET['search'])){
//            $search = $_GET['search'];
//        }
//        if (isset($_GET['view']) && $_GET['view'] == 'grid' ){
//            $data = Ringtones::latest('ringtone_name')
//                ->orwhereRelation('tags','tag_name', 'like', '%' . $search . '%')
//                ->paginate(12);
//            $data->load('tags');
//            return view('ringtones.index',[
//                'page_title' => $page_title,
//                'tags' => $tags,
//                'data' => $data,
//            ]);
//        }else{
//            return view('musics.index',[
//                'page_title' => $page_title,
//                'action' => $action,
//                'tags' => $tags,
//            ]);
//        }
//    }

    public function index()
    {
        $header = [
            'title' => 'Musics',
            'button' => [
//                'Create'            => ['id'=>'createMusics','style'=>'primary'],
                'Create YTB'        => ['id'=>'createYTB','style'=>'success'],
                'Update Multiple'   => ['id'=>'update_multipleMusics','style'=>'warning'],
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

        $totalRecordswithFilter = Musics::select('count(*) as allcount')
            ->where('music_name', 'like', '%' . $searchValue . '%')
            ->orwhereRelation('tags','tag_name','like', '%' . $searchValue . '%')
            ->count();

        // Get records, also we have included search filter as well
        $records = Musics::with('tags')
            ->where('music_name', 'like', '%' . $searchValue . '%')
            ->orwhereRelation('tags','tag_name','like', '%' . $searchValue . '%')
            ->select('*')
            ->orderBy($columnName, $columnSortOrder)
            ->skip($start)
            ->take($rowperpage)
            ->get();

        if ($request->get('null_tag') == 1){
            $totalRecordswithFilter = Musics::select('count(*) as allcount')
                ->doesntHave('tags')
                ->where('music_name', 'like', '%' . $searchValue . '%')
                ->count();
            $records = Musics::doesntHave('tags')
                ->where('music_name', 'like', '%' . $searchValue . '%')
                ->select('*')
                ->orderBy($columnName, $columnSortOrder)
                ->skip($start)
                ->take($rowperpage)
                ->get();
        }

        $data_arr = array();
        foreach ($records as $record) {
            $btn = ' <a href="javascript:void(0)" data-id="'.$record->id.'" class="btn btn-warning editMusics"><i class="ti-pencil-alt"></i></a>';
            $btn .= ' <a href="javascript:void(0)" data-id="'.$record->id.'" class="btn btn-danger deleteMusics"><i class="ti-trash"></i></a>';

            $image = $record->music_image ?  '<img class="rounded-circle" alt="'.$record->music_name.'"  src="'.url('storage/musics/images/'.$record->music_image) .'" width="150">'   : '<img class="rounded-circle" alt="'.$record->music_name.'"  src="'. url('storage/default.png') .'" width="150">' ;
            $image_url = $record->music_url_image ? '<img class="rounded-circle" alt="'.$record->music_name.'"  src="'.$record->music_url_image .'" width="150" ">' : null;

            $music_file     = $record->music_file    ?   '<h5 class="mt-0 font-16">On Site</h5><audio class="audio-player" controls><source src="'.url('/storage/musics/files').'/'.$record->music_file.'" type="audio/mp3"/></audio>' :  null;
//            $music_link_1   = checkLink($record->music_link_1)  ?   '<h5 class="mt-0 font-16">Link 1</h5><audio class="audio-player" controls><source src="'.$record->music_link_1.'" type="audio/mp3"/></audio>' : null ;
            $music_link_1   = '<h5 class="mt-0 font-16">Link 1</h5><audio class="audio-player" controls><source src="'.$record->music_link_1.'" type="audio/mp3"/></audio>';
            $music_link_2   = '<h5 class="mt-0 font-16">Link 2</h5><audio class="audio-player" controls><source src="'.$record->music_link_2.'" type="audio/mp3"/></audio>';
//            $music_link_2   = checkLink($record->music_link_2)  ?   '<h5 class="mt-0 font-16">Link 2</h5><audio class="audio-player" controls><source src="'.$record->music_link_2.'" type="audio/mp3"/></audio>' : null ;
            $music_ytb      = $record->music_id_ytb ? '<h5 class="mt-0 font-16">YTB</h5><audio class="audio-player" controls><source src="'.$this->getLinkUrl($record->music_id_ytb,'url').'" type="audio/mp3"/></audio>' : null ;


            $data_arr[] = array(
                "id" => $record->id,
                "music_file" => $music_file.$music_link_1.$music_link_2.$music_ytb,
//                "music_file" => $check,
                "music_name" => $image.$image_url.  '<h5 class="mt-0 font-16">'.$record->id.'</h5>'  .     '<span class="card-title-desc">'.$record->music_name.'</span>',
                "music_view_count" => $record->music_view_count,
                "music_like_count" => $record->music_like_count,
                "music_link" => $record->music_link ? '<a target="_blank" href="'.$record->music_link.'" class="btn btn-outline-warning"><i class="ti-link"></i></a>': null,
                "tags" => $record->tags,
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


//        $rules = [
//            'file.*' => 'max:20000|mimes:mp3,txt,jpg',
//        ];
//        $message = [
//            'file.mimes'=>'Định dạng File',
//            'file.max'=>'Dung lượng File',
//
//        ];
//        $error = Validator::make($request->all(),$rules, $message );
//        if($error->fails()){
//            return response()->json(['errors'=> $error->errors()->all()]);
//        }
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
        return response()->json([
            'music' => $music,
        ]);
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $data= Musics::find($id);
        $data->update($request->all());
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
//        $ringtone->visitor_favorites()->delete();
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

    public function getInfoYTB($_id): \Illuminate\Http\JsonResponse
    {
        $youtube = new YouTubeDownloader();

        $list_id = preg_split("/[|,]+/",$_id);

        $dataArr = [];
        foreach ($list_id as $id){
            try {
                $downloadOptions = $youtube->getDownloadLinks("https://www.youtube.com/watch?v=" . trim($id));

                $info = $downloadOptions->getInfo();




                $dataArr[] = [
                    'videoId' => trim($id),
//                    'author' => $info->getAuthor(),
                    'title' => $info->getTitle(),
                    'viewCount' => $info->getViewCount(),
                    'keywords' => implode(",\n",@$info->getKeywords()),
                    'shortDescription' => $info->getShortDescription(),
                    'lengthSeconds' => gmdate("H:i:s",$info->getLengthSeconds()),
                    'image' => 'https://i.ytimg.com/vi_webp/'.trim($id).'/mqdefault.webp',
                    'url_audio' => $downloadOptions->getSplitFormats()->audio->url,
                ];
            }catch (\Exception $ex) {
                Log::error('Error: Not link ID YTB: '.$id);
//                return response()->json(['error'=>'Not link ID YTB: '.$id]);
            }
        }
        return response()->json($dataArr);

    }

    public function createYTB(Request $request){
        $data = $request->getInfo;
        foreach ($data as $key=>$value){
//            dd($value,$key);
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
                    'music_view_count'=>$value['viewCount'],
                    'music_description'=>base64_encode($value['description']),
                    'music_title'=>base64_encode($value['title']),
                    'music_keywords'=>base64_encode($value['keywords']),
                    'music_thumbnail_link'=>  "https://i.ytimg.com/vi_webp/$key/mqdefault.webp"
                ]
            );
            $music->tags()->sync($request->select_tags);
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

    public function getLinkYTB($id){
        $info = Musics::where('music_id_ytb',$id)->firstOrFail();
        if($info->expire < time()){
            $youtube = new YouTubeDownloader();
            $downloadOptions = $youtube->getDownloadLinks("https://www.youtube.com/watch?v=" . trim($id));

            $music_url_link_audio_ytb = $downloadOptions->getSplitFormats()->audio->url;
            $expire = $this->parse_query($music_url_link_audio_ytb)['expire'];

            $info->update([
                'music_url_link_audio_ytb'=>$music_url_link_audio_ytb,
                'expire' => $expire
            ]);
        }
        $link = $info->music_url_link_audio_ytb;
        return redirect($link);
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



}
