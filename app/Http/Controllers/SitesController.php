<?php

namespace App\Http\Controllers;

use App\Categories;
use App\CategoriesHasSites;
use App\ListIP;
use App\Models\SiteManage;
use App\Sites;
use App\Tags;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class SitesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $page_title =  'Sites';
//        $categories = Categories::get();
        return view('sites.index',[
            'page_title' => $page_title,
//            'categories' => $categories
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
        $totalRecords = Sites::select('count(*) as allcount')->count();
        $totalRecordswithFilter = Sites::select('count(*) as allcount')
            ->where('site_name', 'like', '%' . $searchValue . '%')
            ->count();

        // Get records, also we have included search filter as well
        $records = Sites::with('categories')
            ->where('site_name', 'like', '%' . $searchValue . '%')
            ->select('*')
            ->orderBy($columnName, $columnSortOrder)
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();
        foreach ($records as $record) {
//            $btn  = ' <a href="javascript:void(0)" onclick="editRolesPermissions('.$record->id.')" class="btn btn-warning"><i class="ti-pencil-alt"></i></a>';
            $btn = ' <a href="javascript:void(0)" data-id="'.$record->id.'" class="btn btn-warning editSites"><i class="ti-pencil-alt"></i></a>';
            $btn .= ' <a href="javascript:void(0)" data-id="'.$record->id.'" class="btn btn-danger deleteSites"><i class="ti-trash"></i></a>';

            $data_arr[] = array(
                "id" => $record->id,
                "site_image" => '<a class="image-popup-no-margins" href="storage/sites/'.$record->site_image.'"><img class="img-fluid" alt="'.$record->site_name.'" src="storage/sites/'.$record->site_image.'" width="150"></a>',
//                "site_name" => '<a href="/admin/site/view/'. $record->site_web.'" data-id="'.$record->id.'"><h5 class="font-size-16">'.$record->site_name.'</h5></a>',
                "site_name" => '<a href="'.route('sites.view',$record->id).'"><h5 class="font-size-16">'.$record->site_name.'</h5></a>',
                "site_project" =>'<span class="badge badge-success" style="font-size: 100%">' . $record->site_project. '</span>',
                "site_ads" => $record->ad_switch == 1 ? '<a href="javascript:void(0)" data-id="'.$record->id.'" class="changeAds"><span class="badge badge-success">Active</span></a>': '<a href="javascript:void(0)" data-id="'.$record->id.'" class="changeAds"><span class="badge badge-danger">Deactivated</span></a>',

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
            'site_web' =>'unique:sites,site_web',

        ];
        $message = [
            'site_web.unique'=>'Web đã tồn tại',

        ];
        $error = Validator::make($request->all(),$rules, $message );
        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }


        $data = new Sites();
        $data['site_name'] = trim($request->site_name);
        $data['site_web'] = trim($request->site_web);
        $data['site_project'] = trim($request->site_project);
        if($request->image){
            $file = $request->image;
            $filename = Str::slug($request->site_web);
            $extension = $file->getClientOriginalExtension();
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            $now = new \DateTime('now'); //Datetime
            $monthNum = $now->format('m');
            $dateObj   = DateTime::createFromFormat('!m', $monthNum);
            $monthName = $dateObj->format('F'); // Month
            $year = $now->format('Y'); // Year
            $monthYear = $monthName.$year;
            $path_image    =  storage_path('app/public/sites/'.$monthYear.'/');
            if (!file_exists($path_image)) {
                mkdir($path_image, 0777, true);
            }
            $img = Image::make($file);
            $img->save($path_image.$fileNameToStore);
            $path_image =  $monthYear.'/'.$fileNameToStore;
            $data['site_image'] = $path_image;
        }else{
            $data['site_image'] = 'default.png';
        }
        $data->save();

        return response()->json(['success'=>'Thêm mới thành công']);
    }
    public function edit($id){

        $site = Sites::with('categories')->find($id);
        return response()->json([
            'site' =>$site,
        ]);
    }
    public function update(Request $request)
    {
        $id = $request->id;
        $rules = [
            'site_web' =>'unique:sites,site_web,'.$id.',id',

        ];
        $message = [
            'site_web.unique'=>'Web đã tồn tại',
        ];
        $error = Validator::make($request->all(),$rules, $message );
        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }
        $data= Sites::find($id);
        $data->site_web = $request->site_web;
        $data->site_name = $request->site_name;
        $data->site_project = $request->site_project;


        if($request->image){
            if ($data->site_image != 'default.png'){
                $path_Remove =   storage_path('app/public/sites/').$data->site_image;
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
            $path_image    =  storage_path('app/public/sites/'.$monthYear.'/');
            if (!file_exists($path_image)) {
                mkdir($path_image, 0777, true);
            }
            $img = Image::make($file);
            $img->save($path_image.$fileNameToStore);
            $path_image =  $monthYear.'/'.$fileNameToStore;
            $data->site_image = $path_image;
        }
        $data->save();
        return response()->json(['success'=>'Cập nhật thành công']);
    }
    public function delete($id)
    {
        $site = Sites::find($id);
        if ($site->site_image != 'default.png') {
            $path = storage_path('app/public/sites/') . $site->site_image;
            try {
                if (file_exists($path)) {
                    unlink($path);
                }
            } catch (\Exception $ex) {
                Log::error($ex->getMessage());
            }
        }
        $site->delete();
        return response()->json(['success'=>'Xoá thành công']);
    }
    public function changeAds($id)
    {
        $data = Sites::find($id);
        if($data->ad_switch == 1){
            $data->ad_switch = 0;
            $data->save();
            return response()->json(['success'=>'Tắt Ads.']);
        }elseif ($data->ad_switch == 0){
            $data->ad_switch = 1;
            $data->save();
            return response()->json(['success'=>'Kích hoạt ADs.']);
        }

    }

    public function viewSite($id){
        $page_title =  'Site';
        $site = Sites::find($id);
        $tags = Tags::all();
        return view('sites.site.index',[
            'page_title' => $page_title,
            'site' => $site,
            'tags' => $tags,
        ]);

    }

    public function update_site(Request $request)
    {
        Sites::find($request->id)->update($request->all());
        return response()->json(['success'=>'Cập nhật thành công']);
    }
//    public function update_load_view_by(Request $request)
//    {
//        Sites::find($request->id)->update($request->all());
//        return response()->json(['success'=>'Cập nhật thành công']);
//    }
    public function update_ads(Request $request)
    {

        $site = Sites::find($request->id);
        $data = [
            "ads_provider" => $request->ads_provider,
            "AdMob_Publisher_ID" =>$request->AdMob_Publisher_ID,
            "AdMob_App_ID" => $request->AdMob_App_ID,
            "AdMob_Banner_Ad_Unit_ID" => $request->AdMob_Banner_Ad_Unit_ID,
            "AdMob_Interstitial_Ad_Unit_ID" => $request->AdMob_Interstitial_Ad_Unit_ID,
            "AdMob_App_Reward_Ad_Unit_ID" => $request->AdMob_App_Reward_Ad_Unit_ID,
            "AdMob_Native_Ad_Unit_ID" => $request->AdMob_Native_Ad_Unit_ID,
            "AdMob_App_Open_Ad_Unit_ID" => $request->AdMob_App_Open_Ad_Unit_ID,
            "applovin_banner" => $request->applovin_banner,
            "applovin_interstitial" => $request->applovin_interstitial,
            "applovin_reward" => $request->applovin_reward,
            "ironsource_id" => $request->ironsource_id,
            "startapp_id" => $request->startapp_id,
        ];
        $site->update(['site_ads'=>json_encode($data)]);
        return response()->json(['success'=>'Cập nhật thành công']);
    }

    public function update_FeatureImages(Request $request)
    {
        $site = Sites::find($request->id);

        $path    =  storage_path('app/public/featureimages/'.Str::slug($site->site_web).'/');
        $this->deleteDirectory($path);
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $data = [];
        foreach ($request->file as $file){
            $filenameWithExt=$file->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $nameImage =  preg_replace('/[^A-Za-z0-9\-\']/', '_', $filename);
            $extension = $file->getClientOriginalExtension();
            $fileNameToStore = $nameImage.'_'.time().'.'.$extension;
            $img = Image::make($file);
            $img->save($path.$fileNameToStore);
            $data[] = $fileNameToStore;
        }

        $site->update(['site_feature_images'=>json_encode($data)]);
        return response()->json(['success'=>'Cập nhật thành công']);
    }


    public function getIndexCategories(Request $request){
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

        $totalRecords = Sites::findOrFail($request->id)
            ->categories()
            ->select('count(*) as allcount')->count();

        $totalRecordswithFilter = Sites::findOrFail($request->id)
            ->categories()
            ->where('category_name', 'like', '%' . $searchValue . '%')
            ->select('count(*) as allcount')->count();

        $records = Sites::findOrFail($request->id)
            ->categories()
            ->where('category_name', 'like', '%' . $searchValue . '%')
            ->orderBy($columnName, $columnSortOrder)
            ->select('*')
            ->with('tags')
            ->withCount('wallpaper')
            ->skip($start)
            ->take($rowperpage)
            ->get();
        $data_arr = array();
        foreach ($records as $record) {
            $btn = ' <a href="javascript:void(0)" data-id="'.$record->id.'" class="btn btn-warning editSiteCategory"><i class="ti-pencil-alt"></i></a>';
            $btn .= ' <a href="javascript:void(0)" data-id="'.$record->id.'" class="btn btn-danger deleteSiteCategory"><i class="ti-trash"></i></a>';
            $data_arr[] = array(
                "id" => $record->id,
                "category_image" => '<a class="image-popup-no-margins" href="'.URL::asset('storage/categories').'/'.$record->category_image.'"><img class="img-fluid" alt="" src="'.URL::asset('storage/categories/').'/'. $record->category_image.'" width="150"></a>',
                "category_name" => $record->category_name,
                "category_checked_ip" => $record->category_checked_ip == 1 ? '<span class="badge badge-danger">FAKE</span>' : '<span class="badge badge-success">REAL</span>',
                "wallpaper_count" => $record->wallpaper_count,
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

    public function update_category(Request $request)
    {
        $category = CategoriesHasSites::
        where('category_id',$request->category_id)
            ->where('site_id',$request->site_id)
            ->first();

        if($request->image){
            if($category->site_image != null){
                $path_Remove =   storage_path('app/public/categories/').$category->site_image;
                if(file_exists($path_Remove)){
                    unlink($path_Remove);
                }
            }
            $file = $request->image;
            $filename = Str::slug($request->site_id).'_'.Str::slug($request->category_id);
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
            $category->update(['site_image'=>$path_image]);
        }


        return response()->json(['success'=>'Cập nhật thành công']);
    }

    public function getIndexListIPs(Request $request){
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

        $totalRecords = ListIP::where('id_site',$request->id)
            ->select('count(*) as allcount')->count();

        $totalRecordswithFilter = ListIP::where('id_site',$request->id)
            ->where('ip_address', 'like', '%' . $searchValue . '%')
            ->select('count(*) as allcount')->count();


        // Get records, also we have included search filter as well
        $records = ListIP::where('id_site',$request->id)
            ->where('ip_address', 'like', '%' . $searchValue . '%')
            ->select('*')
            ->skip($start)
            ->take($rowperpage)
            ->get();


        $data_arr = array();
        foreach ($records as $record) {
            $data_arr[] = array(
                "id" => $record->id,
                "ip_address" => $record->ip_address,
                "count" => $record->count,
                "updated_at" => $record->updated_at,
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

    function deleteDirectory($dir) {
        if (!file_exists($dir)) {
            return true;
        }

        if (!is_dir($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (!$this->deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }

        return rmdir($dir);
    }
}
