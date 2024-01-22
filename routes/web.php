<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\User\TasksController;
use App\Http\Controllers\Admin\SprintController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\AdminUsersController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\User\UserProjectController;
use App\Http\Controllers\Admin\RemoveRoleFromUserController;
use Spatie\Permission\Models\Role;// Corrected use statement
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\RevokePermissionFromRoleController;
use App\Http\Controllers\Admin\RevokePermissionFromUserController;
use App\Http\Controllers\User\UserSprintController;
use App\Http\Controllers\User\UserTasksController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::middleware(['guest'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('login'); // Redirect to the login page
    });
});

// Route::get('/dashboard', [UserProjectController::class, 'LastProject']);
Route::get('/api/get-last-projects/', [UserProjectController::class, 'getLastProject']);



Route::get('/cards', function () {
    return Inertia::render('Card');
})->name('Card');


Route::get('/dashboard', function() {
   
})
    ->middleware(['auth', 'verified', 'checkNonAdmin'])->name('dashboard');

//Route::get('users/tasks',[TasksController::class,'index'])->name('users.tasks.index');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard',[UserController::class,'index'])->name('users.index');

    Route::get('/users/projects', [UserProjectController::class, 'index'])->name('users.projects.index');

    Route::get('/users/tasks/create', [UserTasksController::class, 'create'])->name('users.tasks.create');
    Route::get('/users/tasks/{task}/edit', [UserTasksController::class, 'edit'])->name('users.tasks.edit');
   
    Route::get('/users/tasks', [UserTasksController::class, 'index'])->name('users.tasks.index');
    Route::put('/users/tasks/{task}/update', [UserTasksController::class, 'update'])->name('users.tasks.update');
    Route::delete('/users/tasks/{task}', [UserTasksController::class, 'destroy'])->name('users.tasks.destroy');


    Route::put('/users/tasks/{task}', [UserTasksController::class, 'updateStatus'])->name('users.tasks.update_status');

    Route::get('/users/sprints', [UserSprintController::class, 'index'])->name('users.sprints.index');
  // Route::get('/users/tasks', [UserTasksController::class, 'create'])->name('users.tasks.create');

    Route::delete('/users/sprints/{sprint}', [UserSprintController::class, 'destroy'])->name('users.sprints.destroy');

    Route::get('/users/projects/create', [UserProjectController::class, 'create'])->name('users.projects.create')->middleware('permission:create project');

    Route::get('/users/projects/{project}/edit', [UserProjectController::class, 'edit'])->name('users.projects.edit')->middleware('permission:Edit project');
    Route::post('/users/projects', [UserProjectController::class, 'store'])->name('users.projects.store')->middleware('permission:create project');
    Route::delete('/users/projects/{project}', [UserProjectController::class, 'destroy'])->name('users.projects.destroy')->middleware('permission:delete project');
     Route::put('/users/projects/{project}', [UserProjectController::class, 'update'])->name('users.projects.update')->middleware('permission:create project');
     Route::post('/users/sprints', [UserSprintController::class, 'store'])->name('users.sprints.store')->middleware('permission:create sprint');
     Route::get('/users/sprints/create', [UserSprintController::class, 'create'])->name('users.sprints.create')->middleware('permission:create sprint');
     Route::get('/users/sprints/{sprint}/edit', [UserSprintController::class, 'edit'])->name('users.sprints.edit')->middleware('permission:edit sprint');
    
     Route::put('/users/sprints/{sprint}', [UserSprintController::class, 'update'])->name('users.sprints.update');

Route::post('/users/tasks/store', [UserTasksController::class, 'store'])->name('users.tasks.store');



    
});





