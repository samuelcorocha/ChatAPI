<?php

use App\Http\Controllers\RoomsController;
use App\Http\Controllers\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('users')->controller(UsersController::class)->group(function(){
    Route::post('/', 'storeUser');
    Route::post('/login', 'login');
    Route::get('/{user_id}', 'show');
});

Route::prefix('rooms')->controller(RoomsController::class)->group(function(){
    Route::post('/', 'createRoom');
    Route::delete('/{room_id}', 'deleteRoom');
    Route::post('/{room_id}/enter');
    Route::post('/{room_id}/leave');
    Route::delete('/{room_id}/users/{user_id}');
    Route::match(['post', 'get'], '/{room_id}/messages');
});

Route::prefix('messages')->controller('')->group(function(){
    Route::post('/direct/{receiver_id}');
});
