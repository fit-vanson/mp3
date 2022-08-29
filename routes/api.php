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

});






