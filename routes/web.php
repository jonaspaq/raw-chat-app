<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Api\MessageThreadController;
use App\Http\Controllers\Api\MessageThreadMemberController;
use App\Http\Controllers\Api\MessageThreadMessagesController;

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

Route::group(['middleware' => 'guest'], function(){
    Route::get('/', [UserController::class, 'loginView'])->name('login');
    Route::get('/register', [UserController::class, 'create']);

    Route::post('/register', [UserController::class, 'store']);
    Route::post('/login', [UserController::class, 'attemptLogin']);
});



Route::group(['middleware' => 'auth'], function(){
    Route::get('/home', [HomeController::class, 'homeView'])->name('home');
    Route::get('/logout', [UserController::class, 'logout']);
    Route::get('/user', [UserController::class, 'userInfo']);

    Route::get('/message/threads', [MessageThreadController::class, 'index']);

    Route::post('/thread/create', [MessageThreadController::class, 'store']);
    Route::get('/create/member', [MessageThreadMemberController::class, 'store']);

    Route::post('/search/thread', [MessageThreadController::class, 'searchThread']);
    Route::post('/search/thread/group', [MessageThreadController::class, 'searchThreadGroup']);
    Route::post('/search/connection', [MessageThreadController::class, 'searchConnection']);

    Route::get('/thread/messages/{id}', [MessageThreadMessagesController::class, 'fetchMessages']);
    Route::put('/thread/messages/create', [MessageThreadMessagesController::class, 'store']);
});

