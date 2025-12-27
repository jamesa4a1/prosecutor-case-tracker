<?php

namespace App\Events;

use App\Models\CaseModel;
use App\Models\Prosecutor;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProsecutorAssigned
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public CaseModel $case,
        public Prosecutor $prosecutor,
        public ?Prosecutor $previousProsecutor = null,
        public ?User $assignedBy = null
    ) {}
}
