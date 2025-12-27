<?php

namespace App\Listeners;

use App\Events\ProsecutorAssigned;
use App\Notifications\CaseAssignedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyProsecutorOfAssignment implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(ProsecutorAssigned $event): void
    {
        // Get the prosecutor's associated user
        $user = $event->prosecutor->user;

        if ($user && $user->email) {
            // Send notification to the assigned prosecutor
            $user->notify(new CaseAssignedNotification(
                $event->case,
                $event->assignedBy
            ));
        }
    }
}
