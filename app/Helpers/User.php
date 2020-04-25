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