<?php

use App\Models\User;
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

// Auth::routes();
// Route::get('/login', [App\Http\Controllers\AuthController::class, 'showLogin'])->name('show.login');
// Route::post('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login');
// Route::get('/register', [App\Http\Controllers\AuthController::class, 'showRegister'])->name('show.register');
// Route::post('/register', [App\Http\Controllers\AuthController::class, 'register'])->name('register');
// Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');
// Route::get('/profile/{user?}', [App\Http\Controllers\UserController::class, 'show'])->name('profile.show');
// Route::put('/profile/{user?}', [App\Http\Controllers\UserController::class, 'update'])->name('profile.update');
// Route::get('/forgot-password', [App\Http\Controllers\AuthController::class, 'showForgotForm'])->name('show.form.reset');
// Route::post('/forgot-password', [App\Http\Controllers\AuthController::class, 'sendResetMail'])->name('send.mail.reset');
// Route::get('/reset-password', [App\Http\Controllers\AuthController::class, 'showResetForm'])->name('show.reset.password');
// Route::post('/reset-password', [App\Http\Controllers\AuthController::class, 'resetPassword'])->name('reset.password');

// Route::prefix('admin')->group(function() {
//     Route::get('/login', [App\Http\Controllers\Admin\AuthController::class, 'showLogin'])->name('admin.show.login');
//     Route::post('/login', [App\Http\Controllers\Admin\AuthController::class, 'login'])->name('admin.login');

//     Route::group(['middleware' => ['auth:admin']], function() {
//         Route::get('/', [App\Http\Controllers\Admin\HomeController::class, 'index'])->name('admin.home');
//         Route::post('/logout', [App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('admin.logout');
//     });
// });

// Route::group(['middleware' => ['auth:user']], function () {
//     Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
//     Route::get('/timesheet', [App\Http\Controllers\TimesheetController::class, 'index'])->name('timsheet.index');
//     // Route::get('/timesheet/{id}', [App\Http\Controllers\TimesheetController::class, 'show'])->name('timsheet.show');
//     Route::get('/timesheet-detail', [App\Http\Controllers\TimesheetController::class, 'showByDate'])->name('timsheet.show.by.date');
//     Route::post('/timesheet', [App\Http\Controllers\TimesheetController::class, 'store'])->name('timsheet.create');
//     Route::put('/timesheet/{id}', [App\Http\Controllers\TimesheetController::class, 'update'])->name('timesheet.update');
//     Route::put('/check-in', [App\Http\Controllers\TimesheetController::class, 'checkIn'])->name('user.check.in');
//     Route::put('/check-out', [App\Http\Controllers\TimesheetController::class, 'checkOut'])->name('user.check.out');
// });
