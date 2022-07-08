<?php

use App\Http\Controllers\ApiKeysController;
use App\Http\Controllers\BlockIPsController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\RolesPermissionsController;
use App\Http\Controllers\SitesController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\WallpapersController;
use App\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
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


Route::get('/clear', function () {
    echo  Artisan::call('optimize');
    echo  Artisan::call('config:cache');
    echo  Artisan::call('route:cache');

});

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


//
//Route::get('index/{locale}', 'LocaleController@lang');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::group(['prefix'=>'categories'], function (){
    Route::get('/',[CategoriesController::class,'index'])->name('categories.index');
    Route::post('/getIndex',[CategoriesController::class,'getIndex'])->name('categories.getIndex');
    Route::post('/create',[CategoriesController::class,'create'])->name('categories.create');
    Route::get('/edit/{id}',[CategoriesController::class,'edit'])->name('categories.edit');
    Route::post('/update',[CategoriesController::class,'update'])->name('categories.update');
    Route::get('/delete/{id}',[CategoriesController::class,'delete'])->name('categories.delete');
});

Route::group(['prefix'=>'wallpapers'], function (){
    Route::get('/',[WallpapersController::class,'index'])->name('wallpapers.index');
    Route::post('/getIndex',[WallpapersController::class,'getIndex'])->name('wallpapers.getIndex');
    Route::post('/create',[WallpapersController::class,'create'])->name('wallpapers.create');
    Route::get('/edit/{id}',[WallpapersController::class,'edit'])->name('wallpapers.edit');
    Route::post('/update',[WallpapersController::class,'update'])->name('wallpapers.update');
    Route::get('/delete/{id}',[WallpapersController::class,'delete'])->name('wallpapers.delete');
    Route::post('/deleteSelect', [WallpapersController::class, 'deleteSelect'])->name('wallpapers.deleteSelect');
});

Route::group(['prefix'=>'sites'], function (){
    Route::get('/',[SitesController::class,'index'])->name('sites.index');
    Route::post('/getIndex',[SitesController::class,'getIndex'])->name('sites.getIndex');
    Route::post('/create',[SitesController::class,'create'])->name('sites.create');
    Route::get('/edit/{id}',[SitesController::class,'edit'])->name('sites.edit');
    Route::post('/update',[SitesController::class,'update'])->name('sites.update');
    Route::get('/delete/{id}',[SitesController::class,'delete'])->name('sites.delete');
    Route::get('/change-ads/{id}', [SitesController::class, 'changeAds'])->name('sites.changeAds');

    Route::get('/view/{id}', [SitesController::class, 'viewSite'])->name('sites.view');
    Route::post('/view/update_site', [SitesController::class, 'update_site'])->name('sites.update_site');
    Route::post('/view/update_ads', [SitesController::class, 'update_ads'])->name('sites.update_ads');
    Route::post('/view/update_category', [SitesController::class, 'update_category'])->name('sites.update_category');
    Route::post('/view/update_load_view_by', [SitesController::class, 'update_load_view_by'])->name('sites.update_load_view_by');
    Route::post('/view/update_FeatureImages', [SitesController::class, 'update_FeatureImages'])->name('sites.update_FeatureImages');
    Route::post('/view/getIndexCategories',[SitesController::class,'getIndexCategories'])->name('sites.getIndexCategories');
    Route::post('/view/getIndexListIPs',[SitesController::class,'getIndexListIPs'])->name('sites.getIndexListIPs');

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
