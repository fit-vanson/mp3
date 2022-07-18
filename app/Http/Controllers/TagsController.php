<?php

namespace App\Http\Controllers;

use App\Tags;
use Illuminate\Http\Request;



class TagsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $page_title =  'Tags';
        return view('tags.index',[
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
        $totalRecords = Tags::select('count(*) as allcount')->count();
        $totalRecordswithFilter = Tags::select('count(*) as allcount')
            ->where('tag_name', 'like', '%' . $searchValue . '%')
            ->count();

        // Get records, also we have included search filter as well
        $records = Tags::orderBy($columnName, $columnSortOrder)
            ->where('tag_name', 'like', '%' . $searchValue . '%')
            ->select('*')
            ->withCount('wallpaper')
            ->skip($start)
            ->take($rowperpage)
            ->get();


        $data_arr = array();
        foreach ($records as $record) {
            $btn = ' <a href="javascript:void(0)" data-id="'.$record->id.'" class="btn btn-danger deleteTags"><i class="ti-trash"></i></a>';

            $data_arr[] = array(
                "id" => $record->id,
                "tag_name" => '<a href="'.route('wallpapers.index').'?search='.$record->tag_name.'"> <h5 class="font-size-16">'.$record->tag_name.'</h5></a>',
//                $record->tag_name,
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
        $data = Tags::updateOrCreate($request->all());
        return response()->json(['success'=>'Thành công','tag'=>$data]);
    }
//    public function edit($id){
//
//        $data = Tags::find($id);
//        return response()->json([
//            'tag' =>$data,
//        ]);
//    }
//    public function update(Request $request)
//    {
//        $id = $request->id;
//        $rules = [
//            'category_name' =>'unique:categories,category_name,'.$id.',id',
//
//        ];
//        $message = [
//            'category_name.unique'=>'Category đã tồn tại',
//
//        ];
//        $error = Validator::make($request->all(),$rules, $message );
//        if($error->fails()){
//            return response()->json(['errors'=> $error->errors()->all()]);
//        }
//        $data= Categories::find($id);
//        $data->category_name = $request->category_name;
//        $data->category_order = $request->category_order;
//        $data->category_view_count = $request->category_view_count;
//        $data->category_checked_ip = $request->category_checked_ip ? 0 : 1 ;
//
//        if($request->image){
//            if ($data->category_image != 'default.png'){
//                $path_Remove =   storage_path('app/public/categories/').$data->category_image;
//                if(file_exists($path_Remove)){
//                    unlink($path_Remove);
//                }
//            }
//
//            $file = $request->image;
//            $filename = Str::slug($request->category_name);
//            $extension = $file->getClientOriginalExtension();
//            $fileNameToStore = $filename.'_'.time().'.'.$extension;
//            $now = new \DateTime('now'); //Datetime
//            $monthNum = $now->format('m');
//            $dateObj   = DateTime::createFromFormat('!m', $monthNum);
//            $monthName = $dateObj->format('F'); // Month
//            $year = $now->format('Y'); // Year
//            $monthYear = $monthName.$year;
//            $path_image    =  storage_path('app/public/categories/'.$monthYear.'/');
//            if (!file_exists($path_image)) {
//                mkdir($path_image, 0777, true);
//            }
//            $img = Image::make($file);
//            $img->save($path_image.$fileNameToStore);
//            $path_image =  $monthYear.'/'.$fileNameToStore;
//            $data->category_image = $path_image;
//        }
//
//        $data->save();
//
//
//
//        return response()->json(['success'=>'Cập nhật thành công']);
//    }
    public function delete($id)
    {
        $tag = Tags::find($id);
        $tag->wallpaper()->detach();
        $tag->categories()->detach();
        $tag->delete();
        return response()->json(['success'=>'Xoá thành công']);

    }

}
