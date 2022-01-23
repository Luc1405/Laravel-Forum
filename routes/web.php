<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PokemonController;
use App\Http\Controllers\UserController;
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
});

Route::group(['middleware' => 'auth'], function () {
    Route::resource('posts', App\Http\Controllers\PostController::class);
});

Route::post('/update-status', [App\Http\Controllers\PostController::class, 'updateStatus']);

Route::get('/profile', [UserController::class,'index']);

Route::put('/profile/{username}',[UserController::class,'profileUpdate']);

Route::post('/posts/liked',[App\Http\Controllers\PostController::class, 'liked'])->name('liked');
Route::post('/posts/unliked',[App\Http\Controllers\PostController::class, 'unliked'])->name('unliked');


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
