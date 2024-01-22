<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Sprint;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;



class UserSprintController
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Sprint::query()
            ->with('user') // Eager load the user relationship
            ->where('user_id', $user->id) // Filter sprints by the user's ID
            ->when($request->input('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            });
    
        $sprints = $query->get();
    
        $request->merge(['search' => $request->input('search')]);
    
        return Inertia::render('Users/Sprints/Index', [
            'managedSprints' => $sprints, 
            'filters' => $request->only(['search'])
        ]);
    }
    public function delete(Sprint $sprint)
    {
        if ($sprint->user_id === Auth::id()) {
            $sprint->delete();
            return redirect()->back()->with('success', 'Sprint deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'You are not authorized to delete this sprint.');
        }
    }
    
    public function destroy(Sprint $sprint)
    {
        $sprint->delete();
    
        return redirect()->route('users.sprints.index');
    }
    
    public function edit(Sprint $sprint)
    {
        $users = User::all();
        $projects = Project::pluck('name', 'id');
        if ($sprint->user_id === Auth::id()) {
            return Inertia::render('Users/Sprints/Edit', [
                'sprint' => $sprint,
                'projects' => $projects,
                'users' => $users,
            ]);
        }
    }
    public function create()
{
    $users = User::all();
    return Inertia::render('Users/Sprints/Create', [
        'users' => $users
    ]);}



    
public function store(Request $request)
{

$user = Auth::user();

   

     $request->validate([
        'name' => 'required|string|max:255',
        'due_date' => 'required|date',
        'status' => 'required|string',
        'priority' => 'required|string',        
    ]);

    // Create a new Sprint
    $sprint = new Sprint([
        'name' => $request->input('name'),
        'due_date' => $request->input('due_date'),
        'status' => $request->input('status'),
        'priority' => $request->input('priority'),
        'user_id' => Auth::id(), 
    ]);

   
    $sprint->save();

    return redirect()->route('users.sprints.index')->with('success', 'Sprint created successfully.');
}

    
    public function update(Request $request, Sprint $sprint)
    {
        if ($sprint->user_id === Auth::id()) {
            $request->validate([
                'name' => 'required|string|max:255',
                'due_date' => 'required|date',
                'status' => 'required|string',
                'priority' => 'required|string',
            ]);
    
            $sprint->update($request->only(['name', 'due_date', 'status', 'priority']));
    
            return redirect()->route('users.sprints.index')->with('success', 'Sprint updated successfully.');
        } else {
            return redirect()->back()->with('error', 'You are not authorized to update this sprint.');
        }
    }
    
}
