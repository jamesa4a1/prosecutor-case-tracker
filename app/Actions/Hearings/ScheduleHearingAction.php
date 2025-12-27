<?php

namespace App\Actions\Hearings;

use App\Enums\HearingStatus;
use App\Enums\HearingType;
use App\Models\CaseModel;
use App\Models\Hearing;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ScheduleHearingAction
{
    /**
     * Schedule a new hearing for a case.
     */
    public function execute(CaseModel $case, array $data, ?User $createdBy = null): Hearing
    {
        return DB::transaction(function () use ($case, $data, $createdBy) {
            $hearing = Hearing::create([
                'case_id' => $case->id,
                'hearing_type' => $data['hearing_type'] ?? HearingType::Preliminary,
                'status' => HearingStatus::Scheduled,
                'scheduled_date' => $data['scheduled_date'],
                'scheduled_time' => $data['scheduled_time'] ?? null,
                'venue' => $data['venue'] ?? null,
                'court_branch' => $data['court_branch'] ?? $case->court_branch,
                'judge_name' => $data['judge_name'] ?? $case->judge_name,
                'prosecutor_id' => $data['prosecutor_id'] ?? $case->prosecutor_id,
                'remarks' => $data['remarks'] ?? null,
                'created_by' => $createdBy?->id ?? auth()->id(),
            ]);

            // Update case's next hearing date
            $this->updateCaseNextHearing($case);

            return $hearing;
        });
    }

    /**
     * Reschedule an existing hearing.
     */
    public function reschedule(
        Hearing $hearing,
        string $newDate,
        ?string $newTime = null,
        ?string $reason = null
    ): Hearing {
        return DB::transaction(function () use ($hearing, $newDate, $newTime, $reason) {
            // Mark current hearing as postponed
            $hearing->update([
                'status' => HearingStatus::Postponed,
                'postponement_reason' => $reason,
                'next_hearing_date' => $newDate,
            ]);

            // Create new hearing
            $newHearing = Hearing::create([
                'case_id' => $hearing->case_id,
                'hearing_type' => $hearing->hearing_type,
                'status' => HearingStatus::Scheduled,
                'scheduled_date' => $newDate,
                'scheduled_time' => $newTime ?? $hearing->scheduled_time,
                'venue' => $hearing->venue,
                'court_branch' => $hearing->court_branch,
                'judge_name' => $hearing->judge_name,
                'prosecutor_id' => $hearing->prosecutor_id,
                'remarks' => "Rescheduled from " . $hearing->scheduled_date?->format('M d, Y') . 
                    ($reason ? ". Reason: {$reason}" : ''),
                'created_by' => auth()->id(),
            ]);

            // Update case's next hearing date
            $this->updateCaseNextHearing($hearing->case);

            return $newHearing;
        });
    }

    /**
     * Mark hearing as completed.
     */
    public function complete(
        Hearing $hearing,
        ?string $outcome = null,
        ?string $remarks = null,
        ?string $nextHearingDate = null
    ): Hearing {
        return DB::transaction(function () use ($hearing, $outcome, $remarks, $nextHearingDate) {
            $hearing->update([
                'status' => HearingStatus::Completed,
                'outcome' => $outcome,
                'remarks' => $remarks,
                'next_hearing_date' => $nextHearingDate,
            ]);

            // If next hearing date is specified, create a follow-up hearing
            if ($nextHearingDate) {
                $this->execute($hearing->case, [
                    'hearing_type' => $hearing->hearing_type,
                    'scheduled_date' => $nextHearingDate,
                    'scheduled_time' => $hearing->scheduled_time,
                    'court_branch' => $hearing->court_branch,
                    'judge_name' => $hearing->judge_name,
                    'prosecutor_id' => $hearing->prosecutor_id,
                    'remarks' => "Follow-up hearing from " . $hearing->scheduled_date?->format('M d, Y'),
                ]);
            }

            // Update case's next hearing date
            $this->updateCaseNextHearing($hearing->case);

            return $hearing->fresh();
        });
    }

    /**
     * Cancel a hearing.
     */
    public function cancel(Hearing $hearing, ?string $reason = null): Hearing
    {
        return DB::transaction(function () use ($hearing, $reason) {
            $hearing->update([
                'status' => HearingStatus::Cancelled,
                'remarks' => $reason ?? $hearing->remarks,
            ]);

            // Update case's next hearing date
            $this->updateCaseNextHearing($hearing->case);

            return $hearing->fresh();
        });
    }

    /**
     * Update the case's next hearing date based on scheduled hearings.
     */
    protected function updateCaseNextHearing(CaseModel $case): void
    {
        $nextHearing = $case->hearings()
            ->where('status', HearingStatus::Scheduled->value)
            ->where('scheduled_date', '>=', today())
            ->orderBy('scheduled_date')
            ->first();

        $case->update([
            'next_hearing_at' => $nextHearing?->scheduled_date,
        ]);
    }
}
