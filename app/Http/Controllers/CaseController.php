<?php

namespace App\Http\Controllers;

use App\Models\CaseModel;
use App\Models\Prosecutor;
use App\Models\StatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CaseController extends Controller
{
    public function index(Request $request)
    {
        $query = CaseModel::with('prosecutor');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('case_number', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhere('accused', 'like', "%{$search}%");
            });
        }

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('prosecutor_id')) {
            $query->where('prosecutor_id', $request->prosecutor_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('date_filed', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date_filed', '<=', $request->date_to);
        }

        $cases = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        $prosecutors = Prosecutor::where('active', true)->get();

        return view('cases.index', compact('cases', 'prosecutors'));
    }

    public function create()
    {
        $prosecutors = Prosecutor::where('active', true)->get();
        $statuses = CaseModel::STATUSES;
        $types = CaseModel::TYPES;

        return view('cases.create', compact('prosecutors', 'statuses', 'types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'case_number' => 'required|unique:cases,case_number',
            'title' => 'required|string|max:255',
            'offense' => 'required|string|max:255',
            'type' => 'required|in:Criminal,Civil,Special',
            'date_filed' => 'required|date',
            'status' => 'required|in:' . implode(',', CaseModel::STATUSES),
            'complainant' => 'nullable|string|max:255',
            'accused' => 'nullable|string|max:255',
            'investigating_officer' => 'nullable|string|max:255',
            'agency_station' => 'nullable|string|max:255',
            'prosecutor_id' => 'nullable|exists:prosecutors,id',
            'next_hearing_at' => 'nullable|date',
            'court_branch' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $case = CaseModel::create($validated);

        // Record status history
        StatusHistory::create([
            'case_id' => $case->id,
            'from_status' => null,
            'to_status' => $case->status,
            'changed_by' => Auth::id(),
            'changed_at' => now(),
        ]);

        return redirect()->route('cases.show', $case)
            ->with('success', 'Case created successfully.');
    }

    public function show(CaseModel $case)
    {
        $case->load(['prosecutor', 'hearings.assignedProsecutor', 'notes.user', 'statusHistories.changedByUser']);

        return view('cases.show', compact('case'));
    }

    public function edit(CaseModel $case)
    {
        $prosecutors = Prosecutor::where('active', true)->get();
        $statuses = CaseModel::STATUSES;
        $types = CaseModel::TYPES;

        return view('cases.edit', compact('case', 'prosecutors', 'statuses', 'types'));
    }

    public function update(Request $request, CaseModel $case)
    {
        $validated = $request->validate([
            'case_number' => 'required|unique:cases,case_number,' . $case->id,
            'title' => 'required|string|max:255',
            'offense' => 'required|string|max:255',
            'type' => 'required|in:Criminal,Civil,Special',
            'date_filed' => 'required|date',
            'status' => 'required|in:' . implode(',', CaseModel::STATUSES),
            'complainant' => 'nullable|string|max:255',
            'accused' => 'nullable|string|max:255',
            'investigating_officer' => 'nullable|string|max:255',
            'agency_station' => 'nullable|string|max:255',
            'prosecutor_id' => 'nullable|exists:prosecutors,id',
            'next_hearing_at' => 'nullable|date',
            'court_branch' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        // Track status change
        if ($case->status !== $validated['status']) {
            StatusHistory::create([
                'case_id' => $case->id,
                'from_status' => $case->status,
                'to_status' => $validated['status'],
                'changed_by' => Auth::id(),
                'changed_at' => now(),
            ]);
        }

        $case->update($validated);

        return redirect()->route('cases.show', $case)
            ->with('success', 'Case updated successfully.');
    }

    public function destroy(CaseModel $case)
    {
        $case->delete();

        return redirect()->route('cases.index')
            ->with('success', 'Case deleted successfully.');
    }

    public function updateStatus(Request $request, CaseModel $case)
    {
        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', CaseModel::STATUSES),
        ]);

        if ($case->status !== $validated['status']) {
            StatusHistory::create([
                'case_id' => $case->id,
                'from_status' => $case->status,
                'to_status' => $validated['status'],
                'changed_by' => Auth::id(),
                'changed_at' => now(),
            ]);

            $case->update(['status' => $validated['status']]);
        }

        return back()->with('success', 'Case status updated successfully.');
    }
}
