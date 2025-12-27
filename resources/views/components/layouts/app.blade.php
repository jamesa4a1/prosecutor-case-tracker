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
        
        .sidebar {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        }
        
        .sidebar-link {
            transition: all 0.3s ease;
            color: #cbd5e1;
        }
        
        .sidebar-link:hover {
            background-color: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
            padding-left: 1.25rem;
        }
        
        .sidebar-link.active {
            background-color: rgba(59, 130, 246, 0.2);
            color: #3b82f6;
            border-left: 4px solid #3b82f6;
            padding-left: 1rem;
        }
        
        .stat-card {
            transition: all 0.3s ease;
            border: 1px solid #e2e8f0;
        }
        
        .stat-card:hover {
            box-shadow: 0 20px 40px rgba(59, 130, 246, 0.1);
            transform: translateY(-2px);
        }
        
        .badge {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            display: inline-block;
        }
        
        .badge-blue {
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        .badge-yellow {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .badge-green {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .badge-red {
            background-color: #fee2e2;
            color: #7f1d1d;
        }
        
        .badge-purple {
            background-color: #ede9fe;
            color: #5b21b6;
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-50 min-h-screen" x-data="{ sidebarOpen: true, mobileMenuOpen: false }">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside 
            class="fixed inset-y-0 left-0 z-50 w-64 sidebar shadow-2xl transform transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-0"
            :class="{ '-translate-x-full': !mobileMenuOpen, 'translate-x-0': mobileMenuOpen }"
        >
            <!-- Logo/Brand -->
            <div class="flex items-center justify-between h-20 px-6 border-b border-slate-700">
                <div class="flex items-center space-x-3">
                    <div class="w-15 h-15 flex items-center justify-center">
                        <img src="{{ asset('images/logo.png') }}" alt="AProsecutor Logo" class="w-10 h-10 object-contain rounded-lg">
                    </div>
                    <div>
                        <p class="text-white font-bold text-sm">PCTS</p>
                        <p class="text-slate-400 text-xs">Case Tracking</p>
                    </div>
                </div>
                <button @click="mobileMenuOpen = false" class="lg:hidden text-slate-400 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <!-- Navigation -->
            <nav class="px-4 py-6 space-y-2 overflow-y-auto" style="max-height: calc(100vh - 200px);">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider px-4 mb-4">Main Menu</p>
                
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" 
                   class="sidebar-link flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-th-large w-5 mr-3"></i>
                    <span>Dashboard</span>
                </a>
                
                <!-- Cases -->
                <a href="{{ route('cases.index') }}" 
                   class="sidebar-link flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('cases.*') ? 'active' : '' }}">
                    <i class="fas fa-folder-open w-5 mr-3"></i>
                    <span>Cases</span>
                </a>
                
                <!-- Hearings -->
                <a href="{{ route('hearings.index') }}" 
                   class="sidebar-link flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('hearings.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check w-5 mr-3"></i>
                    <span>Hearings</span>
                </a>
                
                <!-- Prosecutors -->
                <a href="{{ route('prosecutors.index') }}" 
                   class="sidebar-link flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('prosecutors.*') ? 'active' : '' }}">
                    <i class="fas fa-users w-5 mr-3"></i>
                    <span>Prosecutors</span>
                </a>
                
                <!-- Reports -->
                <a href="{{ route('reports.index') }}" 
                   class="sidebar-link flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-line w-5 mr-3"></i>
                    <span>Reports</span>
                </a>
                
                <hr class="my-4 border-slate-700">
                
                <!-- Settings -->
                <a href="{{ route('settings.index') }}" 
                   class="sidebar-link flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                    <i class="fas fa-cog w-5 mr-3"></i>
                    <span>Settings</span>
                </a>
            </nav>
            
            <!-- User Profile (Bottom) -->
            <div class="absolute bottom-0 left-0 right-0 px-6 py-4 border-t border-slate-700 bg-slate-900">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg overflow-hidden">
                        @if(auth()->user()->avatar)
                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="w-full h-full object-cover" alt="{{ auth()->user()->name }}">
                        @else
                            <span class="text-white font-bold text-lg">{{ strtoupper(substr(auth()->user()->name ?? 'G', 0, 2)) }}</span>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name ?? 'Guest' }}</p>
                        <p class="text-xs text-slate-400">{{ auth()->user()->role ?? 'Prosecutor' }}</p>
                    </div>
                    <button class="text-slate-400 hover:text-white transition-colors">
                        <i class="fas fa-chevron-down"></i>
                    </button>
                </div>
            </div>
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
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center overflow-hidden">
                                    @if(auth()->user()->avatar)
                                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="w-full h-full object-cover" alt="{{ auth()->user()->name }}">
                                    @else
                                        <span class="text-white font-semibold text-xs">{{ strtoupper(substr(auth()->user()->name ?? 'G', 0, 2)) }}</span>
                                    @endif
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
                
                @if(isset($errors) && $errors->any())
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
                
                {{ $slot }}
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
