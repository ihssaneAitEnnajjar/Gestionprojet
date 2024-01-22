<?php

namespace App\Http\Controllers\User;


use App\Models\Task;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Sprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;






class UserTasksController
{


    public function index(Request $request)
    {
        $canSeeProjects = $request->user()->can('view projects');
       
        $query = Task::query()
            ->with('user') // Eager load the user relationship
            // Filter projects by the user's ID
            ->when(!$canSeeProjects, function($query) {
                $query->where('user_id', Auth::user()->id);
            })
            ->when($request->input('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            });
    
        $tasks = $query->get();
    
        $request->merge(['search' => $request->input('search')]);

        return Inertia::render('Users/Tasks/Index', [
            'managedTasks' => $tasks, // or whatever variable name you're using
        ]);
    }

    

    public function updateStatus(Task $task, Request $request)
    {
        $request->validate([
            'status' => [
                'required',
                // 'in:To Do,In Progress,Not Started,In Review,Completed',
            ],
        ]);

        $task->update(['status' => $request->status]);
        return [
            'message' => 'success'
        ];
    }
    public function create()
    {
        $users = User::all(); // Fetch the list of users
        $sprints = Sprint::pluck('id', 'name');
    
    
        return Inertia::render('Users/Tasks/Create', [
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


        
        return redirect()->route('users.tasks.index')->with('success', 'Task created successfully.');
        
        }
        public function edit(Task $task)
        {
            $users = User::all();
            $sprints = Sprint::pluck('name', 'id'); // Pluck the name as the display value
        
            return Inertia::render('Users/Tasks/Edit', [
                'task' => $task,
                'users' => $users,
                'sprints' => $sprints,
            ]);
        }
        
public function update(Request $request, Task $task)
{
   

    $task->update($request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'start_date' => 'required|date',
        'due_date' => 'required|date',
        'user_id' => 'required|exists:users,id',
        'status' => 'required|string|max:255',
        'sprint_id' => 'required|exists:sprints,id',
    ]));



    return redirect()->route('users.tasks.index');
}
public function destroy(Task $task)
{
    // Perform any additional checks, such as user authorization, before deleting the task.

    try {
        // Delete the task
        $task->delete();

        // Optionally, you can return a success response or a message
        return redirect()->route('users.tasks.index')->with('success', 'Task deleted successfully');
    } catch (\Exception $e) {
        // Handle any exceptions or errors that occur during the deletion process.
        // You can return an error response or redirect back with an error message.
        return redirect()->route('users.tasks.index')->with('error', 'Error deleting task: ' . $e->getMessage());
    }
}
}
