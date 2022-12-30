<?php

namespace App\Http\Controllers;

use App\Categories;

use App\ListIP;
use App\Sites;
use App\Tags;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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

        $header = [
            'title' => 'Sites',
            'button' => [
//                'Create'            => ['id'=>'createMusics','style'=>'primary'],
                'Create'        => ['id'=>'createSites','style'=>'success'],
//                'Update Multiple'   => ['id'=>'update_multipleMusics','style'=>'warning'],
            ]

        ];
        return view('sites.index')->with(compact('header'));

//        $page_title =  'Sites';
//        $action = ['create'];
//        $categories = Categories::get();
//        return view('sites.index',[
//            'page_title' => $page_title,
//            'action' => $action,
////            'categories' => $categories
//        ]);
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
            ->with(['categories'=>function ($q){
                $q->withCount('music');
            }])
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
            $count_musics = 0;

            foreach ($record->categories as $category){
                $count_musics += $category->music_count ;
            }
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
                $Load_Feature .= '<p data-id="'.$record->id.'" class="badge badge-secondary change_load_feature " style="font-size: 100%">Random</p>';
            }elseif ($record->load_view_by == 1){
                $Load_Feature .= '<p data-id="'.$record->id.'" class="badge badge-info change_load_feature" style="font-size: 100%">Manual</p>';
            }elseif ($record->load_view_by == 2){
                $Load_Feature .= '<p  data-id="'.$record->id.'"class="badge badge-warning change_load_feature" style="font-size: 100%">Most View</p>';
            }elseif ($record->load_view_by == 3){
                $Load_Feature .= '<p data-id="'.$record->id.'" class="badge badge-primary change_load_feature" style="font-size: 100%">Feature Wallpaper</p>';
            }

            if ($record->load_categories == 0 ){
                $Categories .= '<p data-id="'.$record->id.'" class="badge badge-secondary change_load_categories" style="font-size: 100%">Random</p>';
            }elseif ($record->load_categories == 1){
                $Categories .= '<p data-id="'.$record->id.'" class="badge badge-info change_load_categories" style="font-size: 100%">Most View</p>';
            }elseif ($record->load_categories == 2){
                $Categories .= '<p data-id="'.$record->id.'" class="badge badge-warning change_load_categories" style="font-size: 100%">Update New</p>';
            }

            if ($record->load_wallpapers_category == 0 ){
                $Wallpaper .= '<p data-id="'.$record->id.'" class="badge badge-secondary change_load_wallpapers" style="font-size: 100%">Random</p>';
            }elseif ($record->load_wallpapers_category == 1){
                $Wallpaper .= '<p data-id="'.$record->id.'" class="badge badge-info change_load_wallpapers" style="font-size: 100%">Most Like</p>';
            }elseif ($record->load_wallpapers_category == 2){
                $Wallpaper .= '<p data-id="'.$record->id.'" class="badge badge-warning change_load_wallpapers" style="font-size: 100%">Most View</p>';
            }elseif ($record->load_wallpapers_category == 3){
                $Wallpaper .= '<p data-id="'.$record->id.'" class="badge badge-primary change_load_wallpapers" style="font-size: 100%">Update New</p>';
            }

            $sort = $Load_Feature.'<br>'.$Categories.'<br>'.$Wallpaper;
            $image = $record->site_image ? '../storage/sites/'.$record->id.'/'.$record->site_image : '../storage/default.png' ;

            $data_arr[] = array(
                "id" => $record->id,
                "site_image" => $record->site_logo_url ? '<a class="image-popup-no-margins" href="'.$record->site_logo_url.'"><img class="img-fluid" alt="'.$record->site_name.'" src="'.$record->site_logo_url.'" width="150"></a>':'<a class="image-popup-no-margins" href="'.$image.'"><img class="img-fluid" alt="'.$record->site_name.'" src="'.$image.'" width="150"></a>',
                "site_name" => '<a href="'.route('sites.view',$record->id).'" style="color:#5b626b;"><h2>'.$record->site_name.'</h2></a><a target="_blank" href="//'.$record->site_web.'"><h4>'.$record->site_web.'</h4></a>',
                "site_project" =>'<span class="badge badge-success" style="font-size: 100%">' . $record->site_project. '</span>',
                "site_ads" => $record->ad_switch == 1 ? '<a href="javascript:void(0)" data-id="'.$record->id.'" class="changeAds"><span class="badge badge-success">Active</span></a>': '<a href="javascript:void(0)" data-id="'.$record->id.'" class="changeAds"><span class="badge badge-danger">Deactivated</span></a>',

                "site_sort" => $sort,
                "categories_count" => $record->categories_count,
                "musics_count" => $count_musics,
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
        $data['site_type_ads'] = trim($request->site_type_ads);

        $data->save();

        $path    =  storage_path('app/public/sites/'.$data->id.'/');
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        if($request->image){
            $file = $request->image;
            $filename = Str::slug($data->site_web);
            $extension = $file->getClientOriginalExtension();
            $fileNameToStore = $filename.'.'.$extension;

            $img = Image::make($file);
            $img->save($path.$fileNameToStore);

            $path_image =  $fileNameToStore;
            $data->update(['site_image' => $path_image]);
        }
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
        $data->site_type_ads = $request->site_type_ads;
        if($request->image){
            $path_Remove =   storage_path('app/public/sites/'.$data->id.'/'.$data->site_image);
            try {
                if(file_exists($path_Remove)){
                    unlink($path_Remove);
                }
            }catch (\Exception $ex) {
                Log::error($ex->getMessage());
            }

            $file = $request->image;
            $filename = Str::slug($request->site_web);
            $extension = $file->getClientOriginalExtension();
            $fileNameToStore = $filename.'.'.$extension;

            $path    =  storage_path('app/public/sites/'.$data->id.'/');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $img = Image::make($file);
            $img->save($path.$fileNameToStore);
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
        $data_site['site_type_ads'] = $request->site_type_ads;
        $data_site['load_view_by'] = $site->load_view_by;
        $data_site['load_categories'] = $site->load_categories;
        $data_site['load_view_by_category'] = $site->load_view_by_category;
        $data_site['ad_switch'] = $site->ad_switch;
        $data_site['site_chplay_link'] = $site->site_chplay_link;
        $data_site['site_image'] = $site->site_image;

        $data_site->save();


        if($request->image){
            $file = $request->image;
            $filename = Str::slug($request->site_web);
            $extension = $file->getClientOriginalExtension();
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            $path    =  storage_path('app/public/sites/'.$data_site->id.'/');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $img = Image::make($file);
            $img->save($path.$fileNameToStore);
            $data_site->update(['site_image' => $fileNameToStore]);
        }

        foreach ($categories as $category){
            $tags = $category->tags()->get()->pluck('id')->toArray();
            $data_categories =  new Categories();
            $data_categories['site_id'] = $data_site->id;
            $data_categories['category_name'] = $category->category_name;
            $data_categories['category_order'] = $category->category_order;
            $data_categories['category_image'] = $category->category_image;
            $data_categories['category_view_count'] = $category->category_view_count;
            $data_categories['category_checked_ip'] = $category->category_checked_ip;
            $data_categories->save();
            $data_categories->tags()->attach($tags);
        }
        File::copyDirectory(storage_path('app/public/sites/'.$site->id),storage_path('app/public/sites/'.$data_site->id.'/'));
        return response()->json(['success'=>'Clone thành công']);
    }

    public function delete($id)
    {
        $site = Sites::find($id);
        $path = storage_path('app/public/sites/') . $site->id;
        try {
            $this->deleteDirectory($path);

        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
        }
        foreach ($site->categories()->get() as $cate){
            $cate->tags()->detach();
        }
        $site->categories()->delete();
        $site->delete();
        return response()->json(['success'=>'Xoá thành công']);
    }

    public function change_ajax($id)
    {
        $data = Sites::find($id);
        if(\request()->action == 'ads'){
            $ads = $data->ad_switch;
            switch ($ads) {
                case 0:
                    $data->ad_switch = 1;
                    $ads = '<a href="javascript:void(0)" data-id="'.$id.'" class="changeAds"><span class="badge badge-success">Active</span></a>';
                    break;
                case 1:
                    $data->ad_switch = 0;
                    $ads = '<a href="javascript:void(0)" data-id="'.$id.'" class="changeAds"><span class="badge badge-danger">Deactivated</span></a>';
                    break;
            }
            $data->save();
            return response()->json(
                [
                    'success'=>'Success.',
                    'ads'=>$ads
                ]);
        }
        if(\request()->action == 'load_feature'){
            $load_feature = $data->load_view_by;
            switch ($load_feature) {
                case 0:
                    $data->load_view_by = 1;
                    $btn = '<p data-id="'.$id.'" class="badge badge-info change_load_feature" style="font-size: 100%">Manual</p>';
                    break;
                case 1:
                    $data->load_view_by = 2;
                    $btn = '<p  data-id="'.$id.'"class="badge badge-warning change_load_feature" style="font-size: 100%">Most View</p>';
                    break;
                case 2:
                    $data->load_view_by = 3;
                    $btn = '<p data-id="'.$id.'" class="badge badge-primary change_load_feature" style="font-size: 100%">Feature Wallpaper</p>';
                    break;
                case 3:
                    $data->load_view_by = 0;
                    $btn = '<p data-id="'.$id.'" class="badge badge-secondary change_load_feature " style="font-size: 100%">Random</p>';
                    break;
            }
            $data->save();
            return response()->json(
                [
                    'success'=>'Success.',
                    'btn'=>$btn
                ]);
        }

        if(\request()->action == 'categories'){
            $load_categories = $data->load_categories;
            switch ($load_categories) {
                case 0:
                    $data->load_categories = 1;
                    $btn = '<p data-id="'.$id.'" class="badge badge-info change_load_categories" style="font-size: 100%">Most View</p>';
                    break;
                case 1:
                    $data->load_categories = 2;
                    $btn = '<p  data-id="'.$id.'"class="badge badge-warning change_load_categories" style="font-size: 100%">Update New</p>';
                    break;
                case 2:
                    $data->load_categories = 0;
                    $btn = '<p data-id="'.$id.'" class="badge badge-secondary change_load_categories" style="font-size: 100%">Random</p>';
                    break;

            }
            $data->save();
            return response()->json(
                [
                    'success'=>'Success.',
                    'btn'=>$btn
                ]);
        }

        if(\request()->action == 'wallpapers'){
            $load_wallpapers_category = $data->load_wallpapers_category;
            switch ($load_wallpapers_category) {
                case 0:
                    $data->load_wallpapers_category = 1;
                    $btn = '<p data-id="'.$id.'" class="badge badge-info change_load_wallpapers" style="font-size: 100%">Most Like</p>';
                    break;
                case 1:
                    $data->load_wallpapers_category = 2;
                    $btn = '<p  data-id="'.$id.'"class="badge badge-warning change_load_wallpapers" style="font-size: 100%">Most View</p>';
                    break;
                case 2:
                    $data->load_wallpapers_category = 3;
                    $btn = '<p data-id="'.$id.'" class="badge badge-primary change_load_wallpapers" style="font-size: 100%">Update New</p>';
                    break;
                case 3:
                    $data->load_wallpapers_category = 0;
                    $btn = '<p data-id="'.$id.'" class="badge badge-secondary change_load_wallpapers " style="font-size: 100%">Random</p>';
                    break;
            }
            $data->save();
            return response()->json(
                [
                    'success'=>'Success.',
                    'btn'=>$btn
                ]);
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
//        dd($request->all());
        Sites::find($request->id)->update($request->all());
        return response()->json(['success'=>'Cập nhật thành công']);
    }

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
        $path    =  storage_path('app/public/sites/'.$request->id.'/featureimages/');
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
            ->withCount('music')
            ->skip($start)
            ->take($rowperpage)
            ->get();
        $data_arr = array();
        foreach ($records as $record) {
            $btn = ' <a href="javascript:void(0)" data-id="'.$record->id.'" class="btn btn-warning editSiteCategory"><i class="ti-pencil-alt"></i></a>';
            $btn .= ' <a href="javascript:void(0)" data-id="'.$record->id.'" class="btn btn-danger deleteSiteCategory"><i class="ti-trash"></i></a>';


            $category_image = $record->category_image ? '../storage/sites/'.$record->site_id.'/categories/'.$record->category_image : '../storage/defaultCate.png' ;


            $data_arr[] = array(
                "id" => $record->id,
                "category_image" => '<a class="image-popup-no-margins" href="'.URL::asset($category_image).'"><img class="img-fluid" alt="" src="'.URL::asset($category_image).'" width="150"></a>',
                "category_name" => $record->category_name,
                "category_checked_ip" => $record->category_checked_ip == 1 ? '<span class="badge badge-danger">FAKE</span>' : '<span class="badge badge-success">REAL</span>',
                "music_count" => $record->music_count,
                "tags" => $record->tags,
//                "tags" => '<a href="'.route('wallpapers.index').'?search='.$record->tags.'"> <h5 class="font-size-16">'.$record->tags.'</h5></a>',
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


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse

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
     * */
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
        $records = ListIP::orderBy($columnName, $columnSortOrder)
            ->where('id_site',$request->id)
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

    public function getAIO($id){

        try {

            $site = Sites::find($id);
            $url = "https://aio.vietmmo.net/api/project-aio/".$site->site_project;
            $dataGet = $this->CURL($url);

            if($dataGet['msg'] == 'success'){
                $data = $dataGet['data'];
                $ads_type = $site->site_type_ads;

                $key = false;
                switch ($ads_type) {
                    case 1:
                        $key = array_search('CHPLAY', array_column($data['markets'], 'market_name'));
                        break;
                    case 5:
                        $key = array_search('OPPO', array_column($data['markets'], 'market_name'));
                        break;
                    case 6:
                        $key = array_search('VIVO', array_column($data['markets'], 'market_name'));
                        break;
                    case 4:
                        $key = array_search('XIAOMI', array_column($data['markets'], 'market_name'));
                        break;
                    case 7:
                        $key = array_search('HUAWEI', array_column($data['markets'], 'market_name'));
                        break;
                    case 8:
                        $key = array_search('NASH', array_column($data['markets'], 'market_name'));
                        break;
                }
//                dd($data,$key);

                if($key || $key == 0){
                    $market_get = $data['markets'][$key];

                    $ads_get = json_decode($market_get['pivot']['ads'],true);
                    $package = $market_get['pivot']['package'];
                    $link = $market_get['pivot']['app_link'];
                    $site_app_name = $market_get['pivot']['app_name_x'];

                    $ads = [
                        "ads_provider" => "ADMOB",
                        "AdMob_Publisher_ID" => $ads_get['ads_id'],
                        "AdMob_App_ID" => $ads_get['ads_id'],
                        "AdMob_Banner_Ad_Unit_ID" => $ads_get['ads_banner'],
                        "AdMob_Interstitial_Ad_Unit_ID" => $ads_get['ads_inter'],
                        "AdMob_App_Reward_Ad_Unit_ID" => $ads_get['ads_reward'],
                        "AdMob_Native_Ad_Unit_ID" => $ads_get['ads_native'],
                        "AdMob_App_Open_Ad_Unit_ID" => $ads_get['ads_open'],
                        "applovin_banner" => '',
                        "applovin_interstitial" => '',
                        "applovin_reward" => '',
                        "ironsource_id" => '',
                        "startapp_id" => $ads_get['ads_start'],
                    ];

                    $url_img_aio = 'https://aio.vietmmo.net/storage/projects/'.$data['da']['ma_da'].'/'.$data['projectname'].'/'.$data['logo'];
                    $this->downloadIMG($data['logo'],$url_img_aio,$id);
                    $update = [
                        'site_name' => $data['title_app'],
                        'site_app_name' => $site_app_name,
                        'site_app_version' => $data['buildinfo_verstr'],
                        'site_link' => $link,
                        'site_image' => $data['logo'],
                        'site_package' => $package,
                        'site_ads' => json_encode($ads)
                    ];
//                    dd($update);
                    $site->update($update);
                    return response()->json(['success'=>'Get thành công']);
                }else{
                    return response()->json(['error'=>'Kiểm tra Market trên AIO']);
                }
            }else{
                return response()->json(['error'=>'Get error']);
            }

        }catch (\Exception $exception) {
            Log::error('Message:' . $exception->getMessage() . '---getAIO--' . $exception->getLine());
        }

    }

    public function CURL($url){
        $dataArr = [
            'Content-Type'=>'application/json',
        ];
        $response = Http::withHeaders($dataArr)->get($url);
        if ($response->successful()) {
            $data = $response->json();
        }
        return $data;
    }

    function downloadIMG($filename,$link,$id){
        $tempImage = storage_path('app/public/sites/'.$id.'/'.$filename);
        copy($link, $tempImage);
        return response()->download($tempImage, $filename);
    }


}
