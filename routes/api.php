<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\UserController;
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




////////////////////////////////////////////////All Api not Need AUTH///////////////////////////////////////////////////////////////////

route::get('test/{id?}',[UserController::class,'test']);

    Route::post("register", [AuthController::class, "register"]);
    Route::post("login", [AuthController::class, "login"]);
    Route::post("ResetPassword", [AuthController::class, "ResetPassword"]);
    Route::post("ForgetPassword", [AuthController::class, "ForgetPassword"]);
    Route::post("Driver/DriverJobApplication", [DriverController::class, "DriverJobApplication"]);
    route::get('Driver/GetStatusDriverJobApplication/{id}',[DriverController::class,'GetStatusDriverJobApplication']);
    route::post('AcceptOrRejectDriverJobApplication',[DriverController::class,'AcceptOrRejectDriverJobApplication']);





////////////////////////////////////////////////All Api Need AUTH///////////////////////////////////////////////////////////////////
Route::group(["prefix" => "", "middleware" => ["auth:sanctum"]], function () {

    route::get('ReSendCode',[AuthController::class, "ReSendCode"]);
    Route::post("customer/VerifyPhone", [AuthController::class, "VerifyPhone"]);
    Route::post("InsertTokenFireBase", [AuthController::class, "InsertTokenFireBase"]);
    Route::post("uploadImage", [UserController::class, "uploadImage"]);
    Route::get("getImage", [UserController::class, "getImage"]);
    Route::get("logout", [AuthController::class, "logout"]);



});


///////////////////////////////////////ADMIN API///////////////////////////////////////////////////////////////
Route::group(["prefix" => "admin", "middleware" => ["auth:sanctum", "ActiveAccount","Abilities:admin"]], function () {

    Route::get("users", [UserController::class, "AllUsers"]);
    Route::get("drivers", [DriverController::class, "AllDrivers"]);
    Route::get("AllJobApplication", [DriverController::class, "AllJobApplication"]);
    Route::get("GetUserById/{id}", [UserController::class, "GetUserById"]);

});
///////////////////////////////////////customer api///////////////////////////////////////////////////////////////
Route::group(["prefix" => "customer", "middleware" => ["auth:sanctum", "ActiveAccount","Abilities:customer"]], function () {

    Route::get("profile", [UserController::class, "profile"]);
    Route::put("UpdateProfile", [UserController::class, "Update"]);

});

//////////////////////////////////////Driver Api///////////////////////////////////////////////////////////////
Route::group(["prefix" => "customer", "middleware" => ["auth:sanctum", "ActiveAccount","Abilities:driver"]], function () {


});
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

