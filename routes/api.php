<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BalanceController;
use App\Http\Controllers\CancellationReasonController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RejectionReasonController;
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

route::get('test/{id}',[UserController::class,'test']);

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
Route::group(["prefix" => "admin", "middleware" => ["auth:sanctum", "ActiveAccount","BannedAccount"]], function () {
  /////////////////////////////////////////////////CREATE ADMIN API////////////////////////////////////////////////////////
    Route::post('CreateAdmin',[AdminController::class,'create'])->middleware("permission:Create-Admin");
    Route::Delete("DeleteAdmin/{id}", [AdminController::class, "delete"])->middleware("permission:Delete-Admin");
    Route::post('changeBannedState',[AdminController::class,'changeBannedState'])->middleware("permission:Change_Banned_Admin");
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    Route::get("users", [UserController::class, "AllUsers"])->middleware("permission:Get-All-Users");
    Route::get("drivers", [DriverController::class, "AllDrivers"])->middleware("permission:Get-All-Drivers");
    Route::get("AllJobApplication", [DriverController::class, "AllJobApplication"])->middleware("permission:Get-All-Job-Application");
    Route::get("AllUpdateDriverInfoApplication", [DriverController::class,"AllUpdateDriverInfoApplication"])->middleware("permission:Get-All-Update-Driver-Info-Application");
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
    Route::post('AddRoleUser',[RolesPermissionController::class,'AddRoleUser'])->middleware("permission:Add-Role-User");
    Route::post('RemoveRoleUser',[RolesPermissionController::class,'RemoveRoleUser'])->middleware("permission:Remove-Role-User");
    Route::post('CreateCancellationReason',[CancellationReasonController::class,'create'])->middleware("permission:Create-Cancellation-Reason");
    Route::put('EditCancellationReason',[CancellationReasonController::class,'edit'])->middleware("permission:Update-Cancellation-Reason");
    Route::Delete('DeleteCancellationReason/{id}',[CancellationReasonController::class,'delete'])->middleware("permission:Delete-Cancellation-Reason");
    Route::get('GetAllCancellationReasons',[CancellationReasonController::class,'GetAllCancellationReasons'])->middleware("permission:Get-All-Cancellation-Reasons");
    Route::post('CreateRejectionReason',[RejectionReasonController::class,'create'])->middleware("permission:Create-Rejection-Reason");
    Route::put('EditRejectionReason',[RejectionReasonController::class,'edit'])->middleware("permission:Update-Rejection-Reason");
    Route::Delete('DeleteRejectionReason/{id}',[RejectionReasonController::class,'delete'])->middleware("permission:Delete-Rejection-Reason");
    Route::get('GetAllRejectionReasons',[RejectionReasonController::class,'GetAllRejectionReasons'])->middleware("permission:Get-All-Rejection-Reasons");
    Route::post('ChargeDriverBalance',[BalanceController::class,'ChargeDriverBalance'])->middleware("permission:Charge-Driver-Balance");
    Route::post('DiscountDriverBalance',[BalanceController::class,'DiscountDriverBalance'])->middleware("permission:Discount-Driver-Balance");
    Route::post('RewardDriverBalance',[BalanceController::class,'RewardDriverBalance'])->middleware("permission:Reward-Driver-Balance");
    Route::post('SendNotificationAll',[NotificationController::class,'SendNotificationAll'])->middleware("permission:Send-Notification-All");
    Route::post('SendNotificationByID',[NotificationController::class,'SendNotificationByID'])->middleware("permission:Send-Notification-By-ID");












});
///////////////////////////////////////customer api///////////////////////////////////////////////////////////////
Route::group(["prefix" => "customer", "middleware" => ["auth:sanctum", "ActiveAccount","BannedAccount"]], function () {

    Route::get("profile", [UserController::class, "profile"])->middleware("permission:Show-Profile");
    Route::put("UpdateProfile", [UserController::class, "Update"])->middleware("permission:Update-Profile");
    Route::post("DriverJobApplication", [DriverController::class, "DriverJobApplication"])->middleware("permission:Send-Job-Application");
    route::get('GetDriverJobApplication/{id}',[DriverController::class,'GetDriverJobApplication'])->middleware("permission:Get-Status-Driver-Job-Application");




});

//////////////////////////////////////Driver Api///////////////////////////////////////////////////////////////
Route::group(["prefix" => "Driver", "middleware" => ["auth:sanctum", "ActiveAccount","BannedAccount"]], function () {

    Route::post("UpdateDriverInfoApplication", [DriverController::class, "UpdateDriverInfoApplication"])->middleware("permission:Send-Update-Driver-Info-Application");
    Route::post("SendRejectionReason", [RejectionReasonController::class, "SendRejectionReason"])->middleware("permission:Send-Rejection-Reason");
    Route::get('GetDriverBalance/{id}',[BalanceController::class,'GetDriverBalance'])->middleware("permission:Get-Driver-Balance");
    Route::get("StartTrip/{trip_id}", [TripController::class, "StartTrip"])->middleware("permission:Start-Trip");
    Route::get("EndTrip/{trip_id}", [TripController::class, "EndTrip"])->middleware("permission:End-Trip");
    Route::post("ChangeStatusDriver", [DriverController::class, "ChangeStatusDriver"])->middleware("permission:Change-Status-Driver");







});

///////////////////////////////////////Trip api///////////////////////////////////////////////////////////////
Route::group(["prefix" => "Trip", "middleware" => ["auth:sanctum", "ActiveAccount","BannedAccount"]], function () {

    Route::post("CreateTrip", [TripController::class, "CreateTrip"])->middleware("permission:Create-Trip");
    Route::post("ConfirmTrip", [TripController::class, "ConfirmTrip"])->middleware("permission:Confirm-Trip");
    Route::get("GetTripById/{id}", [TripController::class, "GetTripById"])->middleware("permission:Get-Trip-By-Id");
    Route::get("GetAllTrips", [TripController::class, "GetAllTrips"])->middleware("permission:Get-All-Trips");
    Route::get("GetAllActiveTrips", [TripController::class, "GetAllActiveTrips"])->middleware("permission:Get-All-Active-Trips");
    Route::get("GetAllTripsDriverCanAccept/{driver_id}", [TripController::class, "GetAllTripsDriverCanAccept"])->middleware("permission:Get-All-Trips-Driver-Can-Accept");
    Route::get("GetUserTrips/{id}", [TripController::class, "GetUserTrips"])->middleware("permission:Get-User-Trips");
    Route::get("GetDriverTrips/{id}", [TripController::class, "GetDriverTrips"])->middleware("permission:Get-Driver-Trips");
    Route::get("AcceptTrip/{id}", [TripController::class, "AcceptTrip"])->middleware("permission:Accept-Trip");
    Route::post("SendCancellationReason", [CancellationReasonController::class, "SendCancellationReason"])->middleware("permission:Send-Cancellation-Reason");
    Route::post("SetTrackingTrip/{id}", [TripController::class, "SetTrackingTrip"])->middleware("permission:Set-Tracking-Trip");









});

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

