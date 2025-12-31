<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index()
    {
        return view('settings.index');
    }

    /**
     * Update the user's profile information.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'office' => ['nullable', 'string', 'max:255'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,gif,webp', 'max:2048', 'dimensions:max_width=2000,max_height=2000'],
        ]);

        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            
            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $path;
        }

        $user->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }

    /**
     * Update notification preferences.
     */
    public function updateNotifications(Request $request)
    {
        $user = Auth::user();

        $preferences = [
            'notify_case_assignment' => $request->boolean('notify_case_assignment'),
            'notify_hearing_reminder' => $request->boolean('notify_hearing_reminder'),
            'notify_status_change' => $request->boolean('notify_status_change'),
            'notify_new_notes' => $request->boolean('notify_new_notes'),
            'notify_weekly_summary' => $request->boolean('notify_weekly_summary'),
            'notify_security' => $request->boolean('notify_security'),
        ];

        $user->update(['notification_preferences' => $preferences]);

        return back()->with('success', 'Notification preferences updated successfully.');
    }
}
