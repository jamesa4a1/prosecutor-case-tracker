# Prosecutor Case Tracking System - UI/UX Implementation Guide

## ✅ Completed Components

### 1. Authentication Pages
- **Login Page** (`resources/views/auth/login.blade.php`) - ✅ Created
  - Professional split-screen design
  - Hero section with branding
  - Secure login form with validation
  
- **Forgot Password Page** (`resources/views/auth/forgot-password.blade.php`) - ✅ Created
  - Clean, centered design
  - Email reset functionality

## 📋 Implementation Checklist

### Phase 1: Core Layout & Navigation (Priority: HIGH)
Create these files in order:

1. **Main Layout** - `resources/views/layouts/admin.blade.php`
   ```
   - Top navigation bar
   - Left sidebar with role-based menu
   - Main content area
   - Footer
   ```

2. **Components** - `resources/views/components/`
   - `sidebar.blade.php` - Navigation menu
   - `navbar.blade.php` - Top bar with user profile
   - `alert.blade.php` - Reusable alerts
   - `stat-card.blade.php` - Dashboard statistics cards
   
### Phase 2: Dashboard (Priority: HIGH)
3. **Dashboard** - `resources/views/dashboard.blade.php`
   ```
   Components needed:
   - 4 summary cards (Total Cases, New Cases, Pending, Closed)
   - Case status distribution chart
   - Upcoming hearings widget (table)
   - Recent cases list
   ```

### Phase 3: Case Management (Priority: HIGH)
4. **Cases Module**
   - `resources/views/cases/index.blade.php` - List all cases with search/filter
   - `resources/views/cases/create.blade.php` - Case intake form
   - `resources/views/cases/show.blade.php` - Case details with tabs
   - `resources/views/cases/edit.blade.php` - Edit case form

### Phase 4: Hearings Module (Priority: MEDIUM)
5. **Hearings Module**
   - `resources/views/hearings/index.blade.php` - List/calendar view
   - `resources/views/hearings/create.blade.php` - Add hearing form

### Phase 5: Prosecutors & Admin (Priority: MEDIUM)
6. **Prosecutor Management**
   - `resources/views/prosecutors/index.blade.php`
   - `resources/views/prosecutors/show.blade.php`

7. **Reports Module**
   - `resources/views/reports/index.blade.php`

8. **User Management** (Admin only)
   - `resources/views/users/index.blade.php`
   - `resources/views/users/create.blade.php`

## 🎨 Design System

### Color Palette
```css
Primary (Blue): #1e40af (blue-800)
Primary Light: #3b82f6 (blue-500)
Success: #10b981 (emerald-500)
Warning: #f59e0b (amber-500)
Danger: #ef4444 (red-500)
Background: #f8fafc (slate-50)
Surface: #ffffff (white)
Text Primary: #1f2937 (gray-800)
Text Secondary: #6b7280 (gray-500)
Border: #e5e7eb (gray-200)
```

### Typography
- Font Family: Inter, Roboto, system-ui, sans-serif
- Headings: font-bold
- Body: font-normal
- Small text: text-sm

### Spacing & Layout
- Container max-width: 1280px (xl)
- Card padding: p-6
- Section gaps: gap-6
- Card radius: rounded-lg (8px)
- Shadow: shadow-sm for cards, shadow-lg for modals

### Components Patterns

#### Status Badges
```html
<!-- For Filing -->
<span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
    For Filing
</span>

<!-- Under Investigation -->
<span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
    Under Investigation
</span>

<!-- Filed in Court -->
<span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
    Filed in Court
</span>

<!-- For Archive -->
<span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
    For Archive
</span>
```

#### Buttons
```html
<!-- Primary -->
<button class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition">
    <i class="fas fa-plus mr-2"></i> Add New
</button>

<!-- Secondary -->
<button class="bg-white hover:bg-gray-50 text-gray-700 font-semibold py-2 px-4 rounded-lg border border-gray-300 transition">
    Cancel
</button>

<!-- Danger -->
<button class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition">
    Delete
</button>
```

#### Form Fields
```html
<div>
    <label class="block text-sm font-medium text-gray-700 mb-2">
        Field Label <span class="text-red-500">*</span>
    </label>
    <input 
        type="text" 
        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
        placeholder="Enter value"
    >
    <p class="mt-1 text-xs text-gray-500">Helper text goes here</p>
</div>
```

#### Data Tables
```html
<div class="overflow-x-auto bg-white rounded-lg shadow">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Header
                </th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    Data
                </td>
            </tr>
        </tbody>
    </table>
</div>
```

## 🔧 Backend Requirements

### Controllers Needed
```
php artisan make:controller DashboardController
php artisan make:controller CaseController --resource
php artisan make:controller HearingController --resource
php artisan make:controller ProsecutorController --resource
php artisan make:controller ReportController
php artisan make:controller UserController --resource
```

### Routes Structure (`routes/web.php`)
```php
// Authentication
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('cases', CaseController::class);
    Route::resource('hearings', HearingController::class);
    Route::resource('prosecutors', ProsecutorController::class);
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    
    // Admin only
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', UserController::class);
    });
});
```

### Database Migrations
Ensure these tables exist with proper relationships:
- users (with role field)
- prosecutors
- cases
- hearings
- case_parties
- notes
- status_histories

## 📦 Frontend Assets

### Required CDN Links (add to layout head)
```html
<!-- Tailwind CSS -->
<script src="https://cdn.tailwindcss.com"></script>

<!-- Font Awesome Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<!-- Chart.js for data visualization -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<!-- Alpine.js for interactivity -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<!-- Flatpickr for date/time pickers -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
```

## 🚀 Quick Start Commands

### 1. Run migrations and seed data
```powershell
php artisan migrate:fresh --seed
```

### 2. Create controllers
```powershell
php artisan make:controller DashboardController
php artisan make:controller HearingController --resource
php artisan make:controller ProsecutorController --resource
php artisan make:controller ReportController
php artisan make:controller UserController --resource
```

### 3. Create seeders for test data
```powershell
php artisan make:seeder ProsecutorSeeder
php artisan make:seeder CaseSeeder
php artisan make:seeder HearingSeeder
```

### 4. Start the development server
```powershell
php artisan serve
```

## 📊 Dashboard Widgets Implementation

### Summary Cards Query Example
```php
// In DashboardController
$totalCases = CaseModel::count();
$newCasesThisMonth = CaseModel::whereMonth('created_at', now()->month)->count();
$pendingCases = CaseModel::where('status', 'Under Preliminary Investigation')->count();
$closedCases = CaseModel::where('status', 'For Archive')->count();
```

### Upcoming Hearings Query
```php
$upcomingHearings = Hearing::with(['case', 'case.prosecutor'])
    ->where('hearing_date', '>=', now())
    ->orderBy('hearing_date', 'asc')
    ->take(10)
    ->get();
```

### Case Status Distribution
```php
$statusDistribution = CaseModel::select('status', DB::raw('count(*) as count'))
    ->groupBy('status')
    ->get();
```

## 🎯 Next Steps

1. **Run the server** and test the login page at http://localhost:8000/login
2. **Create the admin layout** with sidebar and navbar
3. **Build the dashboard** with widgets
4. **Implement case management** forms and lists
5. **Add hearings module** with calendar view
6. **Create reports** and user management

## 📝 Notes

- All forms should have CSRF protection (`@csrf`)
- Use Laravel validation for all inputs
- Implement middleware for role-based access
- Add loading states and empty states
- Use Alpine.js for dropdown menus and modals
- Implement proper error handling and user feedback

---

**Status**: Authentication pages complete ✅  
**Next Priority**: Create admin layout and dashboard
