<?php

use App\Http\Controllers\Api\LikeController;
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

Route::post('/likes', [LikeController::class, 'store'])
    ->middleware('auth')
    ->name('api.likes.store');
Route::delete('/likes', [LikeController::class, 'destroy'])
    ->middleware('auth')
    ->name('api.likes.destroy');