Route::middleware('auth')->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::middleware(['auth','role:admin'])->group(function (){
Route::get('/admin',[AdminController::class,'index'])->name('admin.index');
Route::get('/admin/users', [AdminUsersController::class, 'index'])->name('admin.users.index');
Route::get('/admin/roles', [RoleController::class, 'index'])->name('admin.roles.index');
Route::get('/admin/permissions', [PermissionController::class, 'index'])->name('admin.permissions.index');
Route::post('/admin/roles/store', [RoleController::class, 'store'])->name('admin.roles.store');
Route::get('/admin/roles/create', [RoleController::class, 'create'])->name('admin.roles.create');
Route::get('/admin/roles/{id}/edi', [RoleController::class, 'edi'])->name('admin.roles.edi');
Route::put('/admin/roles/{id}', [RoleController::class, 'update'])->name('admin.roles.update');
Route::get('/admin/roles/{id}', [RoleController::class, 'destroy'])->name('admin.roles.destroy');

Route::get('/admin/permissions/create', [PermissionController::class, 'create'])->name('admin.permissions.create');
Route::post('/admin/permissions/store', [PermissionController::class, 'store'])->name('admin.permissions.store');
Route::put('/admin/permissions/{id}', [PermissionController::class, 'update'])->name('admin.permissions.update');
Route::get('/admin/permissions/{id}', [PermissionController::class, 'destroy'])->name('admin.permissions.destroy');
Route::get('/admin/permissions/edi/{id}', [PermissionController::class, 'edi'])->name('admin.permissions.edi');
Route::get('/admin/roles/{id}', [RoleController::class, 'destroy'])->name('admin.roles.destroy');
Route::get('/admin/projects',[ProjectController::class,'index'])->name('admin.projects.index');
Route::get('/admin/users/create', [AdminUsersController::class, 'create'])->name('admin.users.create');
Route::post('/admin/users/store', [AdminUsersController::class, 'store'])->name('admin.users.store');
Route::get('/admin/users/{id}', [AdminUsersController::class, 'destroy'])->name('admin.users.destroy');
Route::get('/admin/users/{id}/edit', [AdminUsersController::class, 'edit'])->name('admin.users.edit');
Route::get('/admin/projects/create', [ProjectController::class, 'create'])->name('admin.projects.create');
Route::post('/admin/projects/store', [ProjectController::class, 'store'])->name('admin.projects.store');
Route::get('/admin/projects/{project}/edit', [ProjectController::class, 'edit'])->name('admin.projects.edit');
Route::put('/admin/projects/{project}', [ProjectController::class, 'update'])->name('admin.projects.update');
Route::put('/admin/users/{id}', [AdminUsersController::class, 'update'])->name('admin.users.update');
Route::delete('admin/projects/{project}', [ProjectController::class, 'destroy'])->name('admin.projects.destroy');
Route::delete('/admin/roles/{role}/permissions/{permission}', [RevokePermissionFromRoleController::class, '__invoke'])->name('admin.roles.permissions.destroy');
Route::delete('/users/{user}/permissions/{permission}', RevokePermissionFromUserController::class,' __invoke')
    ->name('admin.users.permissions.destroy');
    Route::delete('/users/{user}/roles/{role}', RemoveRoleFromUserController::class,'__invoke')
    ->name('admin.users.roles.destroy');

Route::get('admin/projects/{id}', [ProjectController::class, 'destroy'])->name('admin.projects.destroy');
Route::get('/admin/tasks', [TaskController::class, 'index'])->name('admin.tasks.index');

// sprints
Route::get('/admin/sprints', [SprintController::class, 'index'])->name('admin.sprints.index');
Route::get('/admin/tasks/create', [TaskController::class, 'create'])->name('admin.tasks.create');
Route::get('/admin/sprints/create', [SprintController::class, 'create'])->name('admin.sprints.create');
Route::post('/admin/tasks/store', [TaskController::class, 'store'])->name('admin.tasks.store');
Route::post('/admin/sprints/store', [SprintController::class, 'store'])->name('admin.sprints.store');
Route::get('/admin/sprints/{sprint}/edit', [SprintController::class, 'edit'])->name('admin.sprints.edit');
Route::delete('/admin/sprints/{sprint}', [SprintController::class, 'destroy'])->name('admin.sprints.destroy');
Route::put('/admin/sprints/{sprint}', [SprintController::class, 'update'])->name('admin.sprints.update');


// tasks
Route::get('/admin/tasks/{task}/edit', [TaskController::class, 'edit'])->name('admin.tasks.edit');
Route::put('/admin/tasks/{task}', [TaskController::class, 'update'])->name('admin.tasks.update');
Route::delete('admin/tasks/{task}', [TaskController::class, 'destroy'])->name('admin.tasks.destroy');































}

);



require __DIR__.'/auth.php';
