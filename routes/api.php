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

route::get('test',[UserController::class,'test']);

Route::post("login", [AuthController::class, "login"]);
Route::post("register", [AuthController::class, "register"]);

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
Route::group(["prefix" => "", "middleware" => ["auth:sanctum"]], function () {

    route::get('ReSendCode',[AuthController::class, "ReSendCode"]);
    Route::post("VerifyEmail", [AuthController::class, "VerifyEmail"]);


});
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////


Route::group(["prefix" => "", "middleware" => ["auth:sanctum", "ActiveAccount"]], function () {

    Route::get("logout", [AuthController::class, "logout"]);
    Route::get('role', [UserController::class, 'role']);

});
///////////////////////////////////////admin api///////////////////////////////////////////////////////////////
Route::group(["prefix" => "admin", "middleware" => ["auth:sanctum", "ActiveAccount","Abilities:admin"]], function () {

    Route::get("users", [UserController::class, "AllUsers"]);
    Route::get("GetUserById/{id}", [UserController::class, "GetUserById"]);

});
///////////////////////////////////////customer api///////////////////////////////////////////////////////////////
Route::group(["prefix" => "customer", "middleware" => ["auth:sanctum", "ActiveAccount","Abilities:customer"]], function () {

    Route::get("profile", [UserController::class, "profile"]);

});
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
