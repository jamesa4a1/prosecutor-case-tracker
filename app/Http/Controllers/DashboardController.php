<?php

namespace App\Http\Controllers;

use App\Models\CaseModel;
use App\Models\Hearing;
use App\Models\Prosecutor;
use App\Models\StatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{

    // Admin dashboard
    public function admin()
    {
        return view('dashboard-admin');
    }

    // Prosecutor dashboard
    public function prosecutor()
    {
        return view('dashboard-prosecutor');
    }

    // Clerk dashboard
    public function clerk()
    {
        return view('dashboard-clerk');
    }

    public function index()
    {
        $user = Auth::user();
        
        // Summary statistics
        $totalActiveCases = CaseModel::whereNotIn('status', ['Closed', 'For Archive', 'Dismissed'])->count();
        $newCasesThisMonth = CaseModel::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $pendingCases = CaseModel::whereIn('status', ['For Filing', 'Under Preliminary Investigation'])->count();
        $closedCases = CaseModel::whereIn('status', ['Closed', 'Dismissed'])
            ->whereYear('updated_at', now()->year)
            ->count();

        // Status distribution for chart
        $statusDistribution = CaseModel::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Upcoming hearings (next 7 days, limited to 10)
        $upcomingHearings = Hearing::with(['case', 'assignedProsecutor'])
            ->where('date_time', '>=', now())
            ->where('date_time', '<=', now()->addDays(7))
            ->orderBy('date_time')
            ->take(10)
            ->get();

        // Recent cases (last 5 updated)
        $recentCases = CaseModel::with('prosecutor')
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        // Recent status changes (last 15)
        $recentStatusChanges = StatusHistory::with(['case', 'changedByUser'])
            ->orderBy('changed_at', 'desc')
            ->take(15)
            ->get();

        return view('dashboard', compact(
            'totalActiveCases',
            'newCasesThisMonth',
            'pendingCases',
            'closedCases',
            'statusDistribution',
            'upcomingHearings',
            'recentCases',
            'recentStatusChanges'
        ));
    }
}
