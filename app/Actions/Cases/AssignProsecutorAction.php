<?php

namespace App\Actions\Cases;

use App\Models\CaseModel;
use App\Models\Prosecutor;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AssignProsecutorAction
{
    /**
     * Assign a prosecutor to a case.
     */
    public function execute(
        CaseModel $case,
        Prosecutor $prosecutor,
        ?User $assignedBy = null,
        ?string $remarks = null
    ): CaseModel {
        return DB::transaction(function () use ($case, $prosecutor, $assignedBy, $remarks) {
            $previousProsecutor = $case->prosecutor;
            $assignedByUser = $assignedBy ?? auth()->user();

            $case->update([
                'prosecutor_id' => $prosecutor->id,
                'assigned_by' => $assignedByUser?->id,
                'assigned_at' => now(),
            ]);

            // Log the assignment in status history (as a note/event)
            $historyRemarks = $remarks ?? $this->buildAssignmentRemarks($previousProsecutor, $prosecutor);
            
            $case->statusHistories()->create([
                'old_status' => $case->status?->value,
                'new_status' => $case->status?->value,
                'remarks' => $historyRemarks,
                'changed_by' => $assignedByUser?->id,
            ]);

            return $case->fresh(['prosecutor']);
        });
    }

    /**
     * Remove prosecutor assignment from a case.
     */
    public function unassign(
        CaseModel $case,
        ?User $unassignedBy = null,
        ?string $remarks = null
    ): CaseModel {
        return DB::transaction(function () use ($case, $unassignedBy, $remarks) {
            $previousProsecutor = $case->prosecutor;
            $unassignedByUser = $unassignedBy ?? auth()->user();

            $case->update([
                'prosecutor_id' => null,
                'assigned_by' => null,
                'assigned_at' => null,
            ]);

            // Log the unassignment
            $historyRemarks = $remarks ?? "Prosecutor unassigned: {$previousProsecutor?->name}";
            
            $case->statusHistories()->create([
                'old_status' => $case->status?->value,
                'new_status' => $case->status?->value,
                'remarks' => $historyRemarks,
                'changed_by' => $unassignedByUser?->id,
            ]);

            return $case->fresh();
        });
    }

    /**
     * Reassign case to a different prosecutor.
     */
    public function reassign(
        CaseModel $case,
        Prosecutor $newProsecutor,
        ?User $reassignedBy = null,
        ?string $reason = null
    ): CaseModel {
        $previousProsecutor = $case->prosecutor;
        
        $remarks = $reason 
            ?? "Case reassigned from {$previousProsecutor?->name} to {$newProsecutor->name}";

        return $this->execute($case, $newProsecutor, $reassignedBy, $remarks);
    }

    /**
     * Bulk assign cases to a prosecutor.
     */
    public function bulkAssign(
        array $caseIds,
        Prosecutor $prosecutor,
        ?User $assignedBy = null
    ): int {
        $count = 0;
        $assignedByUser = $assignedBy ?? auth()->user();

        foreach ($caseIds as $caseId) {
            $case = CaseModel::find($caseId);
            if ($case) {
                $this->execute($case, $prosecutor, $assignedByUser);
                $count++;
            }
        }

        return $count;
    }

    /**
     * Build assignment remarks for history.
     */
    protected function buildAssignmentRemarks(?Prosecutor $previous, Prosecutor $new): string
    {
        if ($previous) {
            return "Case reassigned from {$previous->name} to {$new->name}";
        }

        return "Case assigned to {$new->name}";
    }
}
