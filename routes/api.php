<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\ProfileController;

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
Route::post('login',[LoginController::class,'login']);
Route::post('register',[RegisterController::class,'store']);
Route::middleware('auth:api')->group(function () {
    Route::get('user/profile', [ProfileController::class,'profile']);
    Route::post('update/profile', [ProfileController::class,'updateProfile']);
});
