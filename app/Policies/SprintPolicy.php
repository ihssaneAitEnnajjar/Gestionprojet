<?php

namespace App\Policies;

use App\Models\Sprint;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SprintPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Sprint $sprint): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
   

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Sprint $sprint): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Sprint $sprint): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Sprint $sprint): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Sprint $sprint): bool
    {
        //
    }
    public function create(User $user)
{
    return $user->role === 'manager'; // Only managers can create Sprints
}

}
