<?php

namespace App\Http\Controllers;


use Ejarnutowski\LaravelApiKey\Models\ApiKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ApiKeysController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $page_title =  'Api Keys';
        $action = ['create'];
        return view('apikeys.index',[
            'page_title' => $page_title,
            'action' => $action,
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
        $totalRecords = ApiKey::select('count(*) as allcount')->count();
        $totalRecordswithFilter = ApiKey::select('count(*) as allcount')
            ->where('name', 'like', '%' . $searchValue . '%')
            ->count();

        // Get records, also we have included search filter as well
        $records = ApiKey::orderBy($columnName, $columnSortOrder)
            ->where('name', 'like', '%' . $searchValue . '%')
            ->skip($start)
            ->take($rowperpage)
            ->get();



        $data_arr = array();
        foreach ($records as $key => $record) {

            $data_arr[] = array(
                "id" => $record->id,
                "name" => $record->name,
                "key" => $record->key,
                "active" => $record->active == 0 ? '<a data-id="'.$record->id.'" class="badge badge-danger changeStatus">Deactivated</a>' : '<a data-id="'.$record->id.'" class="badge badge-success changeStatus">Active</a>',
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
    public function create(Request $request)
    {
        $rules = [
            'apikey_name' => 'unique:api_keys,name',
        ];
        $message = [
            'apikey_name.unique'=>'Tên Api Key đã tồn tại',
        ];

        $error = Validator::make($request->all(),$rules, $message );

        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }
        Artisan::call('apikey:generate '.Str::slug($request->apikey_name));

        return response()->json([
            'success'=>'Thêm mới thành công'
        ]);
    }
//    public function update(Request $request){
//
//        $id = $request->id;
//        $rules = [
//            'apikey_name' =>'unique:api_keys,name,'.$id.',id',
//        ];
//        $message = [
//            'apikey_name.unique'=>'Tên Api Key đã tồn tại',
//
//        ];
//        $error = Validator::make($request->all(),$rules, $message );
//        if($error->fails()){
//            return response()->json(['errors'=> $error->errors()->all()]);
//        }
//        $data = ApiKey::find($id);
//        $data->name  = Str::slug($request->apikey_name);
//        $data->key = $request->apikey;
//        $data->save();
//        return response()->json(['success'=>'Cập nhật thành công']);
//    }
//    public function edit($id)
//    {
//        $data = ApiKey::find($id);
//        return response()->json($data);
//    }
//    public function delete($id)
//    {
//        $data = ApiKey::find($id);
//        $data->delete();
//        return response()->json(['success'=>'Xóa thành công.']);
//
//    }
    public function changeStatus($id)
    {
        $data = ApiKey::find($id);
        if($data->active == 1){
            $data->active = 0;
            $data->save();
        }elseif ($data->active == 0){
            $data->active = 1;
            $data->save();
        }
        return response()->json(['success'=>'Thành công.']);

    }
}
