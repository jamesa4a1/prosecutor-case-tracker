<?php

namespace App\Http\Controllers;

use App\Models\CaseModel;
use App\Models\Hearing;
use App\Models\Prosecutor;
use Illuminate\Http\Request;

class HearingController extends Controller
{
    /**
     * Display a listing of hearings.
     */
    public function index(Request $request)
    {
        $query = Hearing::with(['case', 'assignedProsecutor']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('case', function ($q) use ($search) {
                $q->where('case_number', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%");
            });
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('date_time', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('date_time', '<=', $request->date_to);
        }

        // Filter by prosecutor
        if ($request->filled('prosecutor_id')) {
            $query->where('assigned_prosecutor_id', $request->prosecutor_id);
        }

        // Filter by result status
        if ($request->filled('result_status')) {
            $query->where('result_status', $request->result_status);
        }

        $hearings = $query->orderBy('date_time', 'desc')->paginate(15)->withQueryString();
        $prosecutors = Prosecutor::active()->get();

        return view('hearings.index', compact('hearings', 'prosecutors'));
    }

    /**
     * Show the calendar view of hearings.
     */
    public function calendar(Request $request)
    {
        $hearings = Hearing::with(['case', 'assignedProsecutor'])
            ->whereMonth('date_time', $request->get('month', now()->month))
            ->whereYear('date_time', $request->get('year', now()->year))
            ->get();

        return view('hearings.calendar', compact('hearings'));
    }

    /**
     * Show the form for creating a new hearing.
     */
    public function create(Request $request)
    {
        $cases = CaseModel::select('id', 'case_number', 'title')->get();
        $prosecutors = Prosecutor::active()->get();
        $selectedCaseId = $request->get('case_id');

        return view('hearings.create', compact('cases', 'prosecutors', 'selectedCaseId'));
    }

    /**
     * Store a newly created hearing.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'case_id' => 'required|exists:cases,id',
            'date_time' => 'required|date|after:now',
            'court_branch' => 'nullable|string|max:255',
            'assigned_prosecutor_id' => 'nullable|exists:prosecutors,id',
            'result_status' => 'nullable|string|max:100',
            'remarks' => 'nullable|string',
        ]);

        $hearing = Hearing::create($validated);

        // Optionally update the case's next_hearing_at
        $case = CaseModel::find($validated['case_id']);
        if ($case && (!$case->next_hearing_at || $validated['date_time'] < $case->next_hearing_at)) {
            $case->update(['next_hearing_at' => $validated['date_time']]);
        }

        return redirect()->route('hearings.index')
            ->with('success', 'Hearing scheduled successfully.');
    }

    /**
     * Display the specified hearing.
     */
    public function show(Hearing $hearing)
    {
        $hearing->load(['case', 'assignedProsecutor']);
        return view('hearings.show', compact('hearing'));
    }

    /**
     * Show the form for editing the specified hearing.
     */
    public function edit(Hearing $hearing)
    {
        $cases = CaseModel::select('id', 'case_number', 'title')->get();
        $prosecutors = Prosecutor::active()->get();

        return view('hearings.edit', compact('hearing', 'cases', 'prosecutors'));
    }

    /**
     * Update the specified hearing.
     */
    public function update(Request $request, Hearing $hearing)
    {
        $validated = $request->validate([
            'case_id' => 'required|exists:cases,id',
            'date_time' => 'required|date',
            'court_branch' => 'nullable|string|max:255',
            'assigned_prosecutor_id' => 'nullable|exists:prosecutors,id',
            'result_status' => 'nullable|string|max:100',
            'remarks' => 'nullable|string',
        ]);

        $hearing->update($validated);

        return redirect()->route('hearings.index')
            ->with('success', 'Hearing updated successfully.');
    }

    /**
     * Remove the specified hearing.
     */
    public function destroy(Hearing $hearing)
    {
        $hearing->delete();

        return redirect()->route('hearings.index')
            ->with('success', 'Hearing deleted successfully.');
    }
}
