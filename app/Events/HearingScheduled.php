<?php

namespace App\Events;

use App\Models\CaseModel;
use App\Models\Hearing;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class HearingScheduled
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public Hearing $hearing,
        public CaseModel $case,
        public ?User $scheduledBy = null
    ) {}
}
