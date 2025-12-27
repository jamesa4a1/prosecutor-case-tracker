<?php

namespace App\Events;

use App\Enums\CaseStatus;
use App\Models\CaseModel;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CaseStatusChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public CaseModel $case,
        public ?CaseStatus $oldStatus,
        public CaseStatus $newStatus,
        public ?User $changedBy = null,
        public ?string $remarks = null
    ) {}
}
