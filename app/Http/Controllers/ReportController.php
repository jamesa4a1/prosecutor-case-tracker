<?php

namespace App\Http\Controllers;

use App\Models\CaseModel;
use App\Models\Hearing;
use App\Models\Prosecutor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display the reports dashboard with summary data.
     */
    public function index(Request $request)
    {
        // Cases by status for pie/bar chart
        $casesByStatus = CaseModel::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->orderBy('count', 'desc')
            ->get();

        // Top 5 prosecutors by case count
        $topProsecutors = Prosecutor::withCount(['cases', 'cases as closed_cases_count' => function ($query) {
                $query->where('status', 'Closed');
            }])
            ->where('is_active', true)
            ->orderBy('cases_count', 'desc')
            ->take(5)
            ->get();

        // Upcoming hearings in next 30 days
        $upcomingHearings = Hearing::with(['case', 'assignedProsecutor'])
            ->where('date_time', '>=', now())
            ->where('date_time', '<=', now()->addDays(30))
            ->orderBy('date_time')
            ->take(10)
            ->get();

        // Get all active prosecutors for filter
        $prosecutors = Prosecutor::where('is_active', true)->orderBy('name')->get();

        return view('reports.index', compact(
            'casesByStatus',
            'topProsecutors',
            'upcomingHearings',
            'prosecutors'
        ));
    }

    /**
     * Cases by status report.
     */
    public function casesByStatus(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->toDateString());
        $dateTo = $request->get('date_to', now()->toDateString());

        $data = CaseModel::select('status', DB::raw('count(*) as count'))
            ->whereBetween('date_filed', [$dateFrom, $dateTo])
            ->groupBy('status')
            ->orderBy('count', 'desc')
            ->get();

        $total = $data->sum('count');

        return view('reports.cases-by-status', compact('data', 'total', 'dateFrom', 'dateTo'));
    }

    /**
     * Cases by prosecutor report.
     */
    public function casesByProsecutor(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->toDateString());
        $dateTo = $request->get('date_to', now()->toDateString());

        $data = Prosecutor::withCount(['cases' => function ($query) use ($dateFrom, $dateTo) {
            $query->whereBetween('date_filed', [$dateFrom, $dateTo]);
        }])
        ->having('cases_count', '>', 0)
        ->orderBy('cases_count', 'desc')
        ->get();

        return view('reports.cases-by-prosecutor', compact('data', 'dateFrom', 'dateTo'));
    }

    /**
     * Monthly summary report.
     */
    public function monthlySummary(Request $request)
    {
        $year = $request->get('year', now()->year);

        $monthlyData = CaseModel::select(
            DB::raw('MONTH(date_filed) as month'),
            DB::raw('count(*) as total'),
            DB::raw("SUM(CASE WHEN status = 'Closed' THEN 1 ELSE 0 END) as closed"),
            DB::raw("SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as pending")
        )
        ->whereYear('date_filed', $year)
        ->groupBy(DB::raw('MONTH(date_filed)'))
        ->orderBy('month')
        ->get();

        $hearingsData = Hearing::select(
            DB::raw('MONTH(date_time) as month'),
            DB::raw('count(*) as total')
        )
        ->whereYear('date_time', $year)
        ->groupBy(DB::raw('MONTH(date_time)'))
        ->orderBy('month')
        ->get()
        ->keyBy('month');

        return view('reports.monthly-summary', compact('monthlyData', 'hearingsData', 'year'));
    }

    /**
     * Export report data.
     */
    public function export(Request $request, string $type)
    {
        $format = $request->get('format', 'csv');
        
        switch ($type) {
            case 'cases':
                $data = CaseModel::with('prosecutor')
                    ->when($request->filled('date_from'), fn($q) => $q->where('date_filed', '>=', $request->date_from))
                    ->when($request->filled('date_to'), fn($q) => $q->where('date_filed', '<=', $request->date_to))
                    ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
                    ->get();
                
                $filename = 'cases_export_' . now()->format('Y-m-d');
                break;
            
            case 'hearings':
                $data = Hearing::with(['case', 'assignedProsecutor'])
                    ->when($request->filled('date_from'), fn($q) => $q->where('date_time', '>=', $request->date_from))
                    ->when($request->filled('date_to'), fn($q) => $q->where('date_time', '<=', $request->date_to))
                    ->get();
                
                $filename = 'hearings_export_' . now()->format('Y-m-d');
                break;
            
            default:
                abort(404);
        }

        if ($format === 'csv') {
            return $this->exportCsv($data, $filename, $type);
        }

        // For PDF, you would integrate a PDF library
        return back()->with('error', 'PDF export not yet implemented.');
    }

    /**
     * Generate CSV export.
     */
    protected function exportCsv($data, $filename, $type)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}.csv\"",
        ];

        $callback = function () use ($data, $type) {
            $file = fopen('php://output', 'w');

            // Headers
            if ($type === 'cases') {
                fputcsv($file, ['Case Number', 'Title', 'Offense', 'Type', 'Status', 'Date Filed', 'Prosecutor']);
                
                foreach ($data as $case) {
                    fputcsv($file, [
                        $case->case_number,
                        $case->title,
                        $case->offense,
                        $case->type,
                        $case->status,
                        $case->date_filed->format('Y-m-d'),
                        $case->prosecutor->name ?? 'Unassigned',
                    ]);
                }
            } elseif ($type === 'hearings') {
                fputcsv($file, ['Case Number', 'Date/Time', 'Court/Branch', 'Prosecutor', 'Status', 'Remarks']);
                
                foreach ($data as $hearing) {
                    fputcsv($file, [
                        $hearing->case->case_number ?? 'N/A',
                        $hearing->date_time->format('Y-m-d H:i'),
                        $hearing->court_branch,
                        $hearing->assignedProsecutor->name ?? 'Unassigned',
                        $hearing->result_status,
                        $hearing->remarks,
                    ]);
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
