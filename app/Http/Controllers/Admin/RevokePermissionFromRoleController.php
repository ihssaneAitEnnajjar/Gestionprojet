<?php

namespace App\Http\Controllers\Admin;

use Spatie\Permission\Models\Role;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Permission;


class RevokePermissionFromRoleController 
{
    public function __invoke(Role $role, Permission $permission):RedirectResponse
    {
        $role -> revokePermissionTo($permission);
        return back();
    }
}
