<?php

namespace App\Http\Controllers;

use App\GoogleAds;
use Illuminate\Http\Request;

class GoogleAdsController extends Controller
{
    public function index()
    {
        $header = [
            'title' => 'Google Ads Service',
            'button' => [
//                'Create'            => ['id'=>'createGoogle_Ads','style'=>'primary'],
//                'Play List'         => ['id'=>'videoList','style'=>'primary'],
            ]

        ];
        return view('google_ads.index')->with(compact('header'));
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
        $totalRecords = GoogleAds::select('count(*) as allcount') ->count();
        $totalRecordswithFilter = GoogleAds::select('count(*) as allcount')
            ->where(function ($query) use ($searchValue) {
                $query
                    ->where('name', 'like', '%' . $searchValue . '%')
                    ->orwhere('value', 'like', '%' .$searchValue . '%');
            })
            ->count();

        // Get records, also we have included search filter as well
        $records = GoogleAds::orderBy($columnName, $columnSortOrder)
            ->where(function ($query) use ($searchValue) {
                $query
                    ->where('name', 'like', '%' . $searchValue . '%')
                    ->orwhere('value', 'like', '%' .$searchValue . '%');
            })
            ->skip($start)
            ->take($rowperpage)
            ->get();
        $data_arr = array();
        foreach ($records as $record) {

            $data_arr[] = array(
                "id" => $record->id,
                "name" => $record->name,
                "value" => '<a href="#" data-pk="'.$record->id.'" class="editable" data-url="">'.$record->value.'</a>',
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

    public function update(Request $request)
    {
        $id = $request->id;
        $data= GoogleAds::find($id);
        $data->value = trim($request->value);
        $data->save();
        return response()->json(['success'=>'Cập nhật thành công']);
    }

    public function show_direct(Request $request)
    {
        $value = $request->path();
        $value_url = GoogleAds::get()->pluck('value','name')->toArray();

        if($value == $value_url['url_direct']){
            if(checkBlockIp()){
                dd('chuyển qua url block');

            }else{
                $detect = new \Detection\MobileDetect;
                if($detect->isMobile()){
                    if($detect->isAndroidOS()){
                        dd('isAndroidOS');
                    }
                    if($detect->isIphone()){
                        dd('isIphone');
                    }
                }
                if($detect->isTablet()){
                    if($detect->isiOS()){
                        dd('Máy tính bảng OS');
                    }else{
                        dd('Máy tính bảng Android');
                    }
                }else{
                    dd('windows');
                }
            }
        }
    }
}
