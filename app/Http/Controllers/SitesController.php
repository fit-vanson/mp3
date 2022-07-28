<?php

namespace App\Http\Controllers;

use App\Categories;
use App\CategoriesHasSites;
use App\ListIP;
use App\Sites;
use App\Tags;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\File;

class SitesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('directlink');
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
            ->orwhere('site_web', 'like', '%' . $searchValue . '%')
            ->orwhere('site_project', 'like', '%' . $searchValue . '%')
            ->count();

        // Get records, also we have included search filter as well
        $records = Sites::orderBy($columnName, $columnSortOrder)
            ->with('categories')
            ->where('site_name', 'like', '%' . $searchValue . '%')
            ->orwhere('site_web', 'like', '%' . $searchValue . '%')
            ->orwhere('site_project', 'like', '%' . $searchValue . '%')
            ->select('*')
            ->withCount('categories')
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();
        foreach ($records as $record) {

//            dd($record);
//            $btn  = ' <a href="javascript:void(0)" onclick="editRolesPermissions('.$record->id.')" class="btn btn-warning"><i class="ti-pencil-alt"></i></a>';
            $btn = ' <a href="javascript:void(0)" data-id="'.$record->id.'" class="btn btn-warning editSites"><i class="ti-pencil-alt"></i></a>';
            $btn .= ' <a href="javascript:void(0)" data-id="'.$record->id.'" class="btn btn-danger deleteSites"><i class="ti-trash"></i></a>';
            $btn .= '<br><br> <a href="javascript:void(0)" data-id="'.$record->id.'" class="btn btn-secondary copySites"><i class="ti-layers"></i></a>';
//            $btn .= ' <a href="'.route('sites.view',$record->id).'" class="btn btn-info"><i class="ti-info-alt"></i></a>';


            $Load_Feature = 'Load Feature: ';
            $Categories = 'Categories: ';
            $Wallpaper = 'Wallpaper: ';

            if ($record->load_view_by == 0 ){
                $Load_Feature .= '<p class="badge badge-secondary" style="font-size: 100%">Random</p>';
            }elseif ($record->load_view_by == 1){
                $Load_Feature .= '<p class="badge badge-info" style="font-size: 100%">Manual</p>';
            }elseif ($record->load_view_by == 2){
                $Load_Feature .= '<p class="badge badge-warning" style="font-size: 100%">Most View</p>';
            }elseif ($record->load_view_by == 3){
                $Load_Feature .= '<p class="badge badge-primary" style="font-size: 100%">Feature Wallpaper</p>';
            }

            if ($record->load_categories == 0 ){
                $Categories .= '<p class="badge badge-secondary" style="font-size: 100%">Random</p>';
            }elseif ($record->load_categories == 1){
                $Categories .= '<p class="badge badge-info" style="font-size: 100%">Manual</p>';
            }elseif ($record->load_categories == 2){
                $Categories .= '<p class="badge badge-warning" style="font-size: 100%">Update New</p>';
            }

            if ($record->load_wallpapers_category == 0 ){
                $Wallpaper .= '<p class="badge badge-secondary" style="font-size: 100%">Random</p>';
            }elseif ($record->load_wallpapers_category == 1){
                $Wallpaper .= '<p class="badge badge-info" style="font-size: 100%">Manual</p>';
            }elseif ($record->load_wallpapers_category == 2){
                $Wallpaper .= '<p class="badge badge-warning" style="font-size: 100%">Most View</p>';
            }

            $sort = $Load_Feature.'<br>'.$Categories.'<br>'.$Wallpaper;

            $data_arr[] = array(
                "id" => $record->id,
                "site_image" => '<a class="image-popup-no-margins" href="../storage/sites/'.$record->site_image.'"><img class="img-fluid" alt="'.$record->site_name.'" src="../storage/sites/'.$record->site_image.'" width="150"></a>',
                "site_name" => '<a href="'.route('sites.view',$record->id).'" style="color:#5b626b;"><h2>'.$record->site_name.'</h2></a><a target="_blank" href="//'.$record->site_web.'"><h4>'.$record->site_web.'</h4></a>',
                "site_project" =>'<span class="badge badge-success" style="font-size: 100%">' . $record->site_project. '</span>',
                "site_ads" => $record->ad_switch == 1 ? '<a href="javascript:void(0)" data-id="'.$record->id.'" class="changeAds"><span class="badge badge-success">Active</span></a>': '<a href="javascript:void(0)" data-id="'.$record->id.'" class="changeAds"><span class="badge badge-danger">Deactivated</span></a>',

                "site_sort" => $sort,
                "categories_count" => $record->categories_count,
                "wallpapers_count" => $record->categories_count,
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
            $path_image    =  storage_path('app/public/sites/');
            if (!file_exists($path_image)) {
                mkdir($path_image, 0777, true);
            }
            $img = Image::make($file);
            $img->save($path_image.$fileNameToStore);
            $path_image =  $fileNameToStore;
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
            $filename = Str::slug($request->site_web);
            $extension = $file->getClientOriginalExtension();
            $fileNameToStore = $filename.'_'.time().'.'.$extension;

            $path_image    =  storage_path('app/public/sites/');
            if (!file_exists($path_image)) {
                mkdir($path_image, 0777, true);
            }
            $img = Image::make($file);
            $img->save($path_image.$fileNameToStore);
            $path_image =  $fileNameToStore;
            $data->site_image = $path_image;
        }
        $data->save();
        return response()->json(['success'=>'Cập nhật thành công']);
    }

    public function clone(Request $request)
    {

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

        $site = Sites::with('categories')->find($request->id);
        $categories = $site->categories()->get();
        $data_site = new Sites();
        $data_site['site_name'] = trim($request->site_name);
        $data_site['site_web'] = trim($request->site_web);
        $data_site['site_project'] = trim($request->site_project);
        $data_site['site_feature_images'] = $site->site_feature_images;
        $data_site['site_header_title'] = $site->site_header_title;
        $data_site['site_header_content'] = $site->site_header_content;
        $data_site['site_body_title'] = $site->site_body_title;
        $data_site['site_body_content'] = $site->site_body_content;
        $data_site['site_footer_title'] = $site->site_footer_title;
        $data_site['site_footer_content'] = $site->site_footer_content;
        $data_site['site_policy'] = $site->site_policy;
        $data_site['site_ads'] = $site->site_ads;
        $data_site['site_direct_link'] = $site->site_direct_link;
        $data_site['site_view_page'] = 0;
        $data_site['load_view_by'] = $site->load_view_by;
        $data_site['load_categories'] = $site->load_categories;
        $data_site['load_wallpapers_category'] = $site->load_wallpapers_category;
        $data_site['ad_switch'] = $site->ad_switch;
        $data_site['site_chplay_link'] = $site->site_chplay_link;

        if($request->image){
            $file = $request->image;
            $filename = Str::slug($request->site_web);
            $extension = $file->getClientOriginalExtension();
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            $path_image    =  storage_path('app/public/sites/');
            if (!file_exists($path_image)) {
                mkdir($path_image, 0777, true);
            }
            $img = Image::make($file);
            $img->save($path_image.$fileNameToStore);
            $path_image =  $fileNameToStore;
            $data['site_image'] = $path_image;
        }else{
            $data_site['site_image'] = 'default.png';
        }
        $data_site->save();

        foreach ($categories as $category){
            $tags = $category->tags()->get()->pluck('id')->toArray();

            if ($category->category_image != 'default.png'){
                $image_path_name = explode('/',$category->category_image);
                $image_path = str_replace($image_path_name[0],$data_site->id,$category->category_image);
            }else{
                $image_path = 'default.png';
            }


            $data_categories =  new Categories();
            $data_categories['site_id'] = $data_site->id;
            $data_categories['category_name'] = $category->category_name;
            $data_categories['category_order'] = $category->category_order;
            $data_categories['category_image'] = $image_path;
            $data_categories['category_view_count'] = $category->category_view_count;
            $data_categories['category_checked_ip'] = $category->category_checked_ip;
            $data_categories->save();
            $data_categories->tags()->attach($tags);
        }


        $path_featureimages   =  storage_path('app/public/featureimages/'.$data_site->id.'/');
        $path_categories   =  storage_path('app/public/categories/'.$data_site->id.'/');

        File::copyDirectory(storage_path('app/public/categories/'.$site->id),$path_categories);
        File::copyDirectory(storage_path('app/public/featureimages/'.$site->id),$path_featureimages);

        return response()->json(['success'=>'Clone thành công']);
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
        $site->categories()->delete();
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
//        $cates = Sites::findOrFail($id)
//            ->categories()
//            ->select('*')
//            ->with('tags')
//            ->get();
//        $path_featureimages   =  storage_path('app/public/featureimages/'.Str::slug($site->site_web).'/');
//        $path   =  storage_path('app/public/featureimages/'.$id.'/');
//        if(file_exists($path_featureimages)){
//            rename($path_featureimages, $path);
//        }
//        if (!file_exists($path_image)) {
//            mkdir($path_image, 0777, true);
//        }
//
//        foreach ($cates as $cate){
//
//            if ($cate->category_image != 'default.png'){
//
//                $image_path = $cate->category_image;
//                $name = explode('/',$image_path);
//                $change_path = str_replace($name[0],$cate->site_id,$image_path);
//
//
//
//                echo $image_path;
//                $path_move =   public_path('storage/categories/').$cate->category_image;
//
//                $path =   public_path('storage/categories/').$change_path;
//
//
//                if(file_exists($path_move) && $path_move <> $path){
//
//                    File::copy($path_move, $path);
//                }
//
//                Categories::updateOrCreate(
//                    [
//                        'id'=> $cate->id,
//                        'site_id'=>$cate->site_id
//                    ],
//                    [
//                        'category_image'=>$change_path
//                    ]
//                );
//
//
//            }
//
//
//        }
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
        $path    =  storage_path('app/public/featureimages/'.$request->id.'/');
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
                "category_image" => '<a class="image-popup-no-margins" href="'.URL::asset('../storage/categories').'/'.$record->category_image.'"><img class="img-fluid" alt="" src="'.URL::asset('../storage/categories/').'/'. $record->category_image.'" width="150"></a>',
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

    public function import(){
        return view('sites.import');
    }

    public function postImport(Request $request){
        $file = file($request->file->getRealPath());
        $data = array_slice($file,1);
        $parts = array_chunk($data,1000);
        foreach ($parts as $index =>$part){
            $fileName = resource_path('files/sites/'.date('y-m-d-H-i-s-').$index.'.json');
            file_put_contents($fileName, $part);
        }
        return route('sites.importToDb');
    }
    public function importToDb(){
        $path = resource_path('files/sites/*.json');
        $g = glob($path);
        foreach (array_slice($g,0,1) as $file){
            $data = json_decode(file_get_contents($file),true);
            $dataArray = [];
            foreach ($data as $row){
                $dataArray[] =
                    [
                        'id' => $row['id'],
                        'ad_switch' => $row['ad_switch'],
                        'load_view_by' => $row['load_view_by'],
                        'site_name' =>$row['name_site'],
                        'site_web' =>$row['site_name'],
                        'site_image' =>$row['header_image'],
                        'site_feature_images' => null,
                        'site_header_title' =>$row['header_title'],
                        'site_header_content' =>$row['header_content'],
                        'site_body_title' =>$row['body_title'],
                        'site_body_content' =>$row['body_content'],
                        'site_footer_title' =>$row['footer_title'],
                        'site_footer_content' =>$row['footer_content'],
                        'site_policy' =>$row['policy'],
                        'site_ads' => $row['ads'],
                        'site_project' => $row['id'],
                        'site_chplay_link' => $row['directlink'],
                        'site_view_page' =>$row['view_page'],
                    ];
            }
            Sites::insert($dataArray);

        }

//        echo '<META http-equiv="refresh" content="1;URL=' . route('categories.importToDb') . '">';
    }

    public function directlink(){
        $domain=$_SERVER['SERVER_NAME'];
        $site = Sites::where('site_web',$domain)->first();
        if($site){
            $directlink = $site->site_direct_link;
            if ($directlink){
                $site->site_view_page = $site->site_view_page+1;
                $site->save();
                return redirect($directlink);
            }else{
                return redirect('/');
            }
        }
        else{
            return redirect('/');
        }
    }


}
