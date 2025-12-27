<?php

namespace App\DTOs;

use App\Enums\HearingStatus;
use App\Enums\HearingType;
use Illuminate\Http\Request;

readonly class HearingData
{
    public function __construct(
        public int $case_id,
        public string $scheduled_date,
        public ?string $scheduled_time = null,
        public HearingType $hearing_type = HearingType::Preliminary,
        public HearingStatus $status = HearingStatus::Scheduled,
        public ?string $venue = null,
        public ?string $court_branch = null,
        public ?string $judge_name = null,
        public ?int $prosecutor_id = null,
        public ?string $outcome = null,
        public ?string $remarks = null,
        public ?string $next_hearing_date = null,
        public ?string $postponement_reason = null,
    ) {}

    /**
     * Create from request.
     */
    public static function fromRequest(Request $request): self
    {
        return new self(
            case_id: $request->input('case_id'),
            scheduled_date: $request->input('scheduled_date'),
            scheduled_time: $request->input('scheduled_time'),
            hearing_type: HearingType::tryFrom($request->input('hearing_type')) ?? HearingType::Preliminary,
            status: HearingStatus::tryFrom($request->input('status')) ?? HearingStatus::Scheduled,
            venue: $request->input('venue'),
            court_branch: $request->input('court_branch'),
            judge_name: $request->input('judge_name'),
            prosecutor_id: $request->input('prosecutor_id'),
            outcome: $request->input('outcome'),
            remarks: $request->input('remarks'),
            next_hearing_date: $request->input('next_hearing_date'),
            postponement_reason: $request->input('postponement_reason'),
        );
    }

    /**
     * Create from array.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            case_id: $data['case_id'],
            scheduled_date: $data['scheduled_date'],
            scheduled_time: $data['scheduled_time'] ?? null,
            hearing_type: $data['hearing_type'] instanceof HearingType 
                ? $data['hearing_type'] 
                : (HearingType::tryFrom($data['hearing_type'] ?? '') ?? HearingType::Preliminary),
            status: $data['status'] instanceof HearingStatus 
                ? $data['status'] 
                : (HearingStatus::tryFrom($data['status'] ?? '') ?? HearingStatus::Scheduled),
            venue: $data['venue'] ?? null,
            court_branch: $data['court_branch'] ?? null,
            judge_name: $data['judge_name'] ?? null,
            prosecutor_id: $data['prosecutor_id'] ?? null,
            outcome: $data['outcome'] ?? null,
            remarks: $data['remarks'] ?? null,
            next_hearing_date: $data['next_hearing_date'] ?? null,
            postponement_reason: $data['postponement_reason'] ?? null,
        );
    }

    /**
     * Convert to array.
     */
    public function toArray(): array
    {
        return [
            'case_id' => $this->case_id,
            'scheduled_date' => $this->scheduled_date,
            'scheduled_time' => $this->scheduled_time,
            'hearing_type' => $this->hearing_type->value,
            'status' => $this->status->value,
            'venue' => $this->venue,
            'court_branch' => $this->court_branch,
            'judge_name' => $this->judge_name,
            'prosecutor_id' => $this->prosecutor_id,
            'outcome' => $this->outcome,
            'remarks' => $this->remarks,
            'next_hearing_date' => $this->next_hearing_date,
            'postponement_reason' => $this->postponement_reason,
        ];
    }
}
