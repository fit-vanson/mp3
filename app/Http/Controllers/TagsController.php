<?php

namespace App\Http\Controllers;

use App\Tags;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use function GuzzleHttp\Promise\all;


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
            ->withCount('wallpaper','ringtone')
            ->skip($start)
            ->take($rowperpage)
            ->get();


        $data_arr = array();
        foreach ($records as $record) {
            $btn = ' <a href="javascript:void(0)" data-id="'.$record->id.'" class="btn btn-warning editTags"><i class="ti-pencil-alt"></i></a>';
            $btn .= ' <a href="javascript:void(0)" data-name="'.$record->tag_name.'"  data-id="'.$record->id.'" class="btn btn-danger deleteTags"><i class="ti-trash"></i></a>';

            $data_arr[] = array(
                "id" => $record->id,
                "tag_name" => '<h5 class="font-size-16">'.$record->tag_name.'</h5>',
                "wallpaper_count" => '<a href="'.route('wallpapers.index').'?view=grid&search='.$record->tag_name.'"> <h5 class="font-size-16">'.$record->wallpaper_count.'</h5></a>',
                "ringtone_count" => '<a href="'.route('ringtones.index').'?search='.$record->tag_name.'"> <h5 class="font-size-16">'.$record->ringtone_count.'</h5></a>',
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
            'tag_name' =>'unique:tags,tag_name',
        ];
        $message = [
            'tag_name.unique'=>'Tên đã tồn tại',

        ];
        $error = Validator::make($request->all(),$rules, $message );
        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }

        $data = Tags::updateOrCreate($request->all());
        return response()->json(['success'=>'Thành công','tag'=>$data]);
    }
    public function edit($id){

        $data = Tags::find($id);
        return response()->json([
            'tag' =>$data,
        ]);
    }
    public function update(Request $request)
    {
        $id = $request->id;
        $rules = [
            'tag_name' =>'unique:tags,tag_name,'.$id.',id',

        ];
        $message = [
            'tag_name.unique'=>'Tên đã tồn tại',

        ];
        $error = Validator::make($request->all(),$rules, $message );
        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }
        $data= Tags::find($id);
        $data->tag_name = $request->tag_name;
        $data->save();
        return response()->json(['success'=>'Cập nhật thành công']);
    }

    public function delete(Request $request)
    {
        $tag = Tags::find($request->id);
        if(isset($request->wallpaper_tags_change)){

            $walpapers = $tag->wallpaper()->get();

            if ($walpapers->isNotEmpty()) {
                $changeTagsWallpaper = Tags::whereIN('id',$request->wallpaper_tags_change)->get();
                foreach ($changeTagsWallpaper as $changeTagWallpaper){
                    $changeTagWallpaper->wallpaper()->sync($walpapers->pluck('id')->toArray(),false);
                }
            }
        }
        if(isset($request->ringtone_tags_change)){
            $ringtones = $tag->wallpaper()->get();
            if ($ringtones->isNotEmpty()) {
                $changeTagsRingtone = Tags::whereIN('id',$request->ringtone_tags_change)->get();
                foreach ($changeTagsRingtone as $changeTagRingtone){
                    $changeTagRingtone->wallpaper()->sync($ringtones->pluck('id')->toArray(),false);
                }
            }
        }

        $tag->wallpaper()->detach();
        $tag->ringtone()->detach();
        $tag->categories()->detach();
        $tag->delete();
        return response()->json(['success'=>'Xoá thành công']);
    }

    public function changeTag($id)
    {
        $tags = Tags::select('id','tag_name')->where('id','<>',$id)->get();
        return response()->json([
            'tags' =>$tags,
        ]);
    }

}
