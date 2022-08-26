<?php

use App\Http\Controllers\ApiKeysController;
use App\Http\Controllers\BlockIPsController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MusicsController;
use App\Http\Controllers\RingtonesController;
use App\Http\Controllers\RolesPermissionsController;
use App\Http\Controllers\SitesController;
use App\Http\Controllers\TagsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\WallpapersController;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes();

Route::get('/clear', function () {
    try {

        echo Artisan::call('cache:clear');
        echo Artisan::call('config:clear');
        echo Artisan::call('config:cache');
        echo Artisan::call('view:clear');
        echo Artisan::call('optimize');
        echo Artisan::call('route:cache');
        echo Artisan::call('storage:link');
    }catch (\Exception $e){
        Log::error($e->getMessage());
    }
});
Route::get('/link',function (){
    Artisan::call('storage:link');
    echo 1;
});


Route::get('/stream/{id}',[MusicsController::class,'streamID'])->name('musics.stream');
Route::get('/getLinkUrl/{id}',[MusicsController::class,'getLinkUrl'])->name('musics.getLinkUrl');

Route::get('/phpinfo',function (){
    echo phpinfo();
});

Route::get('/', [HomeController::class, 'show'])->name('show');

Route::get('/policy', [HomeController::class, 'policy'])->name('policy');

Route::get('/directlink', [SitesController::class, 'directlink'])->name('directlink');
Route::get('/cloudflare/{id}', [HomeController::class, 'cloudflare'])->name('cloudflare');


