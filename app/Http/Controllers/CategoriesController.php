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
//    public function getIndex(Request $request)
//    {
//        $draw = $request->get('draw');
//        $start = $request->get("start");
//        $rowperpage = $request->get("length"); // total number of rows per page
//
//        $columnIndex_arr = $request->get('order');
//        $columnName_arr = $request->get('columns');
//        $order_arr = $request->get('order');
//        $search_arr = $request->get('search');
//
//        $columnIndex = $columnIndex_arr[0]['column']; // Column index
//
//        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
//        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
//        $searchValue = $search_arr['value']; // Search value
//
//
//        // Total records
//        $totalRecords = Categories::select('count(*) as allcount')->count();
//        $totalRecordswithFilter = Categories::select('count(*) as allcount')
//            ->where('category_name', 'like', '%' . $searchValue . '%')
//            ->count();
//
//        // Get records, also we have included search filter as well
//        $records = Categories::orderBy($columnName, $columnSortOrder)
//            ->where('category_name', 'like', '%' . $searchValue . '%')
//            ->select('*')
//            ->withCount('wallpaper')
//            ->skip($start)
//            ->take($rowperpage)
//            ->get();
//
//
//        $data_arr = array();
//        foreach ($records as $record) {
////            $btn  = ' <a href="javascript:void(0)" onclick="editRolesPermissions('.$record->id.')" class="btn btn-warning"><i class="ti-pencil-alt"></i></a>';
//            $btn = ' <a href="javascript:void(0)" data-id="'.$record->id.'" class="btn btn-warning editCategories"><i class="ti-pencil-alt"></i></a>';
//            $btn .= ' <a href="javascript:void(0)" data-id="'.$record->id.'" class="btn btn-danger deleteCategories"><i class="ti-trash"></i></a>';
//
//            $data_arr[] = array(
//                "id" => $record->id,
//                "category_image" => '<a class="image-popup-no-margins" href="storage/sites/'.$record->site_id.'/categories/'.$record->category_image.'">
//                                <img class="img-fluid" alt="" src="storage/sites/'.$record->site_id.'/categories/'.$record->category_image.'" width="150">
//                            </a>',
//                "category_name" => $record->category_name,
//                "category_checked_ip" => $record->category_checked_ip == 1 ? '<span class="badge badge-danger">FAKE</span>' : '<span class="badge badge-success">REAL</span>',
//                "wallpaper_count" => $record->wallpaper_count,
//                "action" => $btn,
//            );
//        }
//
//        $response = array(
//            "draw" => intval($draw),
//            "iTotalRecords" => $totalRecords,
//            "iTotalDisplayRecords" => $totalRecordswithFilter,
//            "aaData" => $data_arr,
//        );
//
//        echo json_encode($response);
//
//
//    }

    public function create(Request $request){
        $data = new Categories();
        $data['site_id'] = $request->site_id;
        $data['category_name'] = trim($request->category_name);
        $data['category_order'] = $request->category_order;
        $data['category_view_count'] = $request->category_view_count;
        $data['category_checked_ip'] = $request->category_checked_ip ? 0 : 1 ;

        if($request->image){
            $file = $request->image;
            $filename = Str::slug($request->category_name);
            $extension = $file->getClientOriginalExtension();
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            $path_image    =  storage_path('app/public/sites/'.$request->site_id.'/categories/');

            if (!file_exists($path_image)) {
                mkdir($path_image, 0777, true);
            }
            $img = Image::make($file);
            $img->resize(800, 800)->save($path_image.$fileNameToStore,60);
//            $path_image =  $request->site_id.'/'.$fileNameToStore;
            $data['category_image'] = $fileNameToStore;
        }
        $data->save();
        $data->tags()->attach($request->select_tags);
        return response()->json(['success'=>'Thêm mới thành công']);
    }
    public function edit($id){

        $categories = Categories::with('tags')->find($id);
        return response()->json([
            'categories' =>$categories,
        ]);
    }
    public function update(Request $request)
    {
        $id = $request->category_id;
        $data= Categories::find($id);
        $data->category_name = trim($request->category_name);
        $data->category_order = $request->category_order;
        $data->category_view_count = $request->category_view_count;
        $data->category_checked_ip = $request->category_checked_ip ? 0 : 1 ;

        if($request->image){
            $path_Remove =   storage_path('app/public/sites/'.$data->site_id.'/categories/').$data->category_image;
            try {
                if(file_exists($path_Remove)){
                    unlink($path_Remove);
                }
            }catch (\Exception $ex) {
                Log::error($ex->getMessage());
            }



            $file = $request->image;
            $filename = Str::slug($request->category_name);
            $extension = $file->getClientOriginalExtension();
            $fileNameToStore = $filename.'_'.time().'.'.$extension;

            $path_image    =  storage_path('app/public/sites/'.$data->site_id.'/categories/');
            if (!file_exists($path_image)) {
                mkdir($path_image, 0777, true);
            }
            $img = Image::make($file);
            $img->resize(800, 800)->save($path_image.$fileNameToStore,60);

            $data->category_image = $fileNameToStore;
        }
        $data->save();
        $data->tags()->sync($request->select_tags);

        return response()->json(['success'=>'Cập nhật thành công']);
    }
    public function delete($id)
    {
        $cate = Categories::find($id);
        $path   =   storage_path('app/public/sites/'.$cate->site_id.'/categories/').$cate->category_image;
        try {
            if(file_exists($path)){
                unlink($path);
            }
        }catch (\Exception $ex) {
            Log::error($ex->getMessage());
        }
        $cate->tags()->detach();
        $cate->delete();
        return response()->json(['success'=>'Xoá thành công']);
    }


}
