<?php

use App\Http\Controllers\Api\v0\CategoriesController;
use App\Http\Controllers\Api\v0\FavoriteController;
use App\Http\Controllers\Api\v0\WallpapersController;
use App\Http\Controllers\Api\v1\ApiController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/test',function (){
    return ['a'=>'test'];
});

Route::group([
//    'middleware' => 'auth.apikey'
], function() {
    Route::get('/categories', [CategoriesController::class, 'index']);
    Route::get('/categories/{category_id}/wallpapers', [CategoriesController::class, 'getWallpapers']);

    Route::get('/wallpaper-detail/{id}/{device_id}', [WallpapersController::class, 'show']);
    Route::get('/wallpapers/featured', [WallpapersController::class, 'getFeatured']);
    Route::get('/wallpapers/popular', [WallpapersController::class, 'getPopulared']);
    Route::get('/wallpapers/newest', [WallpapersController::class, 'getNewest']);

    Route::post('/wallpaper-favorite', [FavoriteController::class, 'likeWallpaper']);
    Route::post('/wallpaper-favorite-unsaved', [FavoriteController::class, 'disLikeWallpaper']);
    Route::get('/favorite/{device_id}', [FavoriteController::class, 'getSaved']);
});


Route::group([
    "prefix" => "v1"
//    'middleware' => 'auth.apikey'
], function() {
    Route::get('/get_categories',[ApiController::class, 'get_categories']);
    Route::get('/get_wallpapers',[ApiController::class, 'get_wallpapers']);
    Route::get('/get_category_details',[ApiController::class, 'get_category_details']);
    Route::get('/get_ads',[ApiController::class, 'get_ads']);
    Route::get('/get_settings',[ApiController::class, 'get_settings']);
    Route::post('/update_view',[ApiController::class, 'update_view']);
    Route::post('/update_download',[ApiController::class, 'update_download']);
    Route::get('/',[ApiController::class, 'index']);
});

Route::group([
    "prefix" => "v2"
//    'middleware' => 'auth.apikey'
], function() {
    Route::get('/',[App\Http\Controllers\Api\v2\ApiController::class, 'index']);
    Route::post('/getData',[App\Http\Controllers\Api\v2\ApiController::class, 'getData']);
});


Route::group([
    "prefix" => "v3",
//    'middleware' => 'auth.apikey'
], function() {
    Route::get('version/check/{id}',[ApiV3Controller::class, 'checkCode']);
    Route::get('/',[ApiV3Controller::class, 'index']);
    Route::get('first',[ApiV3Controller::class, 'first']);

    Route::get('category/all',[ApiV3Controller::class, 'categoryAll']);
    Route::get('wallpaper/all/{order}/{page}',[ApiV3Controller::class, 'wallpapersAll']);
    Route::get('wallpaper/random/{page}',[ApiV3Controller::class, 'wallpapersRandom']);
    Route::get('wallpaper/category/{page}/{category}',[ApiV3Controller::class, 'wallpapersByCategory']);
    Route::get('wallpaper/query/{page}/{query}',[ApiV3Controller::class, 'wallpapersBysearch']);
    Route::get('wallpaper/add/set/{id}',[ApiV3Controller::class, 'api_add_set']);
    Route::get('wallpaper/add/view/{id}',[ApiV3Controller::class, 'api_add_view']);
    Route::get('wallpaper/add/download/{id}',[ApiV3Controller::class, 'api_add_download']);

});

Route::group([
    "prefix" => "v4",
//    'middleware' => 'auth.apikey'
], function() {
    Route::get('admob',[ApiV4Controller::class, 'admob']);
    Route::get('settings',[ApiV4Controller::class, 'settings']);
    Route::get('home',[ApiV4Controller::class, 'home']);

    Route::get('categories',[ApiV4Controller::class, 'categories']);

    Route::get('wallpaper',[ApiV4Controller::class, 'wallpaper']);
    Route::get('wallpaper/popular',[ApiV4Controller::class, 'popular']);
    Route::get('wallpaper/download',[ApiV4Controller::class, 'download']);
    Route::get('wallpaper/random',[ApiV4Controller::class, 'random']);
    Route::get('wallpaper/cid',[ApiV4Controller::class, 'cid']);
    Route::get('wallpaper/live',[ApiV4Controller::class, 'live']);

    Route::get('wallpaper/hashtag',[ApiV4Controller::class, 'hashtag']);



    Route::get('add/show/wallpaper',[ApiV4Controller::class, 'viewWallpaper']);



});


