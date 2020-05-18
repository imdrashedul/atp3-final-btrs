<?php

namespace App\Http\Controllers;

use App\Role;
use App\RolePermission;
use App\User;
use App\UserPermission;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ManageRole extends Controller
{
    public function index()
    {
        $roles = Role::where('name', '!=', 'super')->orderBy('id', 'DESC')->get();
        return view('system.managerole.index', ['roles' => $roles]);
    }

    public function permissionuser($id, Request $request)
    {
        if($id!=user()->id)
        {
            $user = User::find($id);
            if($user!=null)
            {
                if(user()->role->name=='super' || (
                    user_has_access(['manage'.$user->role->name.'permission']) && (
                        user()->role->name!='busmanager' || (
                            user()->role->name=='busmanager' && user()->id == $user->operator->id
                        )
                    )
                ))
                {
                    return view('system.managerole.userpermission', [
                        'user' => $user,
                        'rolePermissions' => $user->role->permissions->all(),
                        'userPermissions' => array_map(function ($permission){
                            return $permission->permissionid;
                        }, $user->permissions->all())
                    ]);
                }
            }
        }

        return redirect()->back();
    }

    public function permissionusermodify($id, Request $request)
    {
        if($id!=user()->id)
        {
            $user = User::find($id);
            if($user!=null)
            {
                if(user()->role->name=='super' || (
                    user_has_access(['manage'.$user->role->name.'permission']) && (
                        user()->role->name!='busmanager' || (
                            user()->role->name=='busmanager' && user()->id == $user->operator->id
                        )
                    )
                ))
                {
                    $permissions = $request->permissions ?? [];
                    $userPermissions = array_map(function ($permission){
                        return $permission->permissionid;
                    }, $user->permissions->all());
                    $newPermissions = array_diff($permissions, $userPermissions);
                    $user->permissions()->whereNotIn('permissionid', $permissions)->delete();

                    if(!empty($newPermissions)) {
                        $newPermissions = array_map(function ($newPermission) use ($user) {
                            return [
                                'userid' => $user->id,
                                'permissionid' => $newPermission
                            ];
                        }, $newPermissions);

                        UserPermission::insert($newPermissions);
                    }

                    $request->session()->flash('status_success', 'Permission Modified Successfully');
                }
            }
        }

        return redirect()->back();
    }

    public function permission($id, Request $request)
    {
        $role = Role::find($id);

        if($role!=null)
        {
           return view('system.managerole.permission', ['role' => $role]);
        }

        $request->session()->flash('status_error', 'Role Not Found');
        return redirect()->back();

    }

    public function permissionadd($id, Request $request)
    {
        $role = Role::find($id);

        if($role!=null)
        {
            return view('system.managerole.permissionadd', ['role' => $role]);
        }

        $request->session()->flash('status_error', 'Role Not Found');
        return redirect()->back();
    }

    public function permissionaddpost($id, Request $request)
    {
        $role = Role::find($id);

        if($role!=null)
        {
            $request->validate([
                'key' => [
                    'required',
                    Rule::unique('roles_permission', 'permission')->where(function ($query) use ($id) {
                        return $query->where('roleid', $id);
                    })
                ],
                'detail' => 'required'
            ]);

            $permission = RolePermission::create([
                'roleid' => $id,
                'permission' =>  $request->key,
                'details' => $request->detail
            ]);

            if($permission->id)
            {
                $request->session()->flash('status_success', 'Permission '.$request->key.' added successfully');
                return redirect()->route('managerole_permission', ['id' => $id]);
            }

            $request->session()->flash('status_error', 'Something went wrong. Please try again later');
            return redirect()->back();
        }

        $request->session()->flash('status_error', 'Role Not Found');
        return redirect()->back();
    }

    public function permissionedit($id, Request $request)
    {
        $permission = RolePermission::find($id);

        if($permission!=null)
        {
            return view('system.managerole.permissionedit', ['permission' => $permission]);
        }

        $request->session()->flash('status_error', 'Permission Not Found');
        return redirect()->back();
    }

    public function permissioneditpost($id, Request $request)
    {
        $permission = RolePermission::find($id);

        if($permission!=null)
        {
            $request->validate([
                'key' => [
                    'required',
                    Rule::unique('roles_permission', 'permission')->where(function ($query) use ($id) {
                        return $query->where('roleid', $id);
                    })->ignore($permission->id)
                ],
                'detail' => 'required'
            ]);

            $permission->permission = $request->key;
            $permission->details = $request->detail;

            if($permission->save())
            {
                $request->session()->flash('status_success', 'Permission '.$permission->permission.' modified successfully');
                return redirect()->route('managerole_permission', ['id' => $permission->role->id]);
            }

            $request->session()->flash('status_error', 'Please make any changes to update');
            return redirect()->back();
        }

        $request->session()->flash('status_error', 'Permission Not Found');
        return redirect()->back();
    }

    public function permissiondelete($id, Request $request)
    {
        $permission = RolePermission::find($id);

        if($permission!=null)
        {
            if($permission->delete())
            {
                $request->session()->flash('status_success', 'Permission '.$permission->permission.' removed successfully');
                return redirect()->back();
            }
        }

        $request->session()->flash('status_error', 'Permission Not Found');
        return redirect()->back();
    }
}
