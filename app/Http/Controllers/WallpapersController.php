<?php

namespace App\Http\Controllers;

use App\Categories;
use App\Tags;
use App\Wallpapers;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;



class WallpapersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $page_title =  'Wallpapers';
//        $categories = Categories::get();
        $tags = Tags::all();
        return view('wallpapers.index',[
            'page_title' => $page_title,
            'tags' => $tags
        ]);
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
        $totalRecords = Wallpapers::select('count(*) as allcount')->count();
        $totalRecordswithFilter = Wallpapers::select('count(*) as allcount')
            ->where('wallpaper_name', 'like', '%' . $searchValue . '%')
            ->orwhereRelation('tags','tag_name','like', '%' . $searchValue . '%')
            ->count();

        // Get records, also we have included search filter as well
        $records = Wallpapers::with('tags')
            ->where('wallpaper_name', 'like', '%' . $searchValue . '%')
            ->orwhereRelation('tags','tag_name','like', '%' . $searchValue . '%')
            ->select('*')
            ->orderBy($columnName, $columnSortOrder)
            ->skip($start)
            ->take($rowperpage)
            ->get();
        $data_arr = array();
        foreach ($records as $record) {
//            $btn  = ' <a href="javascript:void(0)" onclick="editRolesPermissions('.$record->id.')" class="btn btn-warning"><i class="ti-pencil-alt"></i></a>';
//            $btn = ' <a href="javascript:void(0)" data-id="'.$record->id.'" class="btn btn-warning editWallpapers"><i class="ti-pencil-alt"></i></a>';
            $btn = ' <a href="javascript:void(0)" data-id="'.$record->id.'" class="btn btn-danger deleteWallpapers"><i class="ti-trash"></i></a>';
            $data_arr[] = array(
                "id" => $record->id,
                "wallpaper_image" => '<a class="image-popup-no-margins" href="storage/wallpapers/'.$record->wallpaper_image.'"><img class="img-fluid" alt="'.$record->wallpaper_name.'" src="storage/wallpapers/thumbnails/'.$record->wallpaper_image.'" width="75"></a>',
                "wallpaper_name" => $record->wallpaper_name,
                "image_extension" => $record->image_extension,
                "wallpaper_view_count" => $record->wallpaper_view_count,
                "wallpaper_like_count" => $record->wallpaper_like_count,
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
            'file.*' => 'max:20000|mimes:jpeg,jpg,png,gif',
            'select_tags' => 'required'
        ];
        $message = [
            'file.mimes'=>'Định dạng File',
            'file.max'=>'Dung lượng File',
            'select_tags.required'=>'Tags',
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
        $path_origin    =  storage_path('app/public/wallpapers/'.$monthYear.'/');
        if (!file_exists($path_origin)) {
            mkdir($path_origin, 0777, true);
        }
        $path_thumbnails    =  storage_path('app/public/wallpapers/thumbnails/'.$monthYear.'/');
        if (!file_exists($path_thumbnails)) {
            mkdir($path_thumbnails, 0777, true);
        }
        $insert_data = [];
        foreach ($request->file as $file){
            $filenameWithExt=$file->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $nameImage =  preg_replace('/[^A-Za-z0-9\-\']/', '_', $filename);
            $extension = $file->getClientOriginalExtension();
            $fileNameToStore = $nameImage.'_'.time().'.'.$extension;
            $img = Image::make($file);

            if($img->mime() == "image/gif"){
                copy($file->getRealPath(), $path_origin.$fileNameToStore);
                copy($file->getRealPath(), $fileNameToStore.$fileNameToStore);
            }else{
                $img->save($path_origin.$fileNameToStore);
                $img->resize(360, 640,function ($constraint) {
                    $constraint->aspectRatio();
                })->save($path_thumbnails.$fileNameToStore);
            }

            $origin =  $monthYear.'/'.$fileNameToStore;

            $wallpaper = Wallpapers::create([
                'wallpaper_name' => $filename,
                'wallpaper_image'=> $origin,
                'wallpaper_view_count' => rand(500,2000),
                'wallpaper_like_count' => rand(500,2000),
                'wallpaper_download_count' => rand(500,2000),
                'wallpaper_feature' => rand(0,1),
                'image_extension' => $img->mime()
            ]);


            $wallpaper->tags()->attach($request->select_tags);
            }
        return response()->json(['success'=>'Thành công','data'=>$wallpaper]);

    }
    public function edit($id){

        $categories = Categories::find($id);
        return response()->json([
            'categories' =>$categories,
        ]);
    }
    public function update(Request $request)
    {
        $id = $request->id;
        $rules = [
            'category_name' =>'unique:categories,category_name,'.$id.',id',

        ];
        $message = [
            'category_name.unique'=>'Category đã tồn tại',

        ];
        $error = Validator::make($request->all(),$rules, $message );
        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }
        $data= Categories::find($id);
        $data->category_name = $request->category_name;
        $data->category_order = $request->category_order;
        $data->category_view_count = $request->category_view_count;
        $data->category_checked_ip = $request->category_checked_ip ? 0 : 1 ;

        if($request->image){
            if ($data->category_image != 'default.png'){
                $path_Remove =   storage_path('app/public/categories/').$data->category_image;
                if(file_exists($path_Remove)){
                    unlink($path_Remove);
                }
            }

            $file = $request->image;
            $filename = Str::slug($request->category_name);
            $extension = $file->getClientOriginalExtension();
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            $now = new \DateTime('now'); //Datetime
            $monthNum = $now->format('m');
            $dateObj   = DateTime::createFromFormat('!m', $monthNum);
            $monthName = $dateObj->format('F'); // Month
            $year = $now->format('Y'); // Year
            $monthYear = $monthName.$year;
            $path_image    =  storage_path('app/public/categories/'.$monthYear.'/');
            if (!file_exists($path_image)) {
                mkdir($path_image, 0777, true);
            }
            $img = Image::make($file);
            $img->save($path_image.$fileNameToStore);
            $path_image =  $monthYear.'/'.$fileNameToStore;
            $data->category_image = $path_image;
        }

        $data->save();



        return response()->json(['success'=>'Cập nhật thành công']);
    }
    public function delete($id)
    {
        $wallpaper = Wallpapers::find($id);
        $path    =   storage_path('app/public/wallpapers/').$wallpaper->wallpaper_image;
        $pathThumbnail    =   storage_path('app/public/wallpapers/thumbnails/').$wallpaper->wallpaper_image;
        try {
            if(file_exists($path)){
                unlink($path);
            }
            if(file_exists($pathThumbnail)){
                unlink($pathThumbnail);
            }
        }catch (\Exception $ex) {
            Log::error($ex->getMessage());
        }
        $wallpaper->tags()->detach();
        $wallpaper->visitor_favorites()->delete();
        $wallpaper->delete();
        return response()->json(['success'=>'Xoá thành công']);
    }

    public function deleteSelect(Request $request)
    {
        $id= $request->id;
        $wallpapers = Wallpapers::whereIn('id',$id)->get();

        foreach ( $wallpapers as $wallpaper){
            $path    =   storage_path('app/public/wallpapers/').$wallpaper->wallpaper_image;
            $pathThumbnail    =   storage_path('app/public/wallpapers/thumbnails/').$wallpaper->wallpaper_image;
            try {
                if(file_exists($path)){
                    unlink($path);
                }
                if(file_exists($pathThumbnail)){
                    unlink($pathThumbnail);
                }
            }catch (\Exception $ex) {
                Log::error($ex->getMessage());
            }
            $wallpaper->tags()->detach();
            $wallpaper->visitor_favorites()->delete();
            $wallpaper->delete();
        }
        return response()->json(['success'=>'Xóa thành công.']);
    }


    public function import(){
//        $walls = Wallpapers::all();
//
//        $cateHas = [];
//        foreach ($walls as $wall){
//            $cate = [
//                'category_id' => $wall->cate_id,
//                'wallpaper_id' => $wall->id
//            ];
//            $cateHas[] = $cate;
//        }
//
//
//        CategoriesHasWallpaper::insert($cateHas);

        return view('wallpapers.import');

    }
    public function postImport(Request $request){
        $file = file($request->file->getRealPath());
        $data = array_slice($file,1);
        $parts = array_chunk($data,1000);
        foreach ($parts as $index =>$part){
            $fileName = resource_path('files/wallpapers/'.date('y-m-d-H-i-s-').$index.'.csv');
            file_put_contents($fileName, $part);
        }
        return route('wallpapers.importToDb');
    }
    public function importToDb(){
        $path = resource_path('files/wallpapers/*.csv');
        $g = glob($path);
        foreach (array_slice($g,0,1) as $file){
            $data = array_map('str_getcsv',file($file));

            foreach ($data as $row){
//                dd($row);
                Wallpapers::updateOrCreate(
                    [
                       'id' => $row[0],
                    ],
                    [

                        'wallpaper_name' => $row[2],
                        'wallpaper_image' => $row[5],
                        'wallpaper_view_count' => rand(500,3000),
                        'wallpaper_like_count' => rand(500,3000),
                        'wallpaper_download_count' => rand(500,3000),
                        'wallpaper_feature' => rand(0,1),
                        'image_extension' => $row[10],
                    ]);
            }
            unlink($file);
        }

//        echo '<META http-equiv="refresh" content="1;URL=' . route('wallpapers.importToDb') . '">';
    }

    public function optimization(){
        ini_set('max_execution_time', 1800);
//        $path = public_path('storage/wallpapers/');
//        $dir = scandir($path);
//        $dir = array_slice($dir, 2);;
//        foreach ($dir as $r){
//            if (!file_exists($path.'thumbnails/'.$r)) {
//                mkdir($path.'thumbnails/'.$r, 0777, true);
//            }
//        }
        $wallpapers = Wallpapers::where('image_extension','<>','image/gif')->paginate(20);
        $page = $wallpapers->currentPage()+1;
        foreach ($wallpapers as $wallpaper){
            $path = public_path('storage/wallpapers/'.$wallpaper->wallpaper_image);
            $pathToImage = public_path('storage/wallpapers/thumbnails/'.$wallpaper->wallpaper_image);
            $optimizerChain = OptimizerChainFactory::create();
            $optimizerChain->optimize($path);
            $optimizerChain->optimize($pathToImage);

//            $img = Image::make($pathToImage);
//            $img->resize(360, 640);
//            $img->save($path.'thumbnails/'.$wallpaper->wallpaper_image);
            echo '<br>'.$wallpaper->id . ' - '. $wallpaper->wallpaper_name .' - '. $wallpaper->wallpaper_image;
        }
        echo '<META http-equiv="refresh" content="1;URL=' . route('wallpapers.optimization'). '?page='.$page.'">';
    }
}