Route::group(['prefix'=>env('ADMIN_PAGE','admin')], function (){
//    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/load_data', [HomeController::class, 'load_data'])->name('home.load_data');
    Route::get('/', [HomeController::class, 'index'])->name('home.index');


    Route::group(['prefix'=>'users'], function (){
        Route::get('/',[UsersController::class,'index'])->name('users.index');
        Route::post('/getIndex',[UsersController::class,'getIndex'])->name('users.getIndex');
        Route::post('/create',[UsersController::class,'create'])->name('users.create');
        Route::get('/edit/{id}',[UsersController::class,'edit'])->name('users.edit');
        Route::post('/update',[UsersController::class,'update'])->name('users.update');
        Route::get('/delete/{id}',[UsersController::class,'delete'])->name('users.delete');
    });

    Route::group(['prefix'=>'roles-permissions'], function (){
        Route::get('/',[RolesPermissionsController::class,'index'])->name('roles_permissions.index');
        Route::post('/getIndex',[RolesPermissionsController::class,'getIndex'])->name('roles_permissions.getIndex');
        Route::post('/create',[RolesPermissionsController::class,'create'])->name('roles_permissions.create');
        Route::get('/edit/{id}',[RolesPermissionsController::class,'edit'])->name('roles_permissions.edit');
        Route::post('/update',[RolesPermissionsController::class,'update'])->name('roles_permissions.update');
        Route::get('/delete/{id}',[RolesPermissionsController::class,'delete'])->name('roles_permissions.delete');
    });

    Route::group(['prefix'=>'categories'], function (){
        Route::get('/',[CategoriesController::class,'index'])->name('categories.index');
        Route::post('/getIndex',[CategoriesController::class,'getIndex'])->name('categories.getIndex');
        Route::post('/create',[CategoriesController::class,'create'])->name('categories.create');
        Route::get('/edit/{id}',[CategoriesController::class,'edit'])->name('categories.edit');
        Route::post('/update',[CategoriesController::class,'update'])->name('categories.update');
        Route::get('/delete/{id}',[CategoriesController::class,'delete'])->name('categories.delete');
        Route::get('/import', [CategoriesController::class, 'import'])->name('categories.import');
        Route::post('/postImport', [CategoriesController::class, 'postImport'])->name('categories.postImport');
        Route::get('/importToDb', [CategoriesController::class, 'importToDb'])->name('categories.importToDb');
    });

    Route::group(['prefix'=>'tags'], function (){
        Route::get('/',[TagsController::class,'index'])->name('tags.index');
        Route::post('/getIndex',[TagsController::class,'getIndex'])->name('tags.getIndex');
        Route::post('/create',[TagsController::class,'create'])->name('tags.create');
        Route::get('/edit/{id}',[TagsController::class,'edit'])->name('tags.edit');
        Route::post('/update',[TagsController::class,'update'])->name('tags.update');
        Route::post('/delete',[TagsController::class,'delete'])->name('tags.delete');
        Route::get('/change-tag/{id}',[TagsController::class,'changeTag'])->name('tags.changeTag');
        Route::get('/find',[TagsController::class,'find'])->name('tags.find');

    });

//    Route::group(['prefix'=>'wallpapers'], function (){
//        Route::get('/',[WallpapersController::class,'index'])->name('wallpapers.index');
//        Route::post('/getIndex',[WallpapersController::class,'getIndex'])->name('wallpapers.getIndex');
//        Route::post('/create',[WallpapersController::class,'create'])->name('wallpapers.create');
//        Route::get('/edit/{id}',[WallpapersController::class,'edit'])->name('wallpapers.edit');
//        Route::post('/update',[WallpapersController::class,'update'])->name('wallpapers.update');
//        Route::get('/delete/{id}',[WallpapersController::class,'delete'])->name('wallpapers.delete');
//        Route::post('/deleteSelect', [WallpapersController::class, 'deleteSelect'])->name('wallpapers.deleteSelect');
////        Route::get('/import', [WallpapersController::class, 'import'])->name('wallpapers.import');
////        Route::post('/postImport', [WallpapersController::class, 'postImport'])->name('wallpapers.postImport');
////        Route::get('/importToDb', [WallpapersController::class, 'importToDb'])->name('wallpapers.importToDb');
//        Route::get('/compare', [WallpapersController::class, 'compare'])->name('wallpapers.compare');
//        Route::get('/compareFile', [WallpapersController::class, 'compareFile'])->name('wallpapers.compareFile');
//        Route::post('/compareFilePost', [WallpapersController::class, 'compareFilePost'])->name('wallpapers.compareFilePost');
//    });
//
//    Route::group(['prefix'=>'ringtones'], function (){
//        Route::get('/',[RingtonesController::class,'index'])->name('ringtones.index');
//        Route::post('/getIndex',[RingtonesController::class,'getIndex'])->name('ringtones.getIndex');
//        Route::post('/create',[RingtonesController::class,'create'])->name('ringtones.create');
//        Route::get('/edit/{id}',[RingtonesController::class,'edit'])->name('ringtones.edit');
//        Route::post('/update',[RingtonesController::class,'update'])->name('ringtones.update');
//        Route::get('/delete/{id}',[RingtonesController::class,'delete'])->name('ringtones.delete');
//        Route::post('/deleteSelect', [RingtonesController::class, 'deleteSelect'])->name('ringtones.deleteSelect');
////        Route::get('/import', [RingtonesController::class, 'import'])->name('wallpapers.import');
////        Route::post('/postImport', [RingtonesController::class, 'postImport'])->name('wallpapers.postImport');
////        Route::get('/importToDb', [RingtonesController::class, 'importToDb'])->name('wallpapers.importToDb');
//        Route::get('/compare', [RingtonesController::class, 'compare'])->name('ringtones.compare');
//    });


    Route::group(['prefix'=>'musics'], function (){
        Route::get('/',[MusicsController::class,'index'])->name('musics.index');
        Route::post('/getIndex',[MusicsController::class,'getIndex'])->name('musics.getIndex');
        Route::post('/create',[MusicsController::class,'create'])->name('musics.create');
        Route::get('/edit/{id}',[MusicsController::class,'edit'])->name('musics.edit');
        Route::post('/update',[MusicsController::class,'update'])->name('musics.update');
        Route::post('/update_multiple',[MusicsController::class,'update_multiple'])->name('musics.update_multiple');
        Route::get('/delete/{id}',[MusicsController::class,'delete'])->name('musics.delete');
        Route::post('/deleteSelect', [MusicsController::class, 'deleteSelect'])->name('musics.deleteSelect');
//        Route::get('/import', [RingtonesController::class, 'import'])->name('wallpapers.import');
//        Route::post('/postImport', [RingtonesController::class, 'postImport'])->name('wallpapers.postImport');
//        Route::get('/importToDb', [RingtonesController::class, 'importToDb'])->name('wallpapers.importToDb');
        Route::get('/compare', [MusicsController::class, 'compare'])->name('musics.compare');
    });

    Route::group(['prefix'=>'sites'], function (){
        Route::get('/',[SitesController::class,'index'])->name('sites.index');
        Route::post('/getIndex',[SitesController::class,'getIndex'])->name('sites.getIndex');
        Route::post('/create',[SitesController::class,'create'])->name('sites.create');
        Route::get('/edit/{id}',[SitesController::class,'edit'])->name('sites.edit');
        Route::post('/update',[SitesController::class,'update'])->name('sites.update');
        Route::post('/clone',[SitesController::class,'clone'])->name('sites.clone');
        Route::get('/delete/{id}',[SitesController::class,'delete'])->name('sites.delete');
        Route::get('/change_ajax/{id}', [SitesController::class, 'change_ajax'])->name('sites.change_ajax');
        Route::get('/get-aio/{id}', [SitesController::class, 'getAIO'])->name('sites.getAIO');

        Route::get('/view/{id}', [SitesController::class, 'viewSite'])->name('sites.view');
        Route::post('/view/update_site', [SitesController::class, 'update_site'])->name('sites.update_site');
        Route::post('/view/update_ads', [SitesController::class, 'update_ads'])->name('sites.update_ads');
//        Route::post('/view/update_category', [SitesController::class, 'update_category'])->name('sites.update_category');
        Route::post('/view/update_load_view_by', [SitesController::class, 'update_load_view_by'])->name('sites.update_load_view_by');
        Route::post('/view/update_FeatureImages', [SitesController::class, 'update_FeatureImages'])->name('sites.update_FeatureImages');
        Route::post('/view/getIndexCategories',[SitesController::class,'getIndexCategories'])->name('sites.getIndexCategories');
        Route::post('/view/getIndexListIPs',[SitesController::class,'getIndexListIPs'])->name('sites.getIndexListIPs');

        Route::get('/import', [SitesController::class, 'import'])->name('sites.import');
        Route::post('/postImport', [SitesController::class, 'postImport'])->name('sites.postImport');
        Route::get('/importToDb', [SitesController::class, 'importToDb'])->name('sites.importToDb');

    });

    Route::group(['prefix'=>'api-keys'], function (){
        Route::get('/',[ApiKeysController::class,'index'])->name('apikeys.index');
        Route::post('/getIndex',[ApiKeysController::class,'getIndex'])->name('apikeys.getIndex');
        Route::post('/create',[ApiKeysController::class,'create'])->name('apikeys.create');
        Route::get('/change-status/{id}', [ApiKeysController::class, 'changeStatus'])->name('api_keys.changeStatus');
    });

    Route::group(['prefix'=>'block-ips'], function (){
        Route::get('/',[BlockIPsController::class,'index'])->name('blockips.index');
        Route::post('/getIndex',[BlockIPsController::class,'getIndex'])->name('blockips.getIndex');
        Route::post('/create',[BlockIPsController::class,'create'])->name('blockips.create');
        Route::get('/edit/{id}',[BlockIPsController::class,'edit'])->name('blockips.edit');
        Route::post('/update',[BlockIPsController::class,'update'])->name('blockips.update');
        Route::get('/delete/{id}',[BlockIPsController::class,'delete'])->name('blockips.delete');
    });
});


