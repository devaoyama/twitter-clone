<?php

use App\Http\Controllers\DefaultController;
use App\Http\Controllers\MessageController;
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

Route::get('/', [DefaultController::class, 'index'])->name('default');

Route::post('/messages', [MessageController::class, 'store'])
    ->middleware('can:store,App\Models\Message');
Route::post('/messages/{message}', [MessageController::class, 'update'])
    ->middleware('can:update,message');
Route::delete('/messages/{message}', [MessageController::class, 'destroy'])
    ->middleware('can:destroy,message');

require __DIR__ . '/auth.php';
