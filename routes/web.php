<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
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

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/', 'HomeController@home')->name('home');
Route::get('/', [HomeController::class, 'home'])
    ->name('home.index')
    // ->middleware('auth')
    ;

Route::get('/contact', [HomeController::class, 'contact'])->name('home.contact');
Route::resource('/posts', PostController::class);
// Route::resource('/posts', [PostController::class]);

Auth::routes();
