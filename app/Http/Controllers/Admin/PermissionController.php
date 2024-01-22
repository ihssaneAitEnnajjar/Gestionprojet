<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Permission;
use App\Http\Resources\PermissionResource;
use App\Http\Requests\CreatePermissionRequest;
use Illuminate\Support\Facades\Log;


class PermissionController
{
    public function index(Request $request):Response{
       
    $searchTerm = $request->input('search');

    $permissions = Permission::query();

    if ($searchTerm !== null) {
        $permissions->where('name', 'like', '%' . $searchTerm . '%');
    }

    $permissions = $permissions->get();
    
        return Inertia::render('Admin/Permissions/Index', [
            'permissions' => $permissions,
            'filters' => $request->only(['search']),
        ]);

       
}
            public function create():Response
            {
                return inertia::render('Admin/Permissions/Create');


            }
            public function store(CreatePermissionRequest $request):RedirectResponse
            {
                Permission::create($request->validated());
                return redirect()->route('admin.permissions.index');

            }
            public function edi($id): Response
            {
                $permission = Permission::findOrFail($id);
                return Inertia::render('Admin/Permissions/Edit', [
                    'permission' => new PermissionResource($permission)
                ]);
            }

           // app/Http/Controllers/PermissionController.php



    // Other methods...

    public function update(Request $request, $id)
    {
        // Find the permission by its ID
        $permission = Permission::findOrFail($id);

        // Validate the incoming request data using your validation rules
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $id,
            // Add any other validation rules you need
        ]);

        // Update the permission with the validated data
        $permission->update([
            'name' => $request->name,
        ]);

        // Return a JSON response indicating success
        return redirect()->route('admin.permissions.index');
    }




            
            
            public function destroy(string $id){
                $role =Permission::findById($id);
                $role-> delete();
                return redirect()->route('admin.permissions.index');



            }

 
           
        }
