<?php

use Illuminate\Support\Facades\Auth;

// if (!function_exists('hasAnyPermission')) {
    function hasAnyPermission($permissions)
    {
        if (!Auth::check()) return false;

        foreach ($permissions as $permission) {
            if (Auth::user()->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }
// }
function canDo($permission)
{
    return Auth::check() && Auth::user()->hasPermission($permission);
}
?>