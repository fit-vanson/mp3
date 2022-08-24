<?php

namespace App\Http\Controllers;

use App\Musics;
use App\Ringtones;
use App\Tags;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Jenssegers\ImageHash\ImageHash;
use Jenssegers\ImageHash\Implementations\DifferenceHash;

class MusicsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {

        $page_title =  'Musics';
        $tags = Tags::latest()->get();

        $search = null;
        if (isset($_GET['search'])){
            $search = $_GET['search'];
        }
        if (isset($_GET['view']) && $_GET['view'] == 'grid' ){
            $data = Ringtones::latest('ringtone_name')
                ->orwhereRelation('tags','tag_name', 'like', '%' . $search . '%')
                ->paginate(12);
            $data->load('tags');
            return view('ringtones.index',[
                'page_title' => $page_title,
                'tags' => $tags,
                'data' => $data,
            ]);
        }else{
            return view('musics.index',[
                'page_title' => $page_title,
                'tags' => $tags,
            ]);
        }
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
//            $btn  = ' <a href="javascript:void(0)" onclick="editRolesPermissions('.$record->id.')" class="btn btn-warning"><i class="ti-pencil-alt"></i></a>';
            $btn = ' <a href="javascript:void(0)" data-id="'.$record->id.'" class="btn btn-warning editMusics"><i class="ti-pencil-alt"></i></a>';
            $btn .= ' <a href="javascript:void(0)" data-id="'.$record->id.'" class="btn btn-danger deleteMusics"><i class="ti-trash"></i></a>';
            $data_arr[] = array(
                "id" => $record->id,
//                "ringtone_file" => '<a class="image-popup-no-margins" href="'.url('/storage/ringtones').'/'.$record->ringtone_image.'"><img class="img-fluid" alt="'.$record->wallpaper_name.'" src="'.url('/storage/wallpapers/thumbnails').'/'.$record->wallpaper_image.'" width="75"></a>',
                "music_file" => '<audio class="audio-player" controls><source src="'.url('/storage/musics/files').'/'.$record->music_file.'" type="audio/mp3"/></audio>',
                "music_name" => $record->music_name,
                "music_view_count" => $record->music_view_count,
                "music_like_count" => $record->music_like_count,
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

        $rules = [
            'file.*' => 'max:20000|mimes:mp3,txt,jpg',
        ];
        $message = [
            'file.mimes'=>'Định dạng File',
            'file.max'=>'Dung lượng File',

        ];
        $error = Validator::make($request->all(),$rules, $message );
        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }
        $now = new \DateTime('now'); //Datetime
        $monthNum = $now->format('m');
        $dateObj   = DateTime::createFromFormat('!m', $monthNum);
        $monthName = $dateObj->format('F'); // Month
        $year = $now->format('Y'); // Year
        $monthYear = $monthName.$year;

        $dataArray = [];
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
                        'uuid' => uniqid(),
                        'music_name' => $key,
                        'music_image'=> isset($data['music_image']) ? $data['music_image'] : 'default.png' ,
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
        $data->save();
        $data->tags()->sync($request->select_tags);
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

        $music = Musics::where('uuid',$id)->first();

        $link = $music->music_link;
        $check_link =  false;

        if ($link){
            $headers = get_headers($link);
            $check_link = stripos($headers[0],"200 OK") ? true : false;
        }
        if ($check_link){
            $direct_link = $link;
        }else{
            $direct_link = url('/storage/musics/files').'/'.$music->music_file ;
        }
        return redirect($direct_link);
    }

}
