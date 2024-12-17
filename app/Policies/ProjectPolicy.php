<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Allow super admin to view all projects
        if ( $user->hasRole('Super Admin') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewOriginal(User $user): bool
    {

        // Allow super admin to view all projects
        if ( $user->hasRole('Super Admin') ) {
            return true;
        }

        if ( $user->hasRole('Marketing') ) {
            return true;
        }

        // Allow super admin to view all projects
        if ( $user->hasRole('TSS') ) {
            return true;
        }

        return false;
    }

     /**
     * Determine whether the user can view any models.
     */
    public function viewRevised(User $user): bool
    {
        // Allow super admin to view all projects
        if ( $user->hasRole('Super Admin') ) {
            return true;
        }

        if ( $user->hasRole('TSS') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Project $project): bool
    {
        // Allow super admin to view all projects
        if ( $user->hasRole('Super Admin') ) {
            return true;
        }

        // Allow super admin to view all projects
        if ( $user->hasRole('Marketing') && $project->is_original ) {
            return true;
        }

        // Allow super admin to view all projects
        if ( $user->hasRole('TSS') ) {
            return true;
        }
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Allow super admin to view all projects
        if ( $user->hasRole('Super Admin') ) {
            return true;
        }

        if ( $user->hasRole('Marketing') ) {
            return true;
        }
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Project $project): bool
    {
         if ( $user->hasRole('Super Admin') ) {
            return true;
        }

        if ( $user->hasRole('Marketing') && $project->is_original ) {
            return true;
        }
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Project $project): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Project $project): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Project $project): bool
    {
        //
    }
}
