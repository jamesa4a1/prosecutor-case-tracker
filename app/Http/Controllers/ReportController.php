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
        $prosecutorId = $request->get('prosecutor_id');

        $query = CaseModel::select('status', DB::raw('count(*) as count'));
        
        // Apply prosecutor filter if selected
        if ($prosecutorId) {
            $query->where('prosecutor_id', $prosecutorId);
        }
        
        $data = $query->groupBy('status')
            ->orderBy('count', 'desc')
            ->get();

        $total = $data->sum('count');
        
        // Get all prosecutors for filter dropdown
        $prosecutors = Prosecutor::where('is_active', true)->orderBy('name')->get();

        return view('reports.cases-by-status', compact('data', 'total', 'prosecutorId', 'prosecutors'));
    }

    /**
     * Cases by prosecutor report.
     */
    public function casesByProsecutor(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfYear()->toDateString());
        $dateTo = $request->get('date_to', now()->toDateString());

        // Get all active prosecutors with their case counts within date range
        $data = Prosecutor::where('is_active', true)
            ->withCount(['cases' => function ($query) use ($dateFrom, $dateTo) {
                $query->whereDate('date_filed', '>=', $dateFrom)
                      ->whereDate('date_filed', '<=', $dateTo);
            }])
            ->withCount(['cases as closed_cases_count' => function ($query) use ($dateFrom, $dateTo) {
                $query->whereDate('date_filed', '>=', $dateFrom)
                      ->whereDate('date_filed', '<=', $dateTo)
                      ->where('status', 'Closed');
            }])
            ->withCount(['cases as pending_cases_count' => function ($query) use ($dateFrom, $dateTo) {
                $query->whereDate('date_filed', '>=', $dateFrom)
                      ->whereDate('date_filed', '<=', $dateTo)
                      ->where('status', 'Pending');
            }])
            ->orderBy('cases_count', 'desc')
            ->get();

        // Calculate totals
        $totalCases = $data->sum('cases_count');
        $totalClosed = $data->sum('closed_cases_count');
        $totalPending = $data->sum('pending_cases_count');

        return view('reports.cases-by-prosecutor', compact('data', 'dateFrom', 'dateTo', 'totalCases', 'totalClosed', 'totalPending'));
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
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        
        switch ($type) {
            case 'cases':
                $query = CaseModel::with('prosecutor')
                    ->orderBy('date_filed', 'desc');
                
                // Apply date filters
                if ($dateFrom) {
                    $query->whereDate('date_filed', '>=', $dateFrom);
                }
                if ($dateTo) {
                    $query->whereDate('date_filed', '<=', $dateTo);
                }
                
                // Apply status filter
                if ($request->filled('status')) {
                    $query->where('status', $request->status);
                }
                
                $data = $query->get();
                
                // Generate descriptive filename
                $filenameParts = ['cases_report'];
                if ($dateFrom && $dateTo) {
                    $filenameParts[] = $dateFrom . '_to_' . $dateTo;
                } elseif ($dateFrom) {
                    $filenameParts[] = 'from_' . $dateFrom;
                } elseif ($dateTo) {
                    $filenameParts[] = 'until_' . $dateTo;
                }
                if ($request->filled('status')) {
                    $filenameParts[] = str_replace(' ', '_', strtolower($request->status));
                }
                $filenameParts[] = now()->format('Y-m-d_His');
                $filename = implode('_', $filenameParts);
                break;
            
            case 'hearings':
                $query = Hearing::with(['case', 'assignedProsecutor'])
                    ->orderBy('date_time', 'desc');
                
                // Apply date filters
                if ($dateFrom) {
                    $query->whereDate('date_time', '>=', $dateFrom);
                }
                if ($dateTo) {
                    $query->whereDate('date_time', '<=', $dateTo);
                }
                
                // Apply prosecutor filter
                if ($request->filled('prosecutor_id')) {
                    $query->where('prosecutor_id', $request->prosecutor_id);
                }
                
                $data = $query->get();
                
                // Generate descriptive filename
                $filenameParts = ['hearings_report'];
                if ($dateFrom && $dateTo) {
                    $filenameParts[] = $dateFrom . '_to_' . $dateTo;
                } elseif ($dateFrom) {
                    $filenameParts[] = 'from_' . $dateFrom;
                } elseif ($dateTo) {
                    $filenameParts[] = 'until_' . $dateTo;
                }
                $filenameParts[] = now()->format('Y-m-d_His');
                $filename = implode('_', $filenameParts);
                break;
            
            default:
                abort(404);
        }

        // Check if there's data to export
        if ($data->isEmpty()) {
            return back()->with('warning', 'No records found matching your criteria.');
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
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}.csv\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($data, $type) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Headers
            if ($type === 'cases') {
                fputcsv($file, ['Case Number', 'Title', 'Offense', 'Type', 'Status', 'Date Filed', 'Prosecutor']);
                
                foreach ($data as $case) {
                    $statusValue = $case->status instanceof \App\Enums\CaseStatus ? $case->status->value : $case->status;
                    fputcsv($file, [
                        $case->case_number,
                        $case->title,
                        $case->offense,
                        $case->type,
                        $statusValue,
                        $case->date_filed ? $case->date_filed->format('Y-m-d') : '',
                        $case->prosecutor->name ?? 'Unassigned',
                    ]);
                }
            } elseif ($type === 'hearings') {
                fputcsv($file, ['Case Number', 'Date/Time', 'Court/Branch', 'Prosecutor', 'Status', 'Remarks']);
                
                foreach ($data as $hearing) {
                    $hearingStatus = $hearing->status instanceof \App\Enums\HearingStatus ? $hearing->status->value : ($hearing->status ?? '');
                    fputcsv($file, [
                        $hearing->case->case_number ?? 'N/A',
                        $hearing->date_time ? $hearing->date_time->format('Y-m-d H:i') : '',
                        $hearing->court_branch ?? $hearing->location ?? '',
                        $hearing->assignedProsecutor->name ?? 'Unassigned',
                        $hearingStatus,
                        $hearing->remarks ?? $hearing->notes ?? '',
                    ]);
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
