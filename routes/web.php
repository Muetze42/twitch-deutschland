<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BroadcasterController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\VideoController;
use App\Http\Middleware\HandleInertiaRequests;
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
Route::middleware([HandleInertiaRequests::class])->group(function () {
    Route::get('/', [VideoController::class, 'index'])->name('videos.index');

    Route::get('/streams', [BroadcasterController::class, 'index'])->name('streams.index');

    Route::get('/channels', [ChannelController::class, 'index'])->name('channels.index');
});

Route::get('auth/{provider}', [AuthController::class, 'redirect'])
    ->name('auth')->where('provider', 'twitch');
Route::get('auth/{provider}/callback', [AuthController::class, 'callback'])
    ->where('provider', 'twitch');
