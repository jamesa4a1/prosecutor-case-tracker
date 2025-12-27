<?php

namespace App\Listeners;

use App\Events\CaseStatusChanged;
use App\Models\AuditLog;
use App\Enums\AuditEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogCaseStatusChanged implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(CaseStatusChanged $event): void
    {
        AuditLog::create([
            'user_id' => $event->changedBy?->id ?? auth()->id(),
            'auditable_type' => get_class($event->case),
            'auditable_id' => $event->case->id,
            'event' => AuditEvent::Updated,
            'properties' => [
                'case_number' => $event->case->case_number,
                'old_status' => $event->oldStatus?->value,
                'new_status' => $event->newStatus->value,
                'remarks' => $event->remarks,
            ],
            'ip_address' => request()?->ip(),
            'user_agent' => request()?->userAgent(),
        ]);
    }
}
