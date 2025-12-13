<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    /**
     * Store a newly created note.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'case_id' => 'required|exists:cases,id',
            'body' => 'required|string|max:5000',
        ]);

        Note::create([
            'case_id' => $validated['case_id'],
            'user_id' => auth()->id(),
            'body' => $validated['body'],
        ]);

        return back()->with('success', 'Note added successfully.');
    }

    /**
     * Remove the specified note.
     */
    public function destroy(Note $note)
    {
        // Only allow deletion by the note author or admin
        if (auth()->id() !== $note->user_id && !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $note->delete();

        return back()->with('success', 'Note deleted successfully.');
    }
}
