<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\UserController;
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

Route::group(['middleware' => 'web'], function () {
    Route::get('api/documentation', '\L5Swagger\Http\Controllers\SwaggerController@api')->name('l5swagger.api');
});


Route::post("login", [AuthController::class, "login"]);
Route::post("register", [AuthController::class, "register"]);


Route::group(["prefix" => "", "middleware" => ["auth:sanctum", "ActiveAccount"]], function () {

    Route::get("logout", [AuthController::class, "logout"]);
    Route::get("profile", [UserController::class, "profile"]);
    Route::get('role', [UserController::class, 'role']);
    Route::post("verifyemail", [AuthController::class, "verifyemail"]);
});

