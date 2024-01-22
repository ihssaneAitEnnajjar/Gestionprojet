<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Phase;
use App\Models\Sprint;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SprintController
{
    public function index(Request $request)
    {
        $query = Sprint::query()
        ->with('user') // Eager load the user relationship
        ->when($request->input('search'), function ($query, $search) {
            $query->where('name', 'like', "%{$search}%");
        });

    $sprints = $query->get();
    $request->merge(['search' => $request->input('search')]);

        $phases = Sprint::with('user')->get();
        return inertia('Admin/Sprints/Index', ['sprints' => $sprints,
        'filters' => $request->only(['search'])]);
    }



    public function store(Request $request)
    {
        $data = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'due_date' => 'required|date',
            'status' => 'required|string|max:255',
            'priority' => 'required|string', // Add this line
            'user_id' => 'required|exists:users,id', // Change this to 'required'



        ]);

      // Inside the store method
$sprint = Sprint::create($data);

$user = User::find($data['user_id']);





        return redirect()->route('admin.sprints.index')->with('success', 'Task created successfully.');
    }


    public function create()
    {
        $users = User::all(); 
        $projects = Project::pluck('name', 'id');

        // Add logic to prepare data for the create form
        return Inertia::render('Admin/Sprints/Create', [
            'projects' => $projects,
            'users' => $users
        ]);
    }
    
    
    public function destroy(Sprint $sprint)
{
    // Delete associated tasks first
    $sprint->tasks()->delete();

    // Now delete the sprint
    $sprint->delete();

    return redirect()
        ->route('admin.sprints.index')
        ->with('success', 'Sprint and associated tasks deleted successfully.');
}



    public function edit(Sprint $sprint)
    {
        $users = User::all();
        $projects = Project::pluck('name', 'id');

        // Fetch the data needed for editing
        // You might need to fetch associated data if required
        return Inertia::render('Admin/Sprints/Edit', [
            'sprint' => $sprint,
            'projects' => $projects,
            'users' => $users,
        ]);
    }
    public function update(Request $request, Sprint $sprint)
    {
        $data = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'due_date' => 'required|date',
            'status' => 'required|string|max:255',
            'priority' => 'required|string',
            'user_id' => 'required|exists:users,id', // Change this to 'required'

        ]);

        // Update the sprint's attributes with the validated data
        $sprint->update($data);

        return redirect()->route('admin.sprints.index')->with('success', 'Sprint updated successfully.');
    }
}
