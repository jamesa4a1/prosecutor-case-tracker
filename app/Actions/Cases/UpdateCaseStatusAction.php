<?php

namespace App\Actions\Cases;

use App\Enums\CaseStatus;
use App\Models\CaseModel;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UpdateCaseStatusAction
{
    /**
     * Update the status of a case.
     */
    public function execute(
        CaseModel $case,
        CaseStatus $newStatus,
        ?string $remarks = null,
        ?User $changedBy = null
    ): CaseModel {
        return DB::transaction(function () use ($case, $newStatus, $remarks, $changedBy) {
            $oldStatus = $case->status;

            // Don't create history if status hasn't changed
            if ($oldStatus === $newStatus) {
                return $case;
            }

            // Update case status
            $updateData = ['status' => $newStatus];

            // Auto-fill date_closed when case is resolved or dismissed
            if (in_array($newStatus, [CaseStatus::Resolved, CaseStatus::Dismissed])) {
                $updateData['date_closed'] = now();
            }

            // Clear date_closed if reopening
            if ($oldStatus && in_array($oldStatus, [CaseStatus::Resolved, CaseStatus::Dismissed, CaseStatus::Archived])) {
                if ($newStatus->isActive()) {
                    $updateData['date_closed'] = null;
                }
            }

            $case->update($updateData);

            // Create status history record
            $case->statusHistories()->create([
                'old_status' => $oldStatus?->value,
                'new_status' => $newStatus->value,
                'remarks' => $remarks,
                'changed_by' => $changedBy?->id ?? auth()->id(),
            ]);

            return $case->fresh();
        });
    }

    /**
     * Validate status transition.
     */
    public function canTransition(CaseStatus $from, CaseStatus $to): bool
    {
        // Define allowed transitions
        $allowedTransitions = [
            CaseStatus::Pending->value => [
                CaseStatus::UnderInvestigation->value,
                CaseStatus::ForFiling->value,
                CaseStatus::Dismissed->value,
            ],
            CaseStatus::UnderInvestigation->value => [
                CaseStatus::ForFiling->value,
                CaseStatus::Pending->value,
                CaseStatus::Dismissed->value,
            ],
            CaseStatus::ForFiling->value => [
                CaseStatus::Filed->value,
                CaseStatus::UnderInvestigation->value,
                CaseStatus::Dismissed->value,
            ],
            CaseStatus::Filed->value => [
                CaseStatus::ForResolution->value,
                CaseStatus::Resolved->value,
                CaseStatus::Dismissed->value,
            ],
            CaseStatus::ForResolution->value => [
                CaseStatus::Resolved->value,
                CaseStatus::Filed->value,
                CaseStatus::Dismissed->value,
            ],
            CaseStatus::Resolved->value => [
                CaseStatus::Archived->value,
                CaseStatus::ForResolution->value, // Reopen
            ],
            CaseStatus::Dismissed->value => [
                CaseStatus::Archived->value,
                CaseStatus::Pending->value, // Reopen
            ],
            CaseStatus::Archived->value => [
                CaseStatus::Pending->value, // Restore
            ],
        ];

        $allowed = $allowedTransitions[$from->value] ?? [];
        return in_array($to->value, $allowed);
    }

    /**
     * Get available next statuses for a case.
     */
    public function getAvailableStatuses(CaseModel $case): array
    {
        $currentStatus = $case->status;
        $available = [];

        foreach (CaseStatus::cases() as $status) {
            if ($status !== $currentStatus && $this->canTransition($currentStatus, $status)) {
                $available[] = $status;
            }
        }

        return $available;
    }
}
