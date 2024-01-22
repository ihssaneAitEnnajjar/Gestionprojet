<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\PermissionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/users', [UserController::class, 'index']);


Route::put('/admin/permissions/{id}', [PermissionController::class, 'update'])->name('admin.permissions.update');


Route::get('/admin/permissions/{id}', [PermissionController::class, 'destroy'])->name('admin.permissions.destroy');
Route::delete('/api/admin/projects/{project}', [ProjectController::class, 'destroy'])->name('admin.projects.destroy');
