<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CreateRoleRequest;
use App\Http\Resources\PermissionResource;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Resources\RoleResource;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Log;

class RoleController
{
    public function index(Request $request): Response
    {
         // Initialize the query builder without any filters
         $query = Role::query();

         // Check if a search term was provided
         if ($request->has('search')) {
             // Apply the 'name' filter if a search term is present
             $search = $request->input('search');
             $query->where('name', 'like', "%{$search}%");
         }
 
         // Retrieve the roles
         $roles = $query->get();
         Log::info("Search Term: " . $request->input('search'));
 
         return Inertia::render('Admin/Roles/Index', [
             'roles' => $roles,
             'filters' => $request->only(['search']),
             'permissions' => PermissionResource::collection(Permission::all())
         ]);
     }
    

    public function create(): Response
    {

        return Inertia::render('Admin/Roles/Create', ['permissions' => PermissionResource::collection(Permission::all())]);
    }
    public function store(CreateRoleRequest $request)
    {

        $role = Role::create(['name' => $request->name]);
        if ($request->has('permissions')) {
            $role->syncPermissions($request->input('permissions'));
        }
        return redirect()->route('admin.roles.index');
    }


    public function update(Request $request, $id): RedirectResponse
    {
        $role = Role::findById($id);

        // Validate the incoming request data using your CreateRoleRequest rules

        // Update the role with the validated data
        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->input('permissions'));

        // Redirect back to the index page after the update
        return redirect()->route('admin.roles.index');
    }


    public function edi(string $id): Response
    {
        $role = Role::findById($id);
        $role->load('permissions');


        return Inertia::render('Admin/Roles/Edit', [
            'role' => $role,
            'permissions' => PermissionResource::collection(Permission::all())
        ]);
    }


    public function destroy(string $id)
    {
        $role = Role::findById($id);
        $role->delete();
        return back();
    }
}
