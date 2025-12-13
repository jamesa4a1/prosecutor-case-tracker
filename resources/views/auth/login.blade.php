<x-layouts.guest title="Login">
    <div class="min-h-screen flex">
        <!-- Left Side - Hero Section -->
        <div class="hidden lg:flex lg:w-1/2 hero-pattern flex-col justify-center items-center p-12">
            <div class="text-center text-white max-w-md">
                <div class="mb-8">
                    <i class="fas fa-balance-scale text-7xl opacity-90"></i>
                </div>
                <h1 class="text-4xl font-bold mb-4">Prosecutor Case Tracking System</h1>
                <p class="text-lg text-blue-100 mb-8">
                    Streamlined case management for the modern prosecutor's office. 
                    Track cases, manage hearings, and collaborate efficiently.
                </p>
                <div class="flex justify-center space-x-8 text-blue-100">
                    <div class="text-center">
                        <i class="fas fa-folder-open text-3xl mb-2"></i>
                        <p class="text-sm">Case Management</p>
                    </div>
                    <div class="text-center">
                        <i class="fas fa-calendar-check text-3xl mb-2"></i>
                        <p class="text-sm">Hearing Schedules</p>
                    </div>
                    <div class="text-center">
                        <i class="fas fa-chart-line text-3xl mb-2"></i>
                        <p class="text-sm">Reports & Analytics</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Side - Login Form -->
        <div class="w-full lg:w-1/2 flex flex-col justify-center items-center p-8 bg-white">
            <div class="w-full max-w-md">
                <!-- Mobile Logo -->
                <div class="lg:hidden text-center mb-8">
                    <i class="fas fa-balance-scale text-5xl text-primary-600 mb-4"></i>
                    <h1 class="text-2xl font-bold text-gray-900">PCT System</h1>
                </div>
                
                <!-- Welcome Text -->
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-900">Welcome Back</h2>
                    <p class="text-gray-600 mt-2">Sign in to continue to your account</p>
                </div>
                
                <!-- Error Messages -->
                @if($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                </div>
                @endif
                
                @if(session('status'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <span>{{ session('status') }}</span>
                    </div>
                </div>
                @endif
                
                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf
                    
                    <!-- Role Dropdown -->
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                            Login As <span class="text-red-500">*</span>
                        </label>
                        <select id="role" name="role" required class="w-full pl-3 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors">
                            <option value="">Select Role</option>
                            <option value="Admin" {{ old('role') == 'Admin' ? 'selected' : '' }}>Admin</option>
                            <option value="Prosecutor" {{ old('role') == 'Prosecutor' ? 'selected' : '' }}>Prosecutor</option>
                            <option value="Clerk" {{ old('role') == 'Clerk' ? 'selected' : '' }}>Clerk</option>
                        </select>
                        @error('role')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                value="{{ old('email') }}"
                                required 
                                autofocus
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors @error('email') border-red-500 @enderror"
                                placeholder="you@example.com"
                            >
                        </div>
                        @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                required
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors @error('password') border-red-500 @enderror"
                                placeholder="••••••••"
                            >
                        </div>
                        @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input 
                                type="checkbox" 
                                name="remember" 
                                class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500"
                            >
                            <span class="ml-2 text-sm text-gray-600">Remember me</span>
                        </label>
                        <a href="{{ route('password.request') }}" class="text-sm text-primary-600 hover:text-primary-700 hover:underline">
                            Forgot password?
                        </a>
                    </div>
                    
                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="w-full bg-primary-600 text-white py-3 px-4 rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-colors font-medium"
                    >
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Sign In
                    </button>
                </form>
                
                <!-- Footer -->
                <div class="mt-8 text-center">
                    <p class="text-sm text-gray-500">
                        &copy; {{ date('Y') }} Prosecutor Case Tracking System
                    </p>
                    <p class="text-xs text-gray-400 mt-1">
                        Authorized Personnel Only
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.guest>
