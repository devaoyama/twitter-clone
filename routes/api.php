<?php

use App\Http\Controllers\Api\LikeController;
use \App\Http\Controllers\Api\MessageController;
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

Route::get('/messages', [MessageController::class, 'index']);
Route::put('/messages/{message}', [MessageController::class, 'update'])
    ->middleware('can:update,message')
    ->name('api.messages.update');
Route::delete('/messages/{message}', [MessageController::class, 'destroy'])
    ->middleware('can:destroy,message')
    ->name('api.messages.destroy');

Route::post('/likes', [LikeController::class, 'store'])
    ->middleware('auth')
    ->name('api.likes.store');
Route::delete('/likes', [LikeController::class, 'destroy'])
    ->middleware('auth')
    ->name('api.likes.destroy');
