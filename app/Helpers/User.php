<?php

if (!function_exists('user')) {
    function user()
    {
        return request()->session()->has('user') && is_object($user = request()->session()->get('user')) ? $user : null;
    }
}

if (!function_exists('user_has_role')) {
    function user_has_role(array $roles)
    {
        return in_array(user()->role->name, $roles);
    }
}

if (!function_exists('user_has_access')) {
    function user_has_access(array $features)
    {
        $permission = \App\UserPermission::where('userid', user()->id)->whereHas('permission', function($query) use ($features) {
           $query->whereIn('permission', $features);
        })->count();

        return $permission > 0;
    }
}

if (!function_exists('attach_role_permissions')) {
    function attach_role_permissions(\App\User $user)
    {
        $permissions = array_map(function ($permission) use ( $user) {
            return [
                'userid' => $user->id,
                'permissionid' => $permission->id
            ];
        }, $user->role->permissions->all());

        if(!empty($permissions)) \App\UserPermission::insert( $permissions );
    }
}

if (!function_exists('roleid_by_name')) {
    function roleid_by_name($name)
    {
        $role = \App\Role::where('name', $name)->first();
        return $role != null ? $role->id : 0;
    }
}