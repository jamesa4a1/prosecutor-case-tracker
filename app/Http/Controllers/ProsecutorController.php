<?php

namespace App\Http\Controllers;

use App\Models\CaseModel;
use App\Models\Prosecutor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ProsecutorMessage;

class ProsecutorController extends Controller
{
    /**
     * Display a listing of prosecutors.
     */
    public function index(Request $request)
    {
        $query = Prosecutor::withCount(['cases', 'assignedHearings']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Filter by office
        if ($request->filled('office')) {
            $query->where('office', $request->office);
        }

        $prosecutors = $query->orderBy('name')->paginate(15)->withQueryString();
        $offices = Prosecutor::distinct()->pluck('office')->filter();

        return view('prosecutors.index', compact('prosecutors', 'offices'));
    }

    /**
     * Show the form for creating a new prosecutor.
     */
    public function create()
    {
        $users = User::where('role', User::ROLE_PROSECUTOR)
            ->whereDoesntHave('prosecutor')
            ->get();

        return view('prosecutors.create', compact('users'));
    }

    /**
     * Store a newly created prosecutor.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:prosecutors,email',
            'position' => 'nullable|string|max:255',
            'office' => 'nullable|string|max:255',
            'user_id' => 'nullable|exists:users,id|unique:prosecutors,user_id',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        Prosecutor::create($validated);

        return redirect()->route('prosecutors.index')
            ->with('success', 'Prosecutor created successfully.');
    }

    /**
     * Display the specified prosecutor.
     */
    public function show(Prosecutor $prosecutor)
    {
        $prosecutor->load(['cases' => function ($query) {
            $query->latest()->take(10);
        }, 'assignedHearings' => function ($query) {
            $query->where('date_time', '>=', now())->orderBy('date_time')->take(10);
        }]);

        $caseStats = [
            'total' => $prosecutor->cases()->count(),
            'active' => $prosecutor->cases()->whereNotIn('status', ['Closed', 'Archived'])->count(),
            'closed' => $prosecutor->cases()->where('status', 'Closed')->count(),
        ];

        return view('prosecutors.show', compact('prosecutor', 'caseStats'));
    }

    /**
     * Show the form for editing the specified prosecutor.
     */
    public function edit(Prosecutor $prosecutor)
    {
        $users = User::where('role', User::ROLE_PROSECUTOR)
            ->where(function ($query) use ($prosecutor) {
                $query->whereDoesntHave('prosecutor')
                      ->orWhere('id', $prosecutor->user_id);
            })
            ->get();

        return view('prosecutors.edit', compact('prosecutor', 'users'));
    }

    /**
     * Update the specified prosecutor.
     */
    public function update(Request $request, Prosecutor $prosecutor)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:prosecutors,email,' . $prosecutor->id,
            'position' => 'nullable|string|max:255',
            'office' => 'nullable|string|max:255',
            'user_id' => 'nullable|exists:users,id|unique:prosecutors,user_id,' . $prosecutor->id,
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $prosecutor->update($validated);

        return redirect()->route('prosecutors.index')
            ->with('success', 'Prosecutor updated successfully.');
    }

    /**
     * Remove the specified prosecutor.
     */
    public function destroy(Prosecutor $prosecutor)
    {
        // Check if prosecutor has active cases
        if ($prosecutor->cases()->whereNotIn('status', ['Closed', 'Archived'])->exists()) {
            return back()->with('error', 'Cannot delete prosecutor with active cases.');
        }

        $prosecutor->delete();

        return redirect()->route('prosecutors.index')
            ->with('success', 'Prosecutor deleted successfully.');
    }

    /**
     * Send an email message to the prosecutor.
     */
    public function sendMessage(Request $request, Prosecutor $prosecutor)
    {
        $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        $senderName = auth()->user()->name;
        $senderEmail = auth()->user()->email;
        $messageContent = $request->message;

        // CHANGE THIS EMAIL ADDRESS to the actor's actual email
        // For testing with MailHog, use any email address
        $recipientEmail = $prosecutor->email; // Or hardcode: 'actor@example.com'

        try {
            Mail::to($recipientEmail)->send(
                new ProsecutorMessage($senderName, $senderEmail, $messageContent)
            );

            return response()->json([
                'success' => true,
                'message' => 'Message sent to ' . $prosecutor->email
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send message: ' . $e->getMessage()
            ], 500);
        }
    }
}
