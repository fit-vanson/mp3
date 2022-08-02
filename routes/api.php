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
    Route::get('/get_search',[ApiController::class, 'get_search']);
    Route::get('/get_search_category',[ApiController::class, 'get_search_category']);
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
    Route::get('version/check/{id}',[App\Http\Controllers\Api\v3\ApiController::class, 'checkCode']);
    Route::get('/',[App\Http\Controllers\Api\v3\ApiController::class, 'index']);
    Route::get('first',[App\Http\Controllers\Api\v3\ApiController::class, 'first']);

    Route::get('category/all',[App\Http\Controllers\Api\v3\ApiController::class, 'categoryAll']);
    Route::get('wallpaper/all/{order}/{page}',[App\Http\Controllers\Api\v3\ApiController::class, 'wallpapersAll']);
    Route::get('wallpaper/random/{page}',[App\Http\Controllers\Api\v3\ApiController::class, 'wallpapersRandom']);
    Route::get('wallpaper/category/{page}/{category}',[App\Http\Controllers\Api\v3\ApiController::class, 'wallpapersByCategory']);
    Route::get('wallpaper/query/{page}/{query}',[App\Http\Controllers\Api\v3\ApiController::class, 'wallpapersBysearch']);
    Route::get('wallpaper/add/set/{id}',[App\Http\Controllers\Api\v3\ApiController::class, 'api_add_set']);
    Route::get('wallpaper/add/view/{id}',[App\Http\Controllers\Api\v3\ApiController::class, 'api_add_view']);
    Route::get('wallpaper/add/download/{id}',[App\Http\Controllers\Api\v3\ApiController::class, 'api_add_download']);

});

Route::group([
    "prefix" => "v4",
//    'middleware' => 'auth.apikey'
], function() {
    Route::get('admob',[App\Http\Controllers\Api\v4\ApiController::class, 'admob']);
    Route::get('settings',[App\Http\Controllers\Api\v4\ApiController::class, 'settings']);
    Route::get('home',[App\Http\Controllers\Api\v4\ApiController::class, 'home']);

    Route::get('categories',[App\Http\Controllers\Api\v4\ApiController::class, 'categories']);

    Route::get('wallpaper',[App\Http\Controllers\Api\v4\ApiController::class, 'wallpaper']);
    Route::get('wallpaper/popular',[App\Http\Controllers\Api\v4\ApiController::class, 'popular']);
    Route::get('wallpaper/download',[App\Http\Controllers\Api\v4\ApiController::class, 'download']);
    Route::get('wallpaper/random',[App\Http\Controllers\Api\v4\ApiController::class, 'random']);
    Route::get('wallpaper/cid',[App\Http\Controllers\Api\v4\ApiController::class, 'cid']);
    Route::get('wallpaper/live',[App\Http\Controllers\Api\v4\ApiController::class, 'live']);

    Route::get('wallpaper/hashtag',[App\Http\Controllers\Api\v4\ApiController::class, 'hashtag']);



    Route::get('add/show/wallpaper',[App\Http\Controllers\Api\v4\ApiController::class, 'viewWallpaper']);



});


Route::group([
    "prefix" => "v6",
//    'middleware' => 'auth.apikey'
], function() {
    Route::post('auth/login',[App\Http\Controllers\Api\v6\ApiController::class, 'login']);
    Route::get('categories',[App\Http\Controllers\Api\v6\ApiController::class, 'categories']);

    Route::get('wallpapers/newest',[App\Http\Controllers\Api\v6\ApiController::class, 'newest']);
    Route::get('wallpapers/trending',[App\Http\Controllers\Api\v6\ApiController::class, 'trending']);
    Route::get('wallpapers/random',[App\Http\Controllers\Api\v6\ApiController::class, 'random']);

    Route::put('wallpapers/download',[App\Http\Controllers\Api\v6\ApiController::class, 'download']);
    Route::put('wallpapers/use',[App\Http\Controllers\Api\v6\ApiController::class, 'use']);
});

Route::group([
    "prefix" => "v7",
//    'middleware' => 'auth.apikey'
], function() {
    Route::get('getJson',[App\Http\Controllers\Api\v7\ApiController::class, 'getJson']);
    Route::get('categories',[App\Http\Controllers\Api\v7\ApiController::class, 'categories']);
    Route::get('action',[App\Http\Controllers\Api\v7\ApiController::class, 'action']);

});

Route::get('wallpaper/{id}',[App\Http\Controllers\Api\v7\ApiController::class, 'showWallpaper']);
Route::get('wallpaperThumb/{id}',[App\Http\Controllers\Api\v7\ApiController::class, 'showWallpaperThumb']);



