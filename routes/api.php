<?php

use App\Http\Controllers\Api\v1\CategoriesController;
use App\Http\Controllers\Api\v1\FavoriteController;
use App\Http\Controllers\Api\v1\MusicsController;
use App\Http\Controllers\Api\v1\RingtonesController;


use App\Http\Controllers\Api\v4\ApiV4Controller;
use App\Http\Controllers\Api\v5\ApiV5Controller;
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



Route::group([
    "prefix" => "v2"
], function() {
    Route::get('/init.php', [\App\Http\Controllers\Api\v2\ApiV2Controler::class, 'init']);
    Route::get('/view.php', [\App\Http\Controllers\Api\v2\ApiV2Controler::class, 'view']);
    Route::get('/search.php', [\App\Http\Controllers\Api\v2\ApiV2Controler::class, 'search']);

});

Route::group([
    "prefix" => "v3"
], function() {
    Route::get('/ads', [\App\Http\Controllers\Api\v3\ApiV3Controler::class, 'getAds']);
    Route::get('/get_home', [\App\Http\Controllers\Api\v3\ApiV3Controler::class, 'getHome']);
    Route::get('/category', [\App\Http\Controllers\Api\v3\ApiV3Controler::class, 'getCategory']);
    Route::get('/category_detail', [\App\Http\Controllers\Api\v3\ApiV3Controler::class, 'getCategoryDetail']);
    Route::get('/search', [\App\Http\Controllers\Api\v3\ApiV3Controler::class, 'getSearch']);

});

Route::group([
    "prefix" => "v4"
], function() {
    Route::post('/app_details', [ApiV4Controller::class, 'app_details']);
    Route::post('/home', [ApiV4Controller::class, 'home']);
    Route::post('/home_collections', [ApiV4Controller::class, 'home_collections']);
    Route::post('/trending_songs', [ApiV4Controller::class, 'trending_songs']);
    Route::post('/song_by_category', [ApiV4Controller::class, 'song_by_category']);
    Route::post('/home_slider_songs', [ApiV4Controller::class, 'home_slider_songs']);
    Route::post('/home_recently_songs', [ApiV4Controller::class, 'home_recently_songs']);
    Route::post('/category', [ApiV4Controller::class, 'category']);
    Route::post('/all_musics', [ApiV4Controller::class, 'all_musics']);
    Route::post('/latest_songs', [ApiV4Controller::class, 'latest_songs']);
    Route::post('/song_view', [ApiV4Controller::class, 'song_view']);
    Route::post('/song_download', [ApiV4Controller::class, 'song_download']);
    Route::post('/song_favourite', [ApiV4Controller::class, 'song_favourite']);
    Route::post('/user_favourite_songs', [ApiV4Controller::class, 'user_favourite_songs']);
    Route::post('/search', [ApiV4Controller::class, 'search']);
    Route::post('/search_single', [ApiV4Controller::class, 'search_single']);


});


Route::group([
    "prefix" => "v5"
], function() {
    Route::get('/settingsFlag', [ApiV5Controller::class, 'settingsFlag']);
    Route::post('/home_components', [ApiV5Controller::class, 'home_components']);
    Route::post('/home', [ApiV5Controller::class, 'home']);



//
//    Route::post('/get-data', [ApiV5Controller::class, 'get_data']);
//    Route::post('/app_details', [ApiV4Controller::class, 'app_details']);
//    Route::post('/home', [ApiV4Controller::class, 'home']);
//    Route::post('/home_collections', [ApiV4Controller::class, 'home_collections']);
//    Route::post('/trending_songs', [ApiV4Controller::class, 'trending_songs']);
//    Route::post('/song_by_category', [ApiV4Controller::class, 'song_by_category']);
//    Route::post('/home_slider_songs', [ApiV4Controller::class, 'home_slider_songs']);
//    Route::post('/home_recently_songs', [ApiV4Controller::class, 'home_recently_songs']);
//    Route::post('/category', [ApiV4Controller::class, 'category']);
//    Route::post('/all_musics', [ApiV4Controller::class, 'all_musics']);
//    Route::post('/latest_songs', [ApiV4Controller::class, 'latest_songs']);
//    Route::post('/song_view', [ApiV4Controller::class, 'song_view']);
//    Route::post('/song_download', [ApiV4Controller::class, 'song_download']);
//    Route::post('/song_favourite', [ApiV4Controller::class, 'song_favourite']);
//    Route::post('/user_favourite_songs', [ApiV4Controller::class, 'user_favourite_songs']);
//    Route::post('/search', [ApiV4Controller::class, 'search']);
//    Route::post('/search_single', [ApiV4Controller::class, 'search_single']);


});








