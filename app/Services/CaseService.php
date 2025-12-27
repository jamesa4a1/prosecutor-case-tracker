<?php

namespace App\Services;

use App\Enums\CasePriority;
use App\Enums\CaseStatus;
use App\Models\CaseModel;
use App\Models\Prosecutor;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;

class CaseService
{
    /**
     * Get cases with filters and pagination.
     */
    public function getCases(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = CaseModel::with(['prosecutor', 'hearings'])
            ->when(!empty($filters['search']), function (Builder $q) use ($filters) {
                $q->search($filters['search']);
            })
            ->when(!empty($filters['status']), function (Builder $q) use ($filters) {
                $q->where('status', $filters['status']);
            })
            ->when(!empty($filters['priority']), function (Builder $q) use ($filters) {
                $q->where('priority', $filters['priority']);
            })
            ->when(!empty($filters['prosecutor_id']), function (Builder $q) use ($filters) {
                $q->where('prosecutor_id', $filters['prosecutor_id']);
            })
            ->when(!empty($filters['date_from']), function (Builder $q) use ($filters) {
                $q->where('date_filed', '>=', $filters['date_from']);
            })
            ->when(!empty($filters['date_to']), function (Builder $q) use ($filters) {
                $q->where('date_filed', '<=', $filters['date_to']);
            })
            ->when(!empty($filters['type']), function (Builder $q) use ($filters) {
                $q->where('type', $filters['type']);
            })
            ->when(isset($filters['is_confidential']), function (Builder $q) use ($filters) {
                $q->where('is_confidential', $filters['is_confidential']);
            });

        // Apply sorting
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortDir = $filters['sort_dir'] ?? 'desc';
        $query->orderBy($sortBy, $sortDir);

        return $query->paginate($perPage);
    }

    /**
     * Get cases for a specific prosecutor.
     */
    public function getProsecutorCases(
        int $prosecutorId,
        array $filters = [],
        int $perPage = 15
    ): LengthAwarePaginator {
        $filters['prosecutor_id'] = $prosecutorId;
        return $this->getCases($filters, $perPage);
    }

    /**
     * Get unassigned cases.
     */
    public function getUnassignedCases(int $perPage = 15): LengthAwarePaginator
    {
        return CaseModel::whereNull('prosecutor_id')
            ->active()
            ->orderBy('priority', 'desc')
            ->orderBy('date_filed')
            ->paginate($perPage);
    }

    /**
     * Get urgent cases requiring attention.
     */
    public function getUrgentCases(?int $prosecutorId = null): Collection
    {
        $query = CaseModel::with(['prosecutor', 'upcomingHearings'])
            ->urgent()
            ->active();

        if ($prosecutorId) {
            $query->where('prosecutor_id', $prosecutorId);
        }

        return $query->orderBy('date_filed')
            ->limit(10)
            ->get();
    }

    /**
     * Get cases with upcoming hearings.
     */
    public function getCasesWithUpcomingHearings(int $days = 7): Collection
    {
        return CaseModel::with(['prosecutor', 'upcomingHearings'])
            ->withUpcomingHearings($days)
            ->active()
            ->get();
    }

    /**
     * Get overdue cases (pending for too long).
     */
    public function getOverdueCases(int $daysThreshold = 30): Collection
    {
        return CaseModel::with('prosecutor')
            ->where('status', CaseStatus::Pending->value)
            ->where('date_filed', '<=', now()->subDays($daysThreshold))
            ->orderBy('date_filed')
            ->get();
    }

    /**
     * Generate case number.
     */
    public function generateCaseNumber(string $prefix = 'NPS'): string
    {
        $year = now()->format('Y');
        
        $lastCase = CaseModel::where('case_number', 'like', "{$prefix}-{$year}-%")
            ->orderByRaw('CAST(SUBSTRING_INDEX(case_number, "-", -1) AS UNSIGNED) DESC')
            ->first();

        if ($lastCase) {
            $lastNumber = (int) substr($lastCase->case_number, strrpos($lastCase->case_number, '-') + 1);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return sprintf('%s-%s-%05d', $prefix, $year, $newNumber);
    }

    /**
     * Get case statistics by date range.
     */
    public function getStatsByDateRange(string $from, string $to): array
    {
        return [
            'total_filed' => CaseModel::whereBetween('date_filed', [$from, $to])->count(),
            'by_status' => CaseModel::whereBetween('date_filed', [$from, $to])
                ->select('status', \DB::raw('count(*) as count'))
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray(),
            'by_priority' => CaseModel::whereBetween('date_filed', [$from, $to])
                ->select('priority', \DB::raw('count(*) as count'))
                ->groupBy('priority')
                ->pluck('count', 'priority')
                ->toArray(),
            'resolved' => CaseModel::where('status', CaseStatus::Resolved->value)
                ->whereBetween('date_closed', [$from, $to])
                ->count(),
        ];
    }

    /**
     * Get workload distribution by prosecutor.
     */
    public function getWorkloadDistribution(): Collection
    {
        return Prosecutor::withCount([
            'cases',
            'cases as active_cases_count' => function (Builder $q) {
                $q->active();
            },
            'cases as urgent_cases_count' => function (Builder $q) {
                $q->urgent();
            },
        ])
        ->orderByDesc('active_cases_count')
        ->get();
    }

    /**
     * Check if case number exists.
     */
    public function caseNumberExists(string $caseNumber, ?int $excludeId = null): bool
    {
        $query = CaseModel::where('case_number', $caseNumber);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Get similar cases (for duplicate detection).
     */
    public function getSimilarCases(string $title, string $complainant, ?int $excludeId = null): Collection
    {
        return CaseModel::where(function (Builder $q) use ($title, $complainant) {
            $q->where('title', 'like', "%{$title}%")
              ->orWhere('complainant', 'like', "%{$complainant}%");
        })
        ->when($excludeId, function (Builder $q) use ($excludeId) {
            $q->where('id', '!=', $excludeId);
        })
        ->limit(5)
        ->get();
    }
}
