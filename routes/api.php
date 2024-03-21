<?php

use App\Http\Controllers\Api\UserController;
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


Route::post('/user-register',[UserController::class,'register']);
Route::get('/user-login',[UserController::class,'login']);
Route::get('/user-password_reset',[UserController::class,'password_reset_by_email']);
Route::get('/reset/{token}',[UserController::class,'reset']);

Route::middleware(['auth:sanctum'])->group(function(){
    Route::get('/user-logout',[UserController::class,'logout']);
    Route::get('/user-logedin',[UserController::class,"logedin_user"]);
    Route::get('/user-change-password',[UserController::class,"change_password"]);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
