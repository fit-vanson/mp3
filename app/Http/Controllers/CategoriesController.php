<?php

namespace App\Http\Controllers;


use App\Categories;
use App\CategoriesHasSites;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;


class CategoriesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $page_title =  'Categories';
        return view('categories.index',[
            'page_title' => $page_title
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
        $totalRecords = Categories::select('count(*) as allcount')->count();
        $totalRecordswithFilter = Categories::select('count(*) as allcount')
            ->where('category_name', 'like', '%' . $searchValue . '%')
            ->count();

        // Get records, also we have included search filter as well
        $records = Categories::orderBy($columnName, $columnSortOrder)
            ->where('category_name', 'like', '%' . $searchValue . '%')
            ->select('*')
            ->withCount('wallpaper')
            ->skip($start)
            ->take($rowperpage)
            ->get();


        $data_arr = array();
        foreach ($records as $record) {
//            $btn  = ' <a href="javascript:void(0)" onclick="editRolesPermissions('.$record->id.')" class="btn btn-warning"><i class="ti-pencil-alt"></i></a>';
            $btn = ' <a href="javascript:void(0)" data-id="'.$record->id.'" class="btn btn-warning editCategories"><i class="ti-pencil-alt"></i></a>';
            $btn .= ' <a href="javascript:void(0)" data-id="'.$record->id.'" class="btn btn-danger deleteCategories"><i class="ti-trash"></i></a>';

            $data_arr[] = array(
                "id" => $record->id,
                "category_image" => '<a class="image-popup-no-margins" href="storage/categories/'.$record->category_image.'">
                                <img class="img-fluid" alt="" src="storage/categories/'.$record->category_image.'" width="150">
                            </a>',
                "category_name" => $record->category_name,
                "category_checked_ip" => $record->category_checked_ip == 1 ? '<span class="badge badge-danger">FAKE</span>' : '<span class="badge badge-success">REAL</span>',
                "wallpaper_count" => $record->wallpaper_count,
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
            'category_name' =>'unique:categories,category_name',

        ];
        $message = [
            'category_name.unique'=>'Category đã tồn tại',

        ];
        $error = Validator::make($request->all(),$rules, $message );
        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }


        $data = new Categories();
        $data['category_name'] = $request->category_name;
        $data['category_order'] = $request->category_order;
        $data['category_view_count'] = $request->category_view_count;
        $data['category_checked_ip'] = $request->category_checked_ip ? 0 : 1 ;

        if($request->image){
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
            $data['category_image'] = $path_image;
        }else{
            $data['category_image'] = 'default.png';
        }

        $data->save();
        return response()->json(['success'=>'Thêm mới thành công']);
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
        $cate = Categories::find($id);
        $path   =   storage_path('app/public/wallpapers/').$cate->category_image;
        try {
            if(file_exists($path)){
                unlink($path);
            }
        }catch (\Exception $ex) {
            Log::error($ex->getMessage());
        }
        $cate->delete();
        return response()->json(['success'=>'Xoá thành công']);

    }

    public function import(){
        return view('categories.import');
    }
    public function postImport(Request $request){
        $file = file($request->file->getRealPath());
        $data = array_slice($file,1);
        $parts = array_chunk($data,1000);
        foreach ($parts as $index =>$part){
//            $fileName = resource_path('files/categories/'.date('y-m-d-H-i-s-').$index.'.csv');
            $fileName = resource_path('files/categoriesHasSites/'.date('y-m-d-H-i-s-').$index.'.csv');
            file_put_contents($fileName, $part);
        }
        dd(1);

        return route('categories.importToDb');
    }
    public function importToDb(){
        $path = resource_path('files/categoriesHasSites/*.csv');
        $g = glob($path);
        foreach (array_slice($g,0,1) as $file){
            $data = array_map('str_getcsv',file($file));

            foreach ($data as $row){
//                dd($row);
                CategoriesHasSites::updateOrCreate(
                    [
                        'id' => $row[0],
                    ],
                    [
                        'category_id' => $row[1],
                        'site_id' => $row[2],
                        'site_image' =>$row[3],

                    ]);
            }
//            unlink($file);
        }

//        echo '<META http-equiv="refresh" content="1;URL=' . route('categories.importToDb') . '">';
    }
}
