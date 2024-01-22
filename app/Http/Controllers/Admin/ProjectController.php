<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;



class ProjectController 
{
    public function index(Request $request)
{
    $query = Project::query()
        ->with('user') // Eager load the user relationship
        ->when($request->input('search'), function ($query, $search) {
            $query->where('name', 'like', "%{$search}%");
        });

    $projects = $query->get();

    // Append the 'search' parameter to pagination links
    $request->merge(['search' => $request->input('search')]);

    // Pass the projects data to the 'Admin/Users/Index' Inertia view
    return inertia('Admin/Projects/Index', [
        'projects' => $projects,
        'filters' => $request->only(['search'])
    ]);
}

    public function create()
    {
        $users = User::all(); // Fetch the list of users

        return Inertia::render('Admin/Projects/Create',[
            'users' => $users
        ]);
    }
    public function store(Request $request)
{
    $data=$request->validate([
        'name' => 'required|string|max:255',
        'start_date' => 'required|date',
        'due_date' => 'required|date',
        'status' => 'required|string|max:255',
        'user_id' => 'required|exists:users,id', 
    ]);

     Project::create($data);

    // Get the currently logged-in admin user and associate the project
     User::find($data['user_id']);
   // $user->projects()->attach($project);
    

    return redirect()->route('admin.projects.index')->with('success', 'Le projet a été créé avec succès.');
}

    public function edit(Project $project)
    {            $users = User::all();

        return Inertia::render('Admin/Projects/Edit', ['project' => $project,
        'users' => $users,
    
    ]);
    }
    public function update(Request $request, Project $project)
    {
        $project->update($request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'due_date' => 'nullable|date',
            'user_id' => 'required|exists:users,id',
            'status' => 'nullable|string|max:255',
            // Add other fields as needed...
        ]));



        return redirect()->route('admin.projects.index');
    }
    public function destroy(Project $project)
{
    // Delete the project
    $project->delete();

    // Respond with a success message or status
    return redirect()->route('admin.projects.index');
}
public function getLastProject()
{
   $user = Auth::user();

   $lastProjects = $user->projects()
    ->latest('created_at')
    ->take(3)
    ->get();
return response()->json($lastProjects);
}

}