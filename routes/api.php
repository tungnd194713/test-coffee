<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login');
Route::post('/register', [App\Http\Controllers\AuthController::class, 'register'])->name('register');
Route::prefix('restaurant')->group(function() {
    Route::get('/', [App\Http\Controllers\RestaurantController::class, 'index'])->name('restaurant.index');
    Route::get('/{id}', [App\Http\Controllers\RestaurantController::class, 'show'])->name('restaurant.show');
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post('/{restaurant_id}/review', [App\Http\Controllers\RestaurantController::class, 'createReview'])->name('restaurant.create.review');
    });
    Route::get('/{restaurant_id}/review', [App\Http\Controllers\RestaurantController::class, 'getReview'])->name('restaurant.get.review');
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/get-profile', [App\Http\Controllers\UserController::class, 'detail'])->name('get.profile');
    Route::post('/delete-history', [App\Http\Controllers\AuthController::class, 'deleteHistory'])->name('delete.history');
    Route::post('/update-profile', [App\Http\Controllers\UserController::class, 'updateProfile'])->name('update.profile');
});
