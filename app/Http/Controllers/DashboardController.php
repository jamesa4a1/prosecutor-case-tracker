<?php

namespace App\Http\Controllers;

use App\Models\CaseModel;
use App\Models\Hearing;
use App\Models\Prosecutor;
use Illuminate\Http\Request;
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
        // Summary statistics
        $totalActiveCases = CaseModel::whereNotIn('status', ['Closed', 'For Archive'])->count();
        $newCasesThisMonth = CaseModel::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $pendingCases = CaseModel::whereIn('status', ['For Filing', 'Under Preliminary Investigation'])->count();
        $closedCases = CaseModel::where('status', 'Closed')->count();

        // Status distribution for chart
        $statusDistribution = CaseModel::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Upcoming hearings (next 10)
        $upcomingHearings = Hearing::with(['case', 'assignedProsecutor'])
            ->where('date_time', '>=', now())
            ->orderBy('date_time')
            ->take(10)
            ->get();

        // Recent cases (last 5 updated)
        $recentCases = CaseModel::with('prosecutor')
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalActiveCases',
            'newCasesThisMonth',
            'pendingCases',
            'closedCases',
            'statusDistribution',
            'upcomingHearings',
            'recentCases'
        ));
    }
}
