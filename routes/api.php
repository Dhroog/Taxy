<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\ReasonController;
use App\Http\Controllers\RolesPermissionController;
use App\Http\Controllers\TripController;
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

route::post('test/',[UserController::class,'test']);

    Route::post("register", [AuthController::class, "register"]);
    Route::post("login", [AuthController::class, "login"]);
    Route::post("ResetPassword", [AuthController::class, "ResetPassword"]);
    Route::post("ForgetPassword", [AuthController::class, "ForgetPassword"]);
    Route::post("ChangePassword", [AuthController::class, "ChangePassword"]);






////////////////////////////////////////////////All Api Need AUTH///////////////////////////////////////////////////////////////////
Route::group(["prefix" => "", "middleware" => ["auth:sanctum"]], function () {

    route::get('ReSendCode',[AuthController::class, "ReSendCode"]);
    Route::post("customer/VerifyPhone", [AuthController::class, "VerifyPhone"]);
    Route::post("InsertTokenFireBase", [AuthController::class, "InsertTokenFireBase"]);
    Route::post("UploadImage", [UserController::class, "UploadImage"]);
    Route::get("GetImageById/{id}", [UserController::class, "GetImageById"]);
    Route::get("logout", [AuthController::class, "logout"]);



});


///////////////////////////////////////ADMIN API///////////////////////////////////////////////////////////////
Route::group(["prefix" => "admin", "middleware" => ["auth:sanctum", "ActiveAccount"]], function () {

    Route::get("users", [UserController::class, "AllUsers"])->middleware("permission:Get-All-Users");
    Route::get("drivers", [DriverController::class, "AllDrivers"])->middleware("permission:Get-All-Drivers");
    Route::get("AllJobApplication", [DriverController::class, "AllJobApplication"])->middleware("permission:Get-All-Job-Application");
    Route::get("AllUpdateDriverInfoApplication", [DriverController::class, "permission:AllUpdateDriverInfoApplication"])->middleware("permission:Get-All-Update-Driver-Info-Application");
    Route::get("GetUserById/{id}", [UserController::class, "GetUserById"])->middleware("permission:Get-User");
    Route::post('AcceptOrRejectDriverJobApplication',[DriverController::class,'AcceptOrRejectDriverJobApplication'])->middleware("permission:Review-Job-Application");
    Route::post('AcceptOrRejectUpdateDriverInfoApplication',[DriverController::class,'AcceptOrRejectUpdateDriverInfoApplication'])->middleware("permission:Review-Update-Driver-Info-Application");
    Route::post('CreateCategory',[CategoryController::class,'create'])->middleware("permission:Create-Category");
    Route::put('EditCategory',[CategoryController::class,'edit'])->middleware("permission:Update-Category");
    Route::Delete('DeleteCategory/{id}',[CategoryController::class,'delete'])->middleware("permission:Delete-Category");
    Route::get('GetAllCategories',[CategoryController::class,'GetAllCategories'])->middleware("permission:Get-All-Categories");
    Route::post('CreateRole',[RolesPermissionController::class,'create'])->middleware("permission:Create-Role");
    Route::put('EditRole',[RolesPermissionController::class,'edit'])->middleware("permission:Update-Role");
    Route::Delete('DeleteRole/{id}',[RolesPermissionController::class,'delete'])->middleware("permission:Delete-Role");
    Route::get('GetAllRoles',[RolesPermissionController::class,'GetAllRoles'])->middleware("permission:Get-All-Roles");
    Route::get('GetAllPermissions',[RolesPermissionController::class,'GetAllPermissions'])->middleware("permission:Get-All-Permissions");
    Route::post('AddPermissionToRole',[RolesPermissionController::class,'AddPermissionToRole'])->middleware("permission:Add-Permission-To-Role");
    Route::post('RemovePermissionFromRole',[RolesPermissionController::class,'RemovePermissionFromRole'])->middleware("permission:Remove-Permission-From-Role");
    Route::post('AddPermissionToUser',[RolesPermissionController::class,'AddPermissionToUser'])->middleware("permission:Add-Permission-To-User");
    Route::post('RemovePermissionFromUser',[RolesPermissionController::class,'RemovePermissionFromUser'])->middleware("permission:Remove-Permission-From-User");
    Route::post('ChangeRoleUser',[RolesPermissionController::class,'ChangeRoleUser'])->middleware("permission:Change-Role-User");
    Route::post('CreateReason',[ReasonController::class,'create'])->middleware("permission:Create-Reason");
    Route::put('EditReason',[ReasonController::class,'edit'])->middleware("permission:Update-Reason");
    Route::Delete('DeleteReason/{id}',[ReasonController::class,'delete'])->middleware("permission:Delete-Reason");
    Route::get('GetAllReasons',[ReasonController::class,'GetAllReasons'])->middleware("permission:Get-All-Reasons");






});
///////////////////////////////////////customer api///////////////////////////////////////////////////////////////
Route::group(["prefix" => "customer", "middleware" => ["auth:sanctum", "ActiveAccount"]], function () {

    Route::get("profile", [UserController::class, "profile"])->middleware("permission:Show-Profile");
    Route::put("UpdateProfile", [UserController::class, "Update"])->middleware("permission:Update-Profile");
    Route::post("DriverJobApplication", [DriverController::class, "DriverJobApplication"])->middleware("permission:Send-Job-Application");
    Route::post("CreateTrip", [TripController::class, "CreateTrip"])->middleware("permission:Create-Trip");
    Route::post("ConfirmTrip", [TripController::class, "ConfirmTrip"])->middleware("permission:Confirm-Trip");



});

//////////////////////////////////////Driver Api///////////////////////////////////////////////////////////////
Route::group(["prefix" => "Driver", "middleware" => ["auth:sanctum", "ActiveAccount"]], function () {

    Route::post("UpdateDriverInfoApplication", [DriverController::class, "UpdateDriverInfoApplication"])->middleware("permission:Send-Update-Driver-Info-Application");
    route::get('GetStatusDriverJobApplication/{id}',[DriverController::class,'GetStatusDriverJobApplication'])->middleware("permission:Get-Status-Driver-Job-Application");


});
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

