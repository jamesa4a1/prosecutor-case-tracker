<?php

namespace App\Services;

use App\Enums\CasePriority;
use App\Enums\CaseStatus;
use App\Enums\HearingStatus;
use App\Models\CaseModel;
use App\Models\Hearing;
use App\Models\Prosecutor;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * Get dashboard statistics for admin.
     */
    public function getAdminStats(): array
    {
        return Cache::remember('admin_dashboard_stats', 300, function () {
            return [
                'cases' => $this->getCaseStats(),
                'hearings' => $this->getHearingStats(),
                'users' => $this->getUserStats(),
                'prosecutors' => $this->getProsecutorStats(),
                'recent_activities' => $this->getRecentActivities(),
            ];
        });
    }

    /**
     * Get dashboard statistics for a prosecutor.
     */
    public function getProsecutorStats(int $prosecutorId): array
    {
        return [
            'my_cases' => $this->getMyCaseStats($prosecutorId),
            'my_hearings' => $this->getMyHearingStats($prosecutorId),
            'upcoming_deadlines' => $this->getUpcomingDeadlines($prosecutorId),
        ];
    }

    /**
     * Get overall case statistics.
     */
    public function getCaseStats(): array
    {
        $total = CaseModel::count();
        $byStatus = CaseModel::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $byPriority = CaseModel::select('priority', DB::raw('count(*) as count'))
            ->groupBy('priority')
            ->pluck('count', 'priority')
            ->toArray();

        return [
            'total' => $total,
            'by_status' => $byStatus,
            'by_priority' => $byPriority,
            'active' => CaseModel::active()->count(),
            'urgent' => CaseModel::urgent()->count(),
            'filed_this_month' => CaseModel::whereMonth('date_filed', now()->month)
                ->whereYear('date_filed', now()->year)
                ->count(),
            'resolved_this_month' => CaseModel::where('status', CaseStatus::Resolved->value)
                ->whereMonth('date_closed', now()->month)
                ->whereYear('date_closed', now()->year)
                ->count(),
        ];
    }

    /**
     * Get case statistics for a specific prosecutor.
     */
    public function getMyCaseStats(int $prosecutorId): array
    {
        $query = CaseModel::where('prosecutor_id', $prosecutorId);

        return [
            'total' => $query->count(),
            'active' => (clone $query)->active()->count(),
            'pending' => (clone $query)->where('status', CaseStatus::Pending->value)->count(),
            'under_investigation' => (clone $query)->where('status', CaseStatus::UnderInvestigation->value)->count(),
            'for_resolution' => (clone $query)->where('status', CaseStatus::ForResolution->value)->count(),
            'urgent' => (clone $query)->urgent()->count(),
        ];
    }

    /**
     * Get hearing statistics.
     */
    public function getHearingStats(): array
    {
        return [
            'total' => Hearing::count(),
            'scheduled' => Hearing::where('status', HearingStatus::Scheduled->value)->count(),
            'today' => Hearing::whereDate('scheduled_date', today())->count(),
            'this_week' => Hearing::whereBetween('scheduled_date', [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ])->count(),
            'upcoming' => Hearing::upcoming()->count(),
            'completed' => Hearing::where('status', HearingStatus::Completed->value)->count(),
            'postponed' => Hearing::where('status', HearingStatus::Postponed->value)->count(),
        ];
    }

    /**
     * Get hearing statistics for a specific prosecutor.
     */
    public function getMyHearingStats(int $prosecutorId): array
    {
        $query = Hearing::whereHas('case', function ($q) use ($prosecutorId) {
            $q->where('prosecutor_id', $prosecutorId);
        });

        return [
            'total' => $query->count(),
            'today' => (clone $query)->whereDate('scheduled_date', today())->count(),
            'this_week' => (clone $query)->thisWeek()->count(),
            'upcoming' => (clone $query)->upcoming()->count(),
        ];
    }

    /**
     * Get user statistics.
     */
    public function getUserStats(): array
    {
        return [
            'total' => User::count(),
            'active' => User::active()->count(),
            'admins' => User::admins()->count(),
            'prosecutors' => User::prosecutors()->count(),
            'clerks' => User::clerks()->count(),
        ];
    }

    /**
     * Get prosecutor statistics.
     */
    protected function getProsecutorStats(): array
    {
        $prosecutors = Prosecutor::withCount(['cases', 'cases as active_cases_count' => function ($q) {
            $q->active();
        }])->get();

        return [
            'total' => $prosecutors->count(),
            'with_cases' => $prosecutors->where('cases_count', '>', 0)->count(),
            'average_caseload' => $prosecutors->avg('active_cases_count'),
            'max_caseload' => $prosecutors->max('active_cases_count'),
        ];
    }

    /**
     * Get upcoming deadlines for a prosecutor.
     */
    public function getUpcomingDeadlines(int $prosecutorId, int $days = 7): Collection
    {
        $hearings = Hearing::with('case')
            ->whereHas('case', function ($q) use ($prosecutorId) {
                $q->where('prosecutor_id', $prosecutorId);
            })
            ->upcoming()
            ->where('scheduled_date', '<=', today()->addDays($days))
            ->get();

        return $hearings->map(function ($hearing) {
            return [
                'type' => 'hearing',
                'date' => $hearing->scheduled_date,
                'title' => "Hearing: {$hearing->case->case_number}",
                'description' => $hearing->hearing_type?->label() . ' at ' . $hearing->venue,
                'case_id' => $hearing->case_id,
                'days_until' => $hearing->days_until,
            ];
        })->sortBy('date');
    }

    /**
     * Get recent activities for the dashboard.
     */
    public function getRecentActivities(int $limit = 10): Collection
    {
        $recentCases = CaseModel::with('prosecutor')
            ->latest()
            ->take($limit)
            ->get()
            ->map(function ($case) {
                return [
                    'type' => 'case',
                    'action' => 'created',
                    'title' => "New case: {$case->case_number}",
                    'description' => $case->title,
                    'timestamp' => $case->created_at,
                    'user' => $case->createdBy?->name ?? 'System',
                ];
            });

        $recentHearings = Hearing::with(['case'])
            ->where('status', HearingStatus::Completed->value)
            ->latest('updated_at')
            ->take($limit)
            ->get()
            ->map(function ($hearing) {
                return [
                    'type' => 'hearing',
                    'action' => 'completed',
                    'title' => "Hearing completed: {$hearing->case->case_number}",
                    'description' => $hearing->outcome ?? 'No outcome recorded',
                    'timestamp' => $hearing->updated_at,
                ];
            });

        return $recentCases->merge($recentHearings)
            ->sortByDesc('timestamp')
            ->take($limit)
            ->values();
    }

    /**
     * Get case trends for charts.
     */
    public function getCaseTrends(int $months = 6): array
    {
        $trends = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthLabel = $date->format('M Y');

            $trends[] = [
                'month' => $monthLabel,
                'filed' => CaseModel::whereMonth('date_filed', $date->month)
                    ->whereYear('date_filed', $date->year)
                    ->count(),
                'resolved' => CaseModel::where('status', CaseStatus::Resolved->value)
                    ->whereMonth('date_closed', $date->month)
                    ->whereYear('date_closed', $date->year)
                    ->count(),
            ];
        }

        return $trends;
    }

    /**
     * Clear dashboard cache.
     */
    public function clearCache(): void
    {
        Cache::forget('admin_dashboard_stats');
    }
}
