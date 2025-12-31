<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Prosecutor Case Tracking System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    @stack('styles')
</head>
<body class="bg-slate-50" x-data="{ sidebarOpen: true }">
    
    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0">
        <div class="flex flex-col h-full">
            <!-- Logo/Brand -->
            <div class="flex items-center gap-3 px-6 py-5 border-b border-gray-200">
                <i class="fas fa-balance-scale text-2xl text-blue-900"></i>
                <div>
                    <h1 class="text-lg font-bold text-gray-900">Prosecutor</h1>
                    <p class="text-xs text-gray-500">Case Tracking</p>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-6 overflow-y-auto">
                <ul class="space-y-1">
                    <li>
                        <a href="/dashboard" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg {{ request()->is('dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }} transition">
                            <i class="fas fa-th-large w-5"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    
                    <!-- Cases Section -->
                    <li class="pt-4">
                        <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Case Management</p>
                    </li>
                    <li>
                        <a href="/cases" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg {{ request()->is('cases*') && !request()->is('cases/create') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }} transition">
                            <i class="fas fa-folder-open w-5"></i>
                            <span>All Cases</span>
                        </a>
                    </li>
                    <li>
                        <a href="/cases/create" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg {{ request()->is('cases/create') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }} transition">
                            <i class="fas fa-plus-circle w-5"></i>
                            <span>Add New Case</span>
                        </a>
                    </li>
                    <li>
                        <a href="/hearings" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg {{ request()->is('hearings*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }} transition">
                            <i class="fas fa-gavel w-5"></i>
                            <span>Hearings</span>
                        </a>
                    </li>
                    
                    <!-- Organization Section -->
                    <li class="pt-4">
                        <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Organization</p>
                    </li>
                    <li>
                        <a href="/prosecutors" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg {{ request()->is('prosecutors*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }} transition">
                            <i class="fas fa-user-tie w-5"></i>
                            <span>Prosecutors</span>
                        </a>
                    </li>
                    <li>
                        <a href="/reports" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg {{ request()->is('reports*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }} transition">
                            <i class="fas fa-chart-bar w-5"></i>
                            <span>Reports</span>
                        </a>
                    </li>
                    
                    <!-- Admin Section -->
                    @if(auth()->check() && auth()->user()->isAdmin())
                    <li class="pt-4">
                        <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Administration</p>
                    </li>
                    <li>
                        <a href="/users" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg {{ request()->is('users*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }} transition">
                            <i class="fas fa-users-cog w-5"></i>
                            <span>User Management</span>
                        </a>
                    </li>
                    @endif
                    
                    <!-- Settings -->
                    <li class="pt-4">
                        <a href="/settings" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg {{ request()->is('settings*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }} transition">
                            <i class="fas fa-cog w-5"></i>
                            <span>Settings</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- User Profile (Bottom) -->
            <div class="px-4 py-4 border-t border-gray-200">
                <div class="flex items-center gap-3 px-3 py-2">
                    <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-semibold">
                        {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name ?? 'User' }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ auth()->user()->role ?? 'Prosecutor' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="lg:pl-64">
        <!-- Top Navbar -->
        <header class="sticky top-0 z-40 bg-white border-b border-gray-200">
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <!-- Mobile menu button -->
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-md text-gray-600 hover:bg-gray-100">
                        <i class="fas fa-bars text-xl"></i>
                    </button>

                    <!-- Page Title -->
                    <div class="flex-1 lg:flex-none">
                        <h2 class="text-xl font-semibold text-gray-900">@yield('page-title', 'Dashboard')</h2>
                    </div>

                    <!-- Right side -->
                    <div class="flex items-center gap-4">
                        <!-- Notifications -->
                        <button class="relative p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition" x-data="{ open: false }" @click="open = !open">
                            <i class="fas fa-bell text-lg"></i>
                            <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                        </button>

                        <!-- User Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 transition">
                                <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white text-sm font-semibold">
                                    {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                                </div>
                                <span class="hidden sm:block text-sm font-medium text-gray-700">{{ auth()->user()->name ?? 'User' }}</span>
                                <i class="fas fa-chevron-down text-xs text-gray-500"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1">
                                <a href="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <i class="fas fa-user w-5"></i> My Profile
                                </a>
                                <a href="/settings" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <i class="fas fa-cog w-5"></i> Settings
                                </a>
                                <hr class="my-1">
                                <form method="POST" action="/logout">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                        <i class="fas fa-sign-out-alt w-5"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="p-4 sm:p-6 lg:p-8">
            <!-- Alerts -->
            @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-start gap-3">
                <i class="fas fa-check-circle text-green-500 mt-0.5"></i>
                <div>
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
                <button class="ml-auto text-green-500 hover:text-green-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg flex items-start gap-3">
                <i class="fas fa-exclamation-circle text-red-500 mt-0.5"></i>
                <div>
                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                </div>
                <button class="ml-auto text-red-500 hover:text-red-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-600 bg-opacity-75 lg:hidden"></div>

    @stack('scripts')
</body>
</html>
