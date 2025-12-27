<?php

namespace App\DTOs;

use App\Enums\CasePriority;
use App\Enums\CaseStatus;
use Illuminate\Http\Request;

readonly class CaseData
{
    public function __construct(
        public string $case_number,
        public string $title,
        public string $offense,
        public string $date_filed,
        public ?string $description = null,
        public ?string $offense_type = null,
        public string $type = 'Criminal',
        public CaseStatus $status = CaseStatus::Pending,
        public CasePriority $priority = CasePriority::Normal,
        public ?string $complainant = null,
        public ?string $accused = null,
        public ?string $investigating_officer = null,
        public ?string $agency_station = null,
        public ?int $prosecutor_id = null,
        public ?string $court_branch = null,
        public ?string $judge_name = null,
        public bool $is_confidential = false,
        public ?string $notes = null,
    ) {}

    /**
     * Create from request.
     */
    public static function fromRequest(Request $request): self
    {
        return new self(
            case_number: $request->input('case_number'),
            title: $request->input('title'),
            offense: $request->input('offense'),
            date_filed: $request->input('date_filed'),
            description: $request->input('description'),
            offense_type: $request->input('offense_type'),
            type: $request->input('type', 'Criminal'),
            status: CaseStatus::tryFrom($request->input('status')) ?? CaseStatus::Pending,
            priority: CasePriority::tryFrom($request->input('priority')) ?? CasePriority::Normal,
            complainant: $request->input('complainant'),
            accused: $request->input('accused'),
            investigating_officer: $request->input('investigating_officer'),
            agency_station: $request->input('agency_station'),
            prosecutor_id: $request->input('prosecutor_id'),
            court_branch: $request->input('court_branch'),
            judge_name: $request->input('judge_name'),
            is_confidential: $request->boolean('is_confidential'),
            notes: $request->input('notes'),
        );
    }

    /**
     * Create from array.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            case_number: $data['case_number'],
            title: $data['title'],
            offense: $data['offense'],
            date_filed: $data['date_filed'],
            description: $data['description'] ?? null,
            offense_type: $data['offense_type'] ?? null,
            type: $data['type'] ?? 'Criminal',
            status: $data['status'] instanceof CaseStatus ? $data['status'] : (CaseStatus::tryFrom($data['status'] ?? '') ?? CaseStatus::Pending),
            priority: $data['priority'] instanceof CasePriority ? $data['priority'] : (CasePriority::tryFrom($data['priority'] ?? '') ?? CasePriority::Normal),
            complainant: $data['complainant'] ?? null,
            accused: $data['accused'] ?? null,
            investigating_officer: $data['investigating_officer'] ?? null,
            agency_station: $data['agency_station'] ?? null,
            prosecutor_id: $data['prosecutor_id'] ?? null,
            court_branch: $data['court_branch'] ?? null,
            judge_name: $data['judge_name'] ?? null,
            is_confidential: $data['is_confidential'] ?? false,
            notes: $data['notes'] ?? null,
        );
    }

    /**
     * Convert to array.
     */
    public function toArray(): array
    {
        return [
            'case_number' => $this->case_number,
            'title' => $this->title,
            'offense' => $this->offense,
            'date_filed' => $this->date_filed,
            'description' => $this->description,
            'offense_type' => $this->offense_type,
            'type' => $this->type,
            'status' => $this->status->value,
            'priority' => $this->priority->value,
            'complainant' => $this->complainant,
            'accused' => $this->accused,
            'investigating_officer' => $this->investigating_officer,
            'agency_station' => $this->agency_station,
            'prosecutor_id' => $this->prosecutor_id,
            'court_branch' => $this->court_branch,
            'judge_name' => $this->judge_name,
            'is_confidential' => $this->is_confidential,
            'notes' => $this->notes,
        ];
    }
}
