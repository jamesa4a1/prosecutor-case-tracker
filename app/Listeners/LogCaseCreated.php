<?php

namespace App\Listeners;

use App\Events\CaseCreated;
use App\Models\AuditLog;
use App\Enums\AuditEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogCaseCreated implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(CaseCreated $event): void
    {
        AuditLog::create([
            'user_id' => $event->createdBy?->id ?? auth()->id(),
            'auditable_type' => get_class($event->case),
            'auditable_id' => $event->case->id,
            'event' => AuditEvent::Created,
            'properties' => [
                'case_number' => $event->case->case_number,
                'title' => $event->case->title,
                'status' => $event->case->status?->value,
            ],
            'ip_address' => request()?->ip(),
            'user_agent' => request()?->userAgent(),
        ]);
    }
}
