<?php

namespace App\Http\Controllers\Admin;

use Rules\Password;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;
use App\Http\Resources\RoleResource;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use App\Http\Resources\PermissionResource;
use Symfony\Component\HttpFoundation\RedirectResponse;


class AdminUsersController
{
    function index(Request $request)
    {$searchTerm = $request->input('search');

        // Query all users
        $usersQuery = User::query();
    
        // If a search term is provided, filter users by name
        if ($searchTerm) {
            $usersQuery->where('name', 'like', "%{$searchTerm}%");
        }
    
        // Get the filtered users
        $users = $usersQuery->get();
    
        return inertia('Admin/Users/Index', [
            'users' => $users,
            'filters' => $request->only(['search']),
        ]);
    }
    public function create()
    {
        $roles = Role::with('permissions')->get()->keyBy('name');

        return Inertia::render(
            'Admin/Users/Create',
            compact('roles'),
        );
    }
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:' . User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->syncRoles($request->role);

        return redirect()->route('admin.users.index');
    }
    // UserController.php

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $role = $user->roles()->first();
        $roles = Role::with('permissions')->get()->keyBy('name');

        return Inertia::render(
            'Admin/Users/Edit',
            compact('user', 'roles', 'role'),
        );
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', 'string'],
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $user->syncRoles($request->role);

        return redirect()->route('admin.users.index');
    }




    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('admin.users.index');
    }
}
