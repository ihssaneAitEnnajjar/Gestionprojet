<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\Project; // Make sure to adjust the model import


class UserProjectController
{
    public function index(Request $request)
    {
        $canSeeProjects = $request->user()->can('view projects');
       
    $query = Project::query()
        ->with('user') // Eager load the user relationship
        // Filter projects by the user's ID
        ->when(!$canSeeProjects, function($query) {
            $query->where('user_id', Auth::user()->id);
        })
        ->when($request->input('search'), function ($query, $search) {
            $query->where('name', 'like', "%{$search}%");
        });

    $projects = $query->get();

    $request->merge(['search' => $request->input('search')]);

    return Inertia::render('Users/Projects/Index', [
        'managedProjects' => $projects,
        'filters' => $request->only(['search'])
    ]);
    }
    public function create()
    {
        $users = User::all();
        return Inertia::render('Users/Projects/Create', [
            'users' => $users
        ]);}
        public function store(Request $request)
        {
            $request->validate([
                'name' => 'required|string|max:255',
                'start_date' => 'required|date',
                'due_date' => 'nullable|date',
                'user_id' => 'required|exists:users,id',
                'status' => 'nullable|string|max:255',
            ]);
    
            $project = new Project([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'start_date' => $request->input('start_date'),
                'due_date' => $request->input('due_date'),
                'user_id' => $request->input('user_id'),
                'status' => $request->input('status'),
            ]);
    
            $project->save();
    
            return redirect()->route('users.projects.index')->with('success', 'Project created successfully.');
        }
        public function edit(Project $project)
        {
            $users = User::all();
          
                return Inertia::render('Users/Projects/Edit', [
                    'project' => $project, // Use 'project' instead of 'projects'
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
            ]));
    
    
    
            return redirect()->route('users.projects.index');
        }

    
    public function delete(Project $project)
    {
        // Check if the user is authorized to delete the project
        if ($project->user_id === Auth::id()) {
            $project->delete();
            return redirect()->back()->with('success', 'Project deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'You are not authorized to delete this project.');
        }
    }

    //public function destroy(Project $project)
    //{
        //return $this->delete($project);
   // }
   public function destroy(Project $project)
    {
        // Delete the project
        $project->delete();
    
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

