<?php

namespace App\Http\Controllers;

use App\BlockIPs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BlockIPsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $page_title =  'Block IPs';
        return view('blockips.index',[
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
        $totalRecords = BlockIPs::select('count(*) as allcount')->count();
        $totalRecordswithFilter = BlockIPs::select('count(*) as allcount')
            ->where('ip_address', 'like', '%' . $searchValue . '%')
            ->count();


        // Get records, also we have included search filter as well
        $records = BlockIPs::orderBy($columnName, $columnSortOrder)
            ->where('ip_address', 'like', '%' . $searchValue . '%')
            ->select('*')
            ->skip($start)
            ->take($rowperpage)
            ->get();
//        dd($records->wallpaper_count);
        $data_arr = array();
        foreach ($records as $key => $record) {
            $btn = ' <a href="javascript:void(0)" data-id="'.$record->id.'" class="btn btn-warning editBlockIPs"><i class="ti-pencil-alt"></i></a>';
            $btn .= ' <a href="javascript:void(0)" data-id="'.$record->id.'" class="btn btn-danger deleteBlockIPs"><i class="ti-trash"></i></a>';

            $data_arr[] = array(
                "id" => $record->id,
                "status" => $record->status != 1 ? '<span  class="badge badge-danger">Deactivated</span>' : '<span  class="badge badge-success">Activated</span>',
                "ip_address" => $record->ip_address,
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
    public function create(Request $request)
    {
        $rules = [
            'ip_address' => 'unique:block_i_ps,ip_address',
        ];
        $message = [
            'ip_address.unique'=>'Đã tồn tại',
        ];

        $error = Validator::make($request->all(),$rules, $message );

        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }
        $data = new BlockIPs();
        $data['ip_address'] = $request->block_ip_address;
        $data['status'] = $request->block_ip_status ? 1 : 0;
        $data->save();

        return response()->json([
            'success'=>'Thêm mới thành công',
        ]);
    }
    public function update(Request $request){

        $id = $request->id;
        $rules = [
            'ip_address' =>'unique:block_i_ps,ip_address,'.$id.',id',

        ];
        $message = [
            'ip_address.unique'=>'Đã tồn tại',
        ];
        $error = Validator::make($request->all(),$rules, $message );
        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }
        $data = BlockIPs::find($id);
        $data->ip_address = $request->block_ip_address;
        $data->status = $request->block_ip_status ? 1 : 0;
        $data->save();
        return response()->json(['success'=>'Cập nhật thành công']);
    }
    public function edit($id)
    {
        $data = BlockIPs::find($id);
        return response()->json($data);
    }
    public function delete($id)
    {
        $block_ip = BlockIPs::find($id);
        $block_ip->delete();
        return response()->json(['success'=>'Xóa thành công.']);

    }
}
