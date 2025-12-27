<?php

namespace App\Policies;

use App\Models\CaseModel;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CasePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any cases.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view the case list
        return true;
    }

    /**
     * Determine whether the user can view the case.
     */
    public function view(User $user, CaseModel $case): bool
    {
        // Confidential cases only viewable by assigned prosecutor or admin
        if ($case->is_confidential) {
            return $user->isAdmin() || 
                   ($user->isProsecutor() && $case->prosecutor_id === $user->prosecutor?->id);
        }
        
        return true;
    }

    /**
     * Determine whether the user can create cases.
     */
    public function create(User $user): bool
    {
        // Admins and Clerks can create cases
        return $user->isAdmin() || $user->isClerk();
    }

    /**
     * Determine whether the user can update the case.
     */
    public function update(User $user, CaseModel $case): bool
    {
        // Admin can update any case
        if ($user->isAdmin()) {
            return true;
        }
        
        // Clerk can update any non-archived case
        if ($user->isClerk()) {
            return $case->status?->value !== 'archived';
        }
        
        // Prosecutor can only update their assigned cases
        if ($user->isProsecutor()) {
            return $case->prosecutor_id === $user->prosecutor?->id;
        }
        
        return false;
    }

    /**
     * Determine whether the user can delete the case.
     */
    public function delete(User $user, CaseModel $case): bool
    {
        // Only Admin can delete cases
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the case.
     */
    public function restore(User $user, CaseModel $case): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the case.
     */
    public function forceDelete(User $user, CaseModel $case): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the case status.
     */
    public function updateStatus(User $user, CaseModel $case): bool
    {
        return $this->update($user, $case);
    }

    /**
     * Determine whether the user can assign a prosecutor to the case.
     */
    public function assignProsecutor(User $user, CaseModel $case): bool
    {
        return $user->isAdmin() || $user->isClerk();
    }

    /**
     * Determine whether the user can view confidential information.
     */
    public function viewConfidential(User $user, CaseModel $case): bool
    {
        if (!$case->is_confidential) {
            return true;
        }
        
        return $user->isAdmin() || 
               ($user->isProsecutor() && $case->prosecutor_id === $user->prosecutor?->id);
    }

    /**
     * Determine whether the user can export the case.
     */
    public function export(User $user, CaseModel $case): bool
    {
        return $this->view($user, $case);
    }
}
