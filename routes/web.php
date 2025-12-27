<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CaseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HearingController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\ProsecutorController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Pages (Guest & Authenticated)
|--------------------------------------------------------------------------
*/
Route::get('/overview', function () {
    return view('pages.overview');
})->name('overview');

Route::get('/features', function () {
    return view('pages.features');
})->name('features');

Route::get('/support', function () {
    return view('pages.support');
})->name('support');

/*
|--------------------------------------------------------------------------
| Authentication Routes (Guest Only)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Email Verification Routes (Authenticated but not verified)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/verify-email', [AuthController::class, 'showVerificationNotice'])->name('verification.notice');
    Route::post('/verify-email', [AuthController::class, 'verifyCode'])->name('verification.verify');
    Route::post('/verify-email/resend', [AuthController::class, 'resendVerificationCode'])->name('verification.resend');
});

/*
|--------------------------------------------------------------------------
| Root Redirect
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return \Illuminate\Support\Facades\Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Protected Routes (Require Authentication)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    
    // Dashboards by role
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/admin', [DashboardController::class, 'admin'])->name('dashboard.admin');
    Route::get('/dashboard/prosecutor', [DashboardController::class, 'prosecutor'])->name('dashboard.prosecutor');
    Route::get('/dashboard/clerk', [DashboardController::class, 'clerk'])->name('dashboard.clerk');
    
    // Cases
    Route::resource('cases', CaseController::class);
    Route::post('/cases/{case}/status', [CaseController::class, 'updateStatus'])->name('cases.updateStatus');
    Route::post('/cases/{case}/archive', [CaseController::class, 'archive'])->name('cases.archive');
    
    // Hearings
    Route::resource('hearings', HearingController::class);
    Route::get('/hearings/calendar', [HearingController::class, 'calendar'])->name('hearings.calendar');
    
    // Notes
    Route::post('/notes', [NoteController::class, 'store'])->name('notes.store');
    Route::delete('/notes/{note}', [NoteController::class, 'destroy'])->name('notes.destroy');
    
    // Prosecutors - View available to all, Edit restricted to Admin/Clerk
    Route::get('/prosecutors', [ProsecutorController::class, 'index'])->name('prosecutors.index');
    Route::get('/prosecutors/{prosecutor}', [ProsecutorController::class, 'show'])->name('prosecutors.show');
    
    // Prosecutors Management (Admin and Clerk only)
    Route::middleware('role:Admin,Clerk')->group(function () {
        Route::get('/prosecutors/create', [ProsecutorController::class, 'create'])->name('prosecutors.create');
        Route::post('/prosecutors', [ProsecutorController::class, 'store'])->name('prosecutors.store');
        Route::get('/prosecutors/{prosecutor}/edit', [ProsecutorController::class, 'edit'])->name('prosecutors.edit');
        Route::put('/prosecutors/{prosecutor}', [ProsecutorController::class, 'update'])->name('prosecutors.update');
        Route::delete('/prosecutors/{prosecutor}', [ProsecutorController::class, 'destroy'])->name('prosecutors.destroy');
    });
    
    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/cases-by-status', [ReportController::class, 'casesByStatus'])->name('casesByStatus');
        Route::get('/cases-by-prosecutor', [ReportController::class, 'casesByProsecutor'])->name('casesByProsecutor');
        Route::get('/monthly-summary', [ReportController::class, 'monthlySummary'])->name('monthlySummary');
        Route::get('/export/{type}', [ReportController::class, 'export'])->name('export');
    });
    
    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [\App\Http\Controllers\SettingsController::class, 'index'])->name('index');
        Route::put('/profile', [\App\Http\Controllers\SettingsController::class, 'updateProfile'])->name('profile.update');
        Route::put('/password', [\App\Http\Controllers\SettingsController::class, 'updatePassword'])->name('password.update');
        Route::put('/notifications', [\App\Http\Controllers\SettingsController::class, 'updateNotifications'])->name('notifications.update');
    });
    
    // Admin Only Routes
    Route::middleware('role:Admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class);
    });
});
