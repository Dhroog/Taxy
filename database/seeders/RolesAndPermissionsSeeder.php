<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        //Manage Admin
        $CreateAdmin='Create-Admin';
        $DeleteAdmin='Delete-Admin';
        $ChangeBannedAdmin='Change_Banned_Admin';
        ////// Define Permissions
        //Manage Users
        $GetAllUsers = 'Get-All-Users';
        $GetUser = 'Get-User';
        //Manage Drivers
        $GetAllDrivers = 'Get-All-Drivers';
        //Manage Job Application
        $SendJobApplication = 'Send-Job-Application';
        $GetAllJobApplication = 'Get-All-Job-Application';
        $ReviewJobApplication = 'Review-Job-Application';
        $GetStatusDriverJobApplication = 'Get-Status-Driver-Job-Application';
        //Manage Update Driver Info Application
        $SendUpdateDriverInfoApplication = 'Send-Update-Driver-Info-Application';
        $GetAllUpdateDriverInfoApplication = 'Get-All-Update-Driver-Info-Application';
        $ReviewUpdateDriverInfoApplication = 'Review-Update-Driver-Info-Application';
        //Manage Categories
        $CreateCategory = 'Create-Category';
        $UpdateCategory = 'Update-Category';
        $DeleteCategory = 'Delete-Category';
        $GetAllCategories = 'Get-All-Categories';
        //Manage Profile
        $ShowProfile = 'Show-Profile';
        $UpdateProfile = 'Update-Profile';
        //Manage Roles & Permissions
        $CreateRole = 'Create-Role';
        $UpdateRole = 'Update-Role';
        $DeleteRole = 'Delete-Role';
        $GetAllRoles = 'Get-All-Roles';
        $GetAllPermissions = 'Get-All-Permissions';
        $AddPermissionToRole = 'Add-Permission-To-Role';
        $RemovePermissionFromRole = 'Remove-Permission-From-Role';
        $AddPermissionToUser = 'Add-Permission-To-User';
        $RemovePermissionFromUser = 'Remove-Permission-From-User';
        $ChangeRoleUser = 'Change-Role-User';
        $AddRoleUser = 'Add-Role-User';
        $RemoveRoleUser = 'Remove-Role-User';
        ///Manage Cancellation Reasons
        $CreateCancellationReason = 'Create-Cancellation-Reason';
        $UpdateCancellationReason = 'Update-Cancellation-Reason';
        $DeleteCancellationReason = 'Delete-Cancellation-Reason';
        $GetAllCancellationReasons = 'Get-All-Cancellation-Reasons';
        ///Manage Rejection Reasons
        $CreateRejectionReason = 'Create-Rejection-Reason';
        $UpdateRejectionReason = 'Update-Rejection-Reason';
        $DeleteRejectionReason = 'Delete-Rejection-Reason';
        $GetAllRejectionReasons = 'Get-All-Rejection-Reasons';
        $SendRejectionReason = 'Send-Rejection-Reason';
        ///Driver
        $GetDriverBalance = 'Get-Driver-Balance';
        $ChangeStatusDriver = 'Change-Status-Driver';
        ///Manage Trips
        $CreateTrip = 'Create-Trip';
        $ConfirmTrip = 'Confirm-Trip';
        $GetTripById = 'Get-Trip-By-Id';
        $GetAllTrips = 'Get-All-Trips';
        $GetAllTripsDriverCanAccept = 'Get-All-Trips-Driver-Can-Accept';
        $GetUserTrips = 'Get-User-Trips';
        $GetDriverTrips = 'Get-Driver-Trips';
        $AcceptTrip = 'Accept-Trip';
        $GetAllActiveTrips = 'Get-All-Active-Trips';
        $SendCancellationReason = 'Send-Cancellation-Reason';
        $StartTrip = 'Start-Trip';
        $SetTrackingTrip = 'Set-Tracking-Trip';
        $ChargeDriverBalance = 'Charge-Driver-Balance';
        $EndTrip = 'End-Trip';
        ///Notification
        $SendNotificationAll = 'Send-Notification-All';
        $SendNotificationByID = 'Send-Notification-By-ID';
        $GetAllUsersForNotification = 'Get-All-Users-For-Notification';
        $GetAllNotificationsForUser = 'Get-All-Notifications-For-User';






        Permission::create(['guard_name' => 'sanctum','name' => $CreateAdmin]);
        Permission::create(['guard_name' => 'sanctum','name' => $DeleteAdmin]);
        Permission::create(['guard_name' => 'sanctum','name' => $ChangeBannedAdmin]);

        ////// Create Permissions
        Permission::create(['guard_name' => 'sanctum','name' => $GetAllUsers]);
        Permission::create(['guard_name' => 'sanctum','name' => $GetUser]);
        Permission::create(['guard_name' => 'sanctum','name' => $GetAllDrivers]);
        Permission::create(['guard_name' => 'sanctum','name' => $SendJobApplication]);
        Permission::create(['guard_name' => 'sanctum','name' => $GetAllJobApplication]);
        Permission::create(['guard_name' => 'sanctum','name' => $ReviewJobApplication]);
        Permission::create(['guard_name' => 'sanctum','name' => $GetStatusDriverJobApplication]);
        Permission::create(['guard_name' => 'sanctum','name' => $SendUpdateDriverInfoApplication]);
        Permission::create(['guard_name' => 'sanctum','name' => $GetAllUpdateDriverInfoApplication]);
        Permission::create(['guard_name' => 'sanctum','name' => $ReviewUpdateDriverInfoApplication]);
        Permission::create(['guard_name' => 'sanctum','name' => $CreateCategory]);
        Permission::create(['guard_name' => 'sanctum','name' => $UpdateCategory]);
        Permission::create(['guard_name' => 'sanctum','name' => $DeleteCategory]);
        Permission::create(['guard_name' => 'sanctum','name' => $GetAllCategories]);
        Permission::create(['guard_name' => 'sanctum','name' => $ShowProfile]);
        Permission::create(['guard_name' => 'sanctum','name' => $UpdateProfile]);
        Permission::create(['guard_name' => 'sanctum','name' => $CreateRole]);
        Permission::create(['guard_name' => 'sanctum','name' => $UpdateRole]);
        Permission::create(['guard_name' => 'sanctum','name' => $DeleteRole]);
        Permission::create(['guard_name' => 'sanctum','name' => $GetAllRoles]);
        Permission::create(['guard_name' => 'sanctum','name' => $GetAllPermissions]);
        Permission::create(['guard_name' => 'sanctum','name' => $AddPermissionToRole]);
        Permission::create(['guard_name' => 'sanctum','name' => $RemovePermissionFromRole]);
        Permission::create(['guard_name' => 'sanctum','name' => $AddPermissionToUser]);
        Permission::create(['guard_name' => 'sanctum','name' => $RemovePermissionFromUser]);
        Permission::create(['guard_name' => 'sanctum','name' => $ChangeRoleUser]);
        Permission::create(['guard_name' => 'sanctum','name' => $AddRoleUser]);
        Permission::create(['guard_name' => 'sanctum','name' => $RemoveRoleUser]);
        Permission::create(['guard_name' => 'sanctum','name' => $CreateTrip]);
        Permission::create(['guard_name' => 'sanctum','name' => $ConfirmTrip]);
        Permission::create(['guard_name' => 'sanctum','name' => $CreateCancellationReason]);
        Permission::create(['guard_name' => 'sanctum','name' => $UpdateCancellationReason]);
        Permission::create(['guard_name' => 'sanctum','name' => $DeleteCancellationReason]);
        Permission::create(['guard_name' => 'sanctum','name' => $GetAllCancellationReasons]);
        Permission::create(['guard_name' => 'sanctum','name' => $SendCancellationReason]);
        Permission::create(['guard_name' => 'sanctum','name' => $CreateRejectionReason]);
        Permission::create(['guard_name' => 'sanctum','name' => $UpdateRejectionReason]);
        Permission::create(['guard_name' => 'sanctum','name' => $DeleteRejectionReason]);
        Permission::create(['guard_name' => 'sanctum','name' => $GetAllRejectionReasons]);
        Permission::create(['guard_name' => 'sanctum','name' => $SendRejectionReason]);
        Permission::create(['guard_name' => 'sanctum','name' => $GetTripById]);
        Permission::create(['guard_name' => 'sanctum','name' => $GetAllTrips]);
        Permission::create(['guard_name' => 'sanctum','name' => $GetAllActiveTrips]);
        Permission::create(['guard_name' => 'sanctum','name' => $GetAllTripsDriverCanAccept]);
        Permission::create(['guard_name' => 'sanctum','name' => $GetUserTrips]);
        Permission::create(['guard_name' => 'sanctum','name' => $GetDriverTrips]);
        Permission::create(['guard_name' => 'sanctum','name' => $AcceptTrip]);
        Permission::create(['guard_name' => 'sanctum','name' => $StartTrip]);
        Permission::create(['guard_name' => 'sanctum','name' => $SetTrackingTrip]);
        Permission::create(['guard_name' => 'sanctum','name' => $ChargeDriverBalance]);
        Permission::create(['guard_name' => 'sanctum','name' => $GetDriverBalance]);
        Permission::create(['guard_name' => 'sanctum','name' => $ChangeStatusDriver]);
        Permission::create(['guard_name' => 'sanctum','name' => $EndTrip]);
        Permission::create(['guard_name' => 'sanctum','name' => $SendNotificationAll]);
        Permission::create(['guard_name' => 'sanctum','name' => $SendNotificationByID]);
        Permission::create(['guard_name' => 'sanctum','name' => $GetAllUsersForNotification]);
        Permission::create(['guard_name' => 'sanctum','name' => $GetAllNotificationsForUser]);




        // Create Roles and Assign Created Permissions
        $SuperAdmin = 'Super-Admin';
        $Customer = 'Customer';
        $Driver = 'Driver';





        Role::create(['guard_name' => 'sanctum','name' => $SuperAdmin])->givePermissionTo(Permission::all());
        Role::create(['guard_name' => 'sanctum','name' => $Customer])->givePermissionTo(
            $ShowProfile,
            $UpdateProfile,
            $SendJobApplication,
            $GetStatusDriverJobApplication,
            $CreateTrip,
            $ConfirmTrip,
            $GetUserTrips,
            $GetTripById,
            $SendCancellationReason,
            $SetTrackingTrip
        );
        Role::create(['guard_name' => 'sanctum','name' => $Driver])->givePermissionTo(
            $SendUpdateDriverInfoApplication,
            $GetAllTripsDriverCanAccept,
            $GetDriverTrips,
            $GetUserTrips,
            $GetTripById,
            $AcceptTrip,
            $SendCancellationReason,
            $SendRejectionReason,
            $StartTrip,
            $ShowProfile,
            $SetTrackingTrip,
            $GetDriverBalance,
            $ChangeStatusDriver,
            $EndTrip,
        );

    }
}
