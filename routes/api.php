<?php

use App\Http\Controllers\Api\BroadcasterController;
use App\Http\Controllers\Api\ChannelController;
use App\Http\Controllers\Api\VideoController;
use Illuminate\Support\Facades\Route;
use NormanHuth\LaravelOptimize\Http\Middleware\LaravelOptimizeApi;

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

Route::middleware([LaravelOptimizeApi::class])->group(function () {
    Route::post('/video/{video}', [VideoController::class, 'show'])->name('video.show');
    Route::post('/broadcaster/{broadcaster}', [BroadcasterController::class, 'show'])->name('broadcaster.show');
    Route::post('/channel/{channel}', [ChannelController::class, 'show'])->name('channel.show');
});
