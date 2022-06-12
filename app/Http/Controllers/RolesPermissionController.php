<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesPermissionController extends Controller
{
    use GeneralTrait;
    public function create(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'name' =>'required|string',
        ]);

        Role::create(['name' => $request->name]);

        return $this->returnSuccessMessage();
    }

    public function edit(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'name' =>'required|string',
        ]);

        $role = Role::find($request->id);
        if( isset($role) )
        {
            $role->name = $request->name;
            $role->save();
            return $this->returnSuccessMessage();
        }else return $this->returnError("Role not found");

    }

    public function delete($id): \Illuminate\Http\JsonResponse
    {
        $role = Role::findById($id);
        if( isset($role) )
        {
            $role->delete();
            return $this->returnSuccessMessage();
        }else return $this->returnError("Role not found");

    }

    public function GetAllCategories(): \Illuminate\Http\JsonResponse
    {
        $categories = Category::all();
        if( isset($categories) )
        {
            return $this->returnData("get all categories",$categories);
        }else return $this->returnError("categories not found");
    }

    public function GetAllRoles(): \Illuminate\Http\JsonResponse
    {
        $roles = Role::all();
        if(isset($roles))
        {
            return $this->returnData("All Roles",$roles);
        }else return $this->returnError("Roles not found");
    }

    public function GetAllPermissions(): \Illuminate\Http\JsonResponse
    {
        $Permission = Permission::all();
        if(isset($Permission))
        {
            return $this->returnData("All Roles",$Permission);
        }else return $this->returnError("Roles not found");
    }

    public function AddPermissionToRole(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
           'role_id' => 'required|int',
           'permission_id' => 'required|int',
        ]);

        $role = Role::findById($request->role_id);
        $Permission = Permission::findById($request->permission_id);
        if(isset($role))
        {
            if(isset($Permission))
            {
                $role->givePermissionTo($Permission);
                return  $this->returnSuccessMessage();
            }else return $this->returnError('Permission not found');
        }else return $this->returnError('Role not found');
    }

    public function RemovePermissionFromRole(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'role_id' => 'required|int',
            'permission_id' => 'required|int',
        ]);

        $role = Role::findById($request->role_id);
        $Permission = Permission::findById($request->permission_id);
        if(isset($role))
        {
            if(isset($Permission))
            {
                $role->revokePermissionTo($Permission);
                return  $this->returnSuccessMessage();
            }else return $this->returnError('Permission not found');
        }else return $this->returnError('Role not found');
    }

    public function AddPermissionToUser(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'user_id' => 'required|int',
            'permission_id' => 'required|int',
        ]);

        $user = User::find($request->user_id);
        $Permission = Permission::findById($request->permission_id);
        if(isset($user))
        {
            if(isset($Permission))
            {
                $user->givePermissionTo($Permission);
                return  $this->returnSuccessMessage();
            }else return $this->returnError('Permission not found');
        }else return $this->returnError('User not found');
    }

    public function RemovePermissionFromUser(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'user_id' => 'required|int',
            'permission_id' => 'required|int',
        ]);

        $user = User::find($request->user_id);
        $Permission = Permission::findById($request->permission_id);
        if(isset($user))
        {
            if(isset($Permission))
            {
                if($user->hasDirectPermission($Permission))
                {
                    $user->revokePermissionTo($Permission);
                    return  $this->returnSuccessMessage();
                }else return $this->returnError("user not have this permission");

            }else return $this->returnError('Permission not found');
        }else return $this->returnError('User not found');
    }

    public function ChangeRoleUser(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'user_id' => 'required|int',
            'role_id' => 'required|int',
        ]);

        $user = User::find($request->user_id);
        $role = Role::findById($request->role_id);
        if(isset($user))
        {
            if(isset($role))
            {
                    $user->syncRoles($role);
                    return  $this->returnSuccessMessage();
            }else return $this->returnError('Role not found');
        }else return $this->returnError('User not found');
    }
}
