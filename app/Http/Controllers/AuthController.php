<?php

namespace App\Http\Controllers;

use App\Models\Prosecutor;
use App\Models\User;
use App\Notifications\VerificationCodeNotification;
use App\Notifications\WelcomeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'role' => ['required', 'in:Admin,Prosecutor,Clerk'],
        ]);

        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']], $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Check if user is active
            if (!Auth::user()->is_active) {
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => 'Your account has been deactivated. Please contact an administrator.',
                ]);
            }

            // Check if selected role matches user's role
            if (Auth::user()->role->value !== $credentials['role']) {
                Auth::logout();
                throw ValidationException::withMessages([
                    'role' => 'Selected role does not match your account role.',
                ]);
            }

            // Check if email is verified
            if (!Auth::user()->email_verified_at) {
                // Generate and send verification code
                $this->generateAndSendVerificationCode(Auth::user());
                return redirect()->route('verification.notice');
            }

            // Redirect to role-specific dashboard
            return $this->redirectToDashboard();
        }

        throw ValidationException::withMessages([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Redirect user to appropriate dashboard based on role
     */
    protected function redirectToDashboard()
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            return redirect()->route('dashboard.admin');
        } elseif ($user->isProsecutor()) {
            return redirect()->route('dashboard.prosecutor');
        } elseif ($user->isClerk()) {
            return redirect()->route('dashboard.clerk');
        }
        
        return redirect()->route('dashboard');
    }

    /**
     * Generate a 4-digit code and send it to user's email
     */
    protected function generateAndSendVerificationCode(User $user): void
    {
        $code = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
        
        $user->update([
            'verification_code' => $code,
            'verification_code_expires_at' => Carbon::now()->addMinutes(10),
        ]);

        $user->notify(new VerificationCodeNotification($code));
    }

    /**
     * Show email verification page
     */
    public function showVerificationNotice()
    {
        if (Auth::user()->email_verified_at) {
            return $this->redirectToDashboard();
        }

        return view('auth.verify-email');
    }

    /**
     * Verify the 4-digit code
     */
    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'size:4'],
        ]);

        $user = Auth::user();

        // Check if code matches
        if ($user->verification_code !== $request->code) {
            return back()->with('error', 'The verification code you entered is not correct. Please try again.');
        }

        // Check if code is expired
        if (Carbon::now()->isAfter($user->verification_code_expires_at)) {
            return back()->with('error', 'The verification code has expired. Please request a new one.');
        }

        // Mark email as verified
        $user->update([
            'email_verified_at' => Carbon::now(),
            'verification_code' => null,
            'verification_code_expires_at' => null,
        ]);

        // Send welcome notification
        try {
            $user->notify(new WelcomeNotification($user));
        } catch (\Exception $e) {
            \Log::warning('Failed to send welcome email: ' . $e->getMessage());
        }

        return $this->redirectToDashboard()->with('success', 'Email verified successfully! Welcome to AProsecutor Case Tracker.');
    }

    /**
     * Resend verification code
     */
    public function resendVerificationCode()
    {
        $user = Auth::user();

        if ($user->email_verified_at) {
            return $this->redirectToDashboard();
        }

        $this->generateAndSendVerificationCode($user);

        return back()->with('resent', true);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    /**
     * Show the registration form
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', PasswordRule::min(10)->letters()->mixedCase()->numbers()->symbols()],
            'role' => ['required', 'in:Admin,Prosecutor,Clerk'],
            'office' => ['nullable', 'string', 'max:255'],
            'position' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
        ], [
            'name.required' => 'Please enter your full name.',
            'email.required' => 'Please enter your official email address.',
            'email.unique' => 'This email is already registered in our system.',
            'password.required' => 'Please create a secure password.',
            'password.confirmed' => 'Password confirmation does not match.',
            'role.required' => 'Please select your role.',
        ]);

        try {
            DB::beginTransaction();

            // Create the user account (without email verification yet)
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'is_active' => true,
            ]);

            // If registering as Prosecutor, create prosecutor profile
            if ($validated['role'] === 'Prosecutor') {
                Prosecutor::create([
                    'user_id' => $user->id,
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'position' => $validated['position'] ?? null,
                    'office' => $validated['office'] ?? null,
                    'is_active' => true,
                ]);
            }

            DB::commit();

            // Log the user in
            Auth::login($user);

            // Generate and send verification code
            $this->generateAndSendVerificationCode($user);

            // Redirect to verification page
            return redirect()->route('verification.notice');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Registration failed: ' . $e->getMessage());
            
            return back()->withInput()->withErrors([
                'email' => 'Registration failed. Please try again or contact support.',
            ]);
        }
    }
}
