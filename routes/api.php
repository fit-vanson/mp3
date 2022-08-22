<?php

use App\Http\Controllers\Api\v1\CategoriesController;
use App\Http\Controllers\Api\v1\FavoriteController;
use App\Http\Controllers\Api\v1\MusicsController;
use App\Http\Controllers\Api\v1\RingtonesController;




use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

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


if (App::environment('production', 'staging')) {
    URL::forceScheme('https');
}


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/test',function (){
    return ['a'=>'test'];
});

Route::group([
    "prefix" => "v1"
], function() {
    Route::get('/categories', [CategoriesController::class, 'index']);
    Route::get('/categories/{category_id}/musics', [CategoriesController::class, 'getMusics']);

    Route::get('categories/{category_id}/musics/{deviceId}', [MusicsController::class, 'getMusicsByCate']);
    Route::get('music-detail/{id}/{device_id}', [MusicsController::class, 'show']);
    Route::get('musics/popular/{deviceId}', [MusicsController::class, 'getPopulared']);
    Route::get('musics/newest/{deviceId}', [MusicsController::class, 'getNewest']);
    Route::get('musics/most-download/{deviceId}', [MusicsController::class, 'getMostDownload']);


    Route::post('ringtone-favorite/', [FavoriteController::class, 'like']);
    Route::post('ringtone-favorite-unsaved/', [FavoriteController::class, 'disLike']);
    Route::get('favorite/{device_id}', [FavoriteController::class, 'getSaved']);

    Route::post('search', [MusicsController::class, 'search']);


});

//
//Route::group([
//    "prefix" => "v1"
////    'middleware' => 'auth.apikey'
//], function() {
//    Route::get('/get_categories',[ApiController::class, 'get_categories']);
//    Route::get('/get_wallpapers',[ApiController::class, 'get_wallpapers']);
//    Route::get('/get_category_details',[ApiController::class, 'get_category_details']);
//    Route::get('/get_ads',[ApiController::class, 'get_ads']);
//    Route::get('/get_settings',[ApiController::class, 'get_settings']);
//    Route::get('/get_search',[ApiController::class, 'get_search']);
//    Route::get('/get_search_category',[ApiController::class, 'get_search_category']);
//    Route::post('/update_view',[ApiController::class, 'update_view']);
//    Route::post('/update_download',[ApiController::class, 'update_download']);
//    Route::get('/',[ApiController::class, 'index']);
//});
//
//Route::group([
//    "prefix" => "v2"
////    'middleware' => 'auth.apikey'
//], function() {
//    Route::get('/',[v2::class, 'index']);
//    Route::post('/getData',[v2::class, 'getData']);
//});
//
//
//Route::group([
//    "prefix" => "v3",
////    'middleware' => 'auth.apikey'
//], function() {
//    Route::get('version/check/{id}',[v3::class, 'checkCode']);
//    Route::get('/',[v3::class, 'index']);
//    Route::get('first',[v3::class, 'first']);
//
//    Route::get('category/all',[v3::class, 'categoryAll']);
//    Route::get('wallpaper/all/{order}/{page}',[v3::class, 'wallpapersAll']);
//    Route::get('wallpaper/random/{page}',[v3::class, 'wallpapersRandom']);
//    Route::get('wallpaper/category/{page}/{category}',[v3::class, 'wallpapersByCategory']);
//    Route::get('wallpaper/query/{page}/{query}',[v3::class, 'wallpapersBysearch']);
//    Route::get('wallpaper/add/set/{id}',[v3::class, 'api_add_set']);
//    Route::get('wallpaper/add/view/{id}',[v3::class, 'api_add_view']);
//    Route::get('wallpaper/add/download/{id}',[v3::class, 'api_add_download']);
//
//});
//
//Route::group([
//    "prefix" => "v4",
////    'middleware' => 'auth.apikey'
//], function() {
//    Route::get('admob',[v4::class, 'admob']);
//    Route::get('settings',[v4::class, 'settings']);
//    Route::get('home',[v4::class, 'home']);
//
//    Route::get('categories',[v4::class, 'categories']);
//
//    Route::get('wallpaper',[v4::class, 'wallpaper']);
//    Route::get('wallpaper/popular',[v4::class, 'popular']);
//    Route::get('wallpaper/download',[v4::class, 'download']);
//    Route::get('wallpaper/random',[v4::class, 'random']);
//    Route::get('wallpaper/cid',[v4::class, 'cid']);
//    Route::get('wallpaper/live',[v4::class, 'live']);
//
//    Route::get('wallpaper/hashtag',[v4::class, 'hashtag']);
//
//
//
//    Route::get('add/show/wallpaper',[v4::class, 'viewWallpaper']);
//
//
//
//});
//
//
//Route::group([
//    "prefix" => "v6",
////    'middleware' => 'auth.apikey'
//], function() {
//    Route::post('auth/login',[v6::class, 'login']);
//    Route::get('categories',[v6::class, 'categories']);
//
//    Route::get('wallpapers/newest',[v6::class, 'newest']);
//    Route::get('wallpapers/trending',[v6::class, 'trending']);
//    Route::get('wallpapers/random',[v6::class, 'random']);
//
//    Route::put('wallpapers/download',[v6::class, 'download']);
//    Route::put('wallpapers/use',[v6::class, 'use']);
//});
//
//Route::group([
//    "prefix" => "v7",
////    'middleware' => 'auth.apikey'
//], function() {
//    Route::get('getJson',[v7::class, 'getJson']);
//    Route::get('getJsonV8',[v7::class, 'getJsonV8']);
//    Route::get('status',[v7::class, 'status'])->name('v8.status');
//    Route::get('categories',[v7::class, 'categories']);
//    Route::get('action',[v7::class, 'action']);
//
//});
//
//Route::get('wallpaper/{id}',[v7::class, 'showWallpaper']);
//Route::get('wallpaperThumb/{id}',[v7::class, 'showWallpaperThumb']);



