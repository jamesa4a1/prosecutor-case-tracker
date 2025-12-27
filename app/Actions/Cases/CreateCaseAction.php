<?php

namespace App\Actions\Cases;

use App\DTOs\CaseData;
use App\Enums\CasePriority;
use App\Enums\CaseStatus;
use App\Models\CaseModel;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CreateCaseAction
{
    /**
     * Create a new case.
     */
    public function execute(array $data, ?User $createdBy = null): CaseModel
    {
        return DB::transaction(function () use ($data, $createdBy) {
            $case = CaseModel::create([
                'case_number' => $data['case_number'],
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'offense' => $data['offense'],
                'offense_type' => $data['offense_type'] ?? null,
                'type' => $data['type'] ?? 'Criminal',
                'status' => $data['status'] ?? CaseStatus::Pending,
                'priority' => $data['priority'] ?? CasePriority::Normal,
                'date_filed' => $data['date_filed'],
                'complainant' => $data['complainant'] ?? null,
                'accused' => $data['accused'] ?? null,
                'investigating_officer' => $data['investigating_officer'] ?? null,
                'agency_station' => $data['agency_station'] ?? null,
                'prosecutor_id' => $data['prosecutor_id'] ?? null,
                'court_branch' => $data['court_branch'] ?? null,
                'judge_name' => $data['judge_name'] ?? null,
                'is_confidential' => $data['is_confidential'] ?? false,
                'notes' => $data['notes'] ?? null,
                'created_by' => $createdBy?->id ?? auth()->id(),
            ]);

            // If prosecutor is assigned, record the assignment
            if (!empty($data['prosecutor_id'])) {
                $case->update([
                    'assigned_by' => $createdBy?->id ?? auth()->id(),
                    'assigned_at' => now(),
                ]);
            }

            // Create initial status history
            $case->statusHistories()->create([
                'old_status' => null,
                'new_status' => $case->status->value,
                'remarks' => 'Case created',
                'changed_by' => $createdBy?->id ?? auth()->id(),
            ]);

            return $case;
        });
    }

    /**
     * Create a case from DTO.
     */
    public function fromDTO(CaseData $dto, ?User $createdBy = null): CaseModel
    {
        return $this->execute($dto->toArray(), $createdBy);
    }
}
