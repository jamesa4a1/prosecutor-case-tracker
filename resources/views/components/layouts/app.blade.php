<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Prosecutor Case Tracker' }}</title>
    
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#1e40af',
                            700: '#1d4ed8',
                            800: '#1e3a8a',
                            900: '#1e3a5f',
                        },
                        accent: {
                            500: '#10b981',
                            600: '#059669',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Flatpickr for date/time -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    
    <style>
        [x-cloak] { display: none !important; }
        .sidebar-link.active {
            background-color: #1e40af;
            color: white;
        }
        .sidebar-link:hover:not(.active) {
            background-color: #f3f4f6;
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-50 min-h-screen" x-data="{ sidebarOpen: true, mobileMenuOpen: false }">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside 
            class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transform transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-0"
            :class="{ '-translate-x-full': !mobileMenuOpen, 'translate-x-0': mobileMenuOpen }"
        >
            <!-- Logo/Brand -->
            <div class="flex items-center justify-between h-16 px-6 bg-primary-600">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-balance-scale text-white text-2xl"></i>
                    <span class="text-white font-bold text-lg">PCT System</span>
                </div>
                <button @click="mobileMenuOpen = false" class="lg:hidden text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <!-- User Info -->
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center">
                        <i class="fas fa-user text-primary-600"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name ?? 'Guest' }}</p>
                        <p class="text-xs text-gray-500">{{ auth()->user()->role ?? 'No Role' }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Navigation -->
            <nav class="px-4 py-4 space-y-1 overflow-y-auto" style="max-height: calc(100vh - 180px);">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" 
                   class="sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'active' : 'text-gray-700' }}">
                    <i class="fas fa-home w-5 mr-3"></i>
                    Dashboard
                </a>
                
                <!-- Cases -->
                <div x-data="{ open: {{ request()->routeIs('cases.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" 
                            class="sidebar-link w-full flex items-center justify-between px-4 py-3 text-sm font-medium text-gray-700 rounded-lg transition-colors">
                        <span class="flex items-center">
                            <i class="fas fa-folder-open w-5 mr-3"></i>
                            Cases
                        </span>
                        <i class="fas fa-chevron-down transition-transform" :class="{ 'rotate-180': open }"></i>
                    </button>
                    <div x-show="open" x-cloak class="ml-8 mt-1 space-y-1">
                        <a href="{{ route('cases.index') }}" 
                           class="sidebar-link flex items-center px-4 py-2 text-sm text-gray-600 rounded-lg {{ request()->routeIs('cases.index') ? 'active' : '' }}">
                            <i class="fas fa-list w-4 mr-2"></i>
                            All Cases
                        </a>
                        <a href="{{ route('cases.create') }}" 
                           class="sidebar-link flex items-center px-4 py-2 text-sm text-gray-600 rounded-lg {{ request()->routeIs('cases.create') ? 'active' : '' }}">
                            <i class="fas fa-plus w-4 mr-2"></i>
                            Add New Case
                        </a>
                    </div>
                </div>
                
                <!-- Hearings -->
                <a href="{{ route('hearings.index') }}" 
                   class="sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('hearings.*') ? 'active' : 'text-gray-700' }}">
                    <i class="fas fa-calendar-alt w-5 mr-3"></i>
                    Hearings
                </a>
                
                <!-- Prosecutors -->
                <a href="{{ route('prosecutors.index') }}" 
                   class="sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('prosecutors.*') ? 'active' : 'text-gray-700' }}">
                    <i class="fas fa-user-tie w-5 mr-3"></i>
                    Prosecutors
                </a>
                
                <!-- Reports -->
                <a href="{{ route('reports.index') }}" 
                   class="sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('reports.*') ? 'active' : 'text-gray-700' }}">
                    <i class="fas fa-chart-bar w-5 mr-3"></i>
                    Reports
                </a>
                
                @if(auth()->user() && auth()->user()->isAdmin())
                <!-- User Management (Admin Only) -->
                <a href="{{ route('admin.users.index') }}" 
                   class="sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.users.*') ? 'active' : 'text-gray-700' }}">
                    <i class="fas fa-users-cog w-5 mr-3"></i>
                    User Management
                </a>
                @endif
                
                <hr class="my-4 border-gray-200">
                
                <!-- Settings -->
                {{--
                <a href="#" class="sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors text-gray-400 cursor-not-allowed opacity-60">
                    <i class="fas fa-cog w-5 mr-3"></i>
                    Settings (Coming Soon)
                </a>
                --}}
            </nav>
        </aside>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Top Navbar -->
            <header class="sticky top-0 z-40 bg-white shadow-sm">
                <div class="flex items-center justify-between h-16 px-4 lg:px-8">
                    <!-- Left: Mobile menu button & Search -->
                    <div class="flex items-center space-x-4">
                        <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden text-gray-500 hover:text-gray-700">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <h1 class="text-xl font-semibold text-gray-800">{{ $header ?? 'Dashboard' }}</h1>
                    </div>
                    
                    <!-- Right: User dropdown -->
                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <button class="relative text-gray-500 hover:text-gray-700">
                            <i class="fas fa-bell text-xl"></i>
                            <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full text-xs text-white flex items-center justify-center">3</span>
                        </button>
                        
                        <!-- User Dropdown -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center space-x-3 text-gray-700 hover:text-gray-900">
                                <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center">
                                    <i class="fas fa-user text-primary-600 text-sm"></i>
                                </div>
                                <span class="hidden md:block text-sm font-medium">{{ auth()->user()->name ?? 'Guest' }}</span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            
                            <div x-show="open" 
                                 @click.away="open = false"
                                 x-cloak
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-50">
                                <div class="px-4 py-2 border-b border-gray-100">
                                    <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name ?? 'Guest' }}</p>
                                    <p class="text-xs text-gray-500">{{ auth()->user()->email ?? '' }}</p>
                                </div>
                                {{--
                                <a href="#" class="block px-4 py-2 text-sm text-gray-400 cursor-not-allowed opacity-60">
                                    <i class="fas fa-cog w-4 mr-2"></i>Settings (Coming Soon)
                                </a>
                                --}}
                                <hr class="my-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                        <i class="fas fa-sign-out-alt w-4 mr-2"></i>Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Page Content -->
            <main class="flex-1 p-4 lg:p-8">
                <!-- Flash Messages -->
                @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center justify-between" x-data="{ show: true }" x-show="show">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-3"></i>
                        {{ session('success') }}
                    </div>
                    <button @click="show = false" class="text-green-700 hover:text-green-900">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                @endif
                
                @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center justify-between" x-data="{ show: true }" x-show="show">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-3"></i>
                        {{ session('error') }}
                    </div>
                    <button @click="show = false" class="text-red-700 hover:text-red-900">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                @endif
                
                @if($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-exclamation-triangle mr-3"></i>
                        <span class="font-medium">Please fix the following errors:</span>
                    </div>
                    <ul class="list-disc list-inside ml-6 text-sm">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                @yield('content')
            </main>
            
            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200 px-4 lg:px-8 py-4">
                <p class="text-sm text-gray-500 text-center">
                    &copy; {{ date('Y') }} Prosecutor Case Tracking System. All rights reserved.
                </p>
            </footer>
        </div>
    </div>
    
    <!-- Mobile Overlay -->
    <div 
        x-show="mobileMenuOpen" 
        @click="mobileMenuOpen = false"
        class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
        x-cloak
    ></div>
    
    @stack('scripts')
</body>
</html>
