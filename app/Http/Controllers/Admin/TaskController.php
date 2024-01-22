<?php

namespace App\Http\Controllers\Admin;

use App\Models\Sprint;
use App\Models\Task;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;

class TaskController 
{
    public function index(Request $request)
    {
        // Fetch all projects from the database using the Project model
        $tasks = Task::all();
        $tasks = Task::with('user')->get();
        $query = Task::query()
        ->with('user') // Eager load the user relationship
        ->when($request->input('search'), function ($query, $search) {
            $query->where('name', 'like', "%{$search}%");
        });

    $tasks = $query->get();

    // Append the 'search' parameter to pagination links
    $request->merge(['search' => $request->input('search')]);

        // Pass the projects data to the 'Admin/Users/Index' Inertia view
        return inertia('Admin/Tasks/Index', [
            'tasks' => $tasks,
            'filters' => $request->only(['search'])

        ]);
    }
    public function create()
{
    $users = User::all(); // Fetch the list of users
    $sprints = Sprint::pluck('id', 'name');


    return Inertia::render('Admin/Tasks/Create', [
        'users' => $users, // Pass the list of users to the view
        'sprints' => $sprints,
    ]);
}

public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'due_date' => 'required|date',
            'user_id' => 'required|exists:users,id', // Change this to 'required'
            'status' => 'required|string|max:255',
            'sprint_id' => 'required|exists:sprints,id', // Make sure the provided sprint_id exists in the phases table

        ]);
     
        $task= Task::create($data);
            $user = User::find($data['user_id']);


        
        return redirect()->route('admin.tasks.index')->with('success', 'Task created successfully.');
        
        }
       
        public function edit(Task $task)
        {
            $users = User::all();
            $sprints = Sprint::pluck('name', 'id'); // Pluck the name as the display value
        
            return Inertia::render('Admin/Tasks/Edit', [
                'task' => $task,
                'users' => $users,
                'sprints' => $sprints,
            ]);
        }
        
public function update(Request $request, Task $task)
{
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'start_date' => 'required|date',
        'due_date' => 'required|date',
        'user_id' => 'required|exists:users,id',
        'status' => 'required|string|max:255',
        'sprint_id' => 'required|exists:sprints,id',
    ]);

    $task->update($data);

    return redirect()->route('admin.tasks.index')->with('success', 'Task updated successfully.');
}

public function destroy(Task $task)
{
    $task->delete();

    return redirect()->route('admin.tasks.index')->with('success', 'Task deleted successfully.');
}
}