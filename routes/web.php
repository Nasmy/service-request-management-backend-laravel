<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Web\LoginController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\RegisterController;
use Illuminate\Support\Facades\Log;

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

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('test', function () {
    Log::info('test gogger');
});

/** Admin portal routes with authentication **/
Route::group([
    'prefix' => '/admin',
    'middleware' => 'guest'
], function () {
    Route::view('login', 'auth.login')->name('login');
    Route::post('authenticate', [LoginController::class, 'authenticate'])->name('authenticate');
});

/** Admin portal routes with authentication**/
Route::group([
    'prefix' => '/admin',
    'middleware' => 'auth'
], function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('test', function () {
        return 'auth';
    })->middleware('authorization')->name('test.index');

    // Tenant user routes
    Route::get('user-create', [UserController::class, 'index'])->middleware('authorization')->name('user.create');
    Route::get('user-update/{id}', [UserController::class, 'edit'])->middleware('authorization')->name('user.edit');
    Route::delete('user-delete/{id}', [UserController::class, 'destroy'])->middleware('authorization')->name('user.destroy');

    // Tenant register related routes
    Route::post('user-create', [RegisterController::class, 'store'])->middleware('authorization')->name('user.store');
    Route::post('user-update/{id}', [UserController::class, 'update'])->middleware('authorization')->name('user.update');
    Route::get('logout', [LoginController::class, 'logout'])->name('auth.logout');

    Route::get('user-update/{user}/password', [UserController::class, 'editPassword'])
        ->middleware('authorization')
        ->name('user.edit-password');
    Route::put('user-update/{user}/password', [UserController::class, 'updatePassword'])
        ->middleware('authorization')
        ->name('user.update-password');
});
