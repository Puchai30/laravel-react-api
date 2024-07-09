<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\VideosController;
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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/videos', [VideosController::class, 'index']);
Route::get('videos/search', [VideosController::class, 'index']);
Route::get('/videos/{slug}', [VideosController::class, 'show']);
Route::post('/videos/store', [VideosController::class, 'store']);
Route::patch('/videos/{slug}', [VideosController::class, 'update']);
Route::delete('/videos/{slug}', [VideosController::class, 'destory']);
Route::post('/videos/upload', [VideosController::class, 'upload']);

Route::get('/categories', [CategoryController::class, 'index']);
Route::post('/categories/store', [CategoryController::class, 'store']);
Route::patch('/categories/{slug}', [CategoryController::class, 'update']);
Route::delete('/categories/{slug}', [CategoryController::class, 'destory']);

Route::get('/tags', [TagController::class, 'index']);
Route::post('/tags/store', [TagController::class, 'store']);
Route::patch('/tags/{slug}', [TagController::class, 'update']);
Route::delete('/tags/{slug}', [TagController::class, 'destory']);
