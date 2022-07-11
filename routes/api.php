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


