<?php

namespace App\Policies;

use App\Models\Hearing;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class HearingPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any hearings.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the hearing.
     */
    public function view(User $user, Hearing $hearing): bool
    {
        // Check if user can view the associated case
        return $user->can('view', $hearing->case);
    }

    /**
     * Determine whether the user can create hearings.
     */
    public function create(User $user): bool
    {
        // All authenticated users can schedule hearings
        return true;
    }

    /**
     * Determine whether the user can update the hearing.
     */
    public function update(User $user, Hearing $hearing): bool
    {
        // Admin can update any hearing
        if ($user->isAdmin()) {
            return true;
        }
        
        // Clerk can update any hearing
        if ($user->isClerk()) {
            return true;
        }
        
        // Prosecutor can only update hearings for their assigned cases
        if ($user->isProsecutor()) {
            return $hearing->case->prosecutor_id === $user->prosecutor?->id;
        }
        
        return false;
    }

    /**
     * Determine whether the user can delete the hearing.
     */
    public function delete(User $user, Hearing $hearing): bool
    {
        // Only Admin and Clerk can delete hearings
        return $user->isAdmin() || $user->isClerk();
    }

    /**
     * Determine whether the user can mark the hearing as completed.
     */
    public function complete(User $user, Hearing $hearing): bool
    {
        return $this->update($user, $hearing);
    }

    /**
     * Determine whether the user can postpone the hearing.
     */
    public function postpone(User $user, Hearing $hearing): bool
    {
        return $this->update($user, $hearing);
    }

    /**
     * Determine whether the user can cancel the hearing.
     */
    public function cancel(User $user, Hearing $hearing): bool
    {
        return $user->isAdmin() || $user->isClerk();
    }
}
