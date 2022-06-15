<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

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
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
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
        ///Manage Trips
        $CreateTrip = 'Create-Trip';
        $ConfirmTrip = 'Confirm-Trip';
        ///Manage Reasons
        $CreateReason = 'Create-Reason';
        $UpdateReason = 'Update-Reason';
        $DeleteReason = 'Delete-Reason';
        $GetAllReasons = 'Get-All-Reasons';





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
        Permission::create(['guard_name' => 'sanctum','name' => $CreateTrip]);
        Permission::create(['guard_name' => 'sanctum','name' => $ConfirmTrip]);
        Permission::create(['guard_name' => 'sanctum','name' => $CreateReason]);
        Permission::create(['guard_name' => 'sanctum','name' => $UpdateReason]);
        Permission::create(['guard_name' => 'sanctum','name' => $DeleteReason]);
        Permission::create(['guard_name' => 'sanctum','name' => $GetAllReasons]);



        // Create Roles and Assign Created Permissions
        $SuperAdmin = 'Super-Admin';
        $Customer = 'Customer';
        $Driver = 'Driver';





        Role::create(['guard_name' => 'sanctum','name' => $SuperAdmin])->givePermissionTo(Permission::all());
        Role::create(['guard_name' => 'sanctum','name' => $Customer])->givePermissionTo(
            $ShowProfile,
            $UpdateProfile,
            $SendJobApplication,
            $CreateTrip,
            $ConfirmTrip
        );
        Role::create(['guard_name' => 'sanctum','name' => $Driver])->givePermissionTo(
            $SendUpdateDriverInfoApplication,
            $GetStatusDriverJobApplication
        );

    }
}
