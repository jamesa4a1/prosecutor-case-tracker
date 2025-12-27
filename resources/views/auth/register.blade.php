<x-layouts.guest title="Register">
    <div class="min-h-screen flex">
        <!-- Left Side - Hero Section with Justice Theme -->
        <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden">
            <!-- Dark Navy Gradient Background -->
            <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-blue-900 to-slate-800"></div>
            
            <!-- Decorative Pattern Overlay -->
            <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.4&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
            
            <!-- Courthouse Silhouette Effect -->
            <div class="absolute bottom-0 left-0 right-0 h-1/3 bg-gradient-to-t from-black/30 to-transparent"></div>
            
            <!-- Gold Accent Line -->
            <div class="absolute top-0 left-0 w-1 h-full bg-gradient-to-b from-yellow-500 via-yellow-600 to-yellow-700"></div>
            
            <!-- Content -->
            <div class="relative z-10 flex flex-col justify-center items-center p-12 w-full">
                <div class="text-center text-white max-w-lg">
                    <!-- Scales of Justice Icon -->
                    <div class="mb-8">
                        <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-white/10 backdrop-blur-sm border border-white/20">
                            <i class="fas fa-balance-scale text-5xl text-yellow-400"></i>
                        </div>
                    </div>
                    
                    <!-- Title -->
                    <h1 class="text-4xl font-bold mb-4 tracking-tight">
                        Prosecution Case<br>Management System
                    </h1>
                    
                    <!-- Subtitle -->
                    <p class="text-lg text-blue-200 mb-10 leading-relaxed">
                        A secure, efficient platform for managing criminal cases, 
                        court hearings, and legal documentation for the Office of the Prosecutor.
                    </p>
                    
                    <!-- Features Grid -->
                    <div class="grid grid-cols-2 gap-6 text-left">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-yellow-500/20 flex items-center justify-center">
                                <i class="fas fa-shield-alt text-yellow-400"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-white">Secure Access</h3>
                                <p class="text-sm text-blue-200">Role-based authentication</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-yellow-500/20 flex items-center justify-center">
                                <i class="fas fa-folder-open text-yellow-400"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-white">Case Tracking</h3>
                                <p class="text-sm text-blue-200">Full case lifecycle</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-yellow-500/20 flex items-center justify-center">
                                <i class="fas fa-gavel text-yellow-400"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-white">Hearing Management</h3>
                                <p class="text-sm text-blue-200">Schedule & track hearings</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-yellow-500/20 flex items-center justify-center">
                                <i class="fas fa-chart-bar text-yellow-400"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-white">Analytics</h3>
                                <p class="text-sm text-blue-200">Performance insights</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Footer Badge -->
                    <div class="mt-12 pt-8 border-t border-white/10">
                        <p class="text-sm text-blue-300 uppercase tracking-wider">
                            <i class="fas fa-landmark mr-2"></i>
                            Office of the Prosecutor
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Side - Registration Form -->
        <div class="w-full lg:w-1/2 flex flex-col justify-center items-center p-6 sm:p-8 bg-gray-50">
            <div class="w-full max-w-lg">
                <!-- Mobile Header -->
                <div class="lg:hidden text-center mb-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-900 mb-4">
                        <i class="fas fa-balance-scale text-2xl text-yellow-400"></i>
                    </div>
                    <h1 class="text-xl font-bold text-gray-900">Prosecution Case Management</h1>
                </div>
                
                <!-- Form Header -->
                <div class="text-center mb-8">
                    <p class="text-xs font-semibold text-yellow-600 uppercase tracking-widest mb-2">
                        Prosecutor Access Portal
                    </p>
                    <h2 class="text-3xl font-bold text-gray-900">Create Account</h2>
                    <p class="text-gray-600 mt-2">Authorized personnel only – Office of the Prosecutor</p>
                </div>
                
                <!-- Error Summary -->
                @if($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-red-500 mt-0.5 mr-3"></i>
                        <div>
                            <h3 class="text-sm font-semibold text-red-800">Please correct the following errors:</h3>
                            <ul class="mt-2 text-sm text-red-700 list-disc list-inside space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Registration Form -->
                <form method="POST" action="{{ route('register.post') }}" class="space-y-5">
                    @csrf
                    
                    <!-- Full Name -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                value="{{ old('name') }}"
                                required 
                                autofocus
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('name') border-red-500 ring-red-500 @enderror"
                                placeholder="Juan Dela Cruz"
                            >
                        </div>
                        @error('name')
                        <p class="mt-1.5 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                        </p>
                        @enderror
                    </div>
                    
                    <!-- Official Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                            Official Email Address <span class="text-red-500">*</span>
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
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('email') border-red-500 ring-red-500 @enderror"
                                placeholder="prosecutor@doj.gov.ph"
                            >
                        </div>
                        @error('email')
                        <p class="mt-1.5 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                        </p>
                        @enderror
                    </div>
                    
                    <!-- Two Column: Role & Office -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Role Selection -->
                        <div>
                            <label for="role" class="block text-sm font-semibold text-gray-700 mb-2">
                                Role <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user-tag text-gray-400"></i>
                                </div>
                                <select 
                                    id="role" 
                                    name="role" 
                                    required
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors appearance-none bg-white @error('role') border-red-500 ring-red-500 @enderror"
                                >
                                    <option value="">Select Role</option>
                                    <option value="Admin" {{ old('role') == 'Admin' ? 'selected' : '' }}>Administrator</option>
                                    <option value="Prosecutor" {{ old('role') == 'Prosecutor' ? 'selected' : '' }}>Prosecutor</option>
                                    <option value="Clerk" {{ old('role') == 'Clerk' ? 'selected' : '' }}>Clerk</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400 text-sm"></i>
                                </div>
                            </div>
                            @error('role')
                            <p class="mt-1.5 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                            @enderror
                        </div>
                        
                        <!-- Position/Title -->
                        <div>
                            <label for="position" class="block text-sm font-semibold text-gray-700 mb-2">
                                Position Title <span class="text-gray-400 font-normal">(Optional)</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-briefcase text-gray-400"></i>
                                </div>
                                <input 
                                    type="text" 
                                    id="position" 
                                    name="position" 
                                    value="{{ old('position') }}"
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('position') border-red-500 ring-red-500 @enderror"
                                    placeholder="Assistant City Prosecutor"
                                >
                            </div>
                            @error('position')
                            <p class="mt-1.5 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Office/Station -->
                    <div>
                        <label for="office" class="block text-sm font-semibold text-gray-700 mb-2">
                            Office / Station <span class="text-gray-400 font-normal">(Optional)</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-building text-gray-400"></i>
                            </div>
                            <input 
                                type="text" 
                                id="office" 
                                name="office" 
                                value="{{ old('office') }}"
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('office') border-red-500 ring-red-500 @enderror"
                                placeholder="City Prosecutor's Office – Mandaue"
                            >
                        </div>
                        @error('office')
                        <p class="mt-1.5 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                        </p>
                        @enderror
                    </div>
                    
                    <!-- Phone (Optional) -->
                    <div>
                        <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">
                            Contact Number <span class="text-gray-400 font-normal">(Optional)</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-phone text-gray-400"></i>
                            </div>
                            <input 
                                type="tel" 
                                id="phone" 
                                name="phone" 
                                value="{{ old('phone') }}"
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('phone') border-red-500 ring-red-500 @enderror"
                                placeholder="+63 917 123 4567"
                            >
                        </div>
                        @error('phone')
                        <p class="mt-1.5 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                        </p>
                        @enderror
                    </div>
                    
                    <!-- Password Fields -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
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
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('password') border-red-500 ring-red-500 @enderror"
                                    placeholder="Min. 8 characters"
                                >
                            </div>
                            @error('password')
                            <p class="mt-1.5 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                                Confirm Password <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                                <input 
                                    type="password" 
                                    id="password_confirmation" 
                                    name="password_confirmation" 
                                    required
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    placeholder="Re-enter password"
                                >
                            </div>
                        </div>
                    </div>
                    
                    <!-- Password Requirements -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                        <p class="text-xs text-blue-700 font-medium mb-2">
                            <i class="fas fa-info-circle mr-1"></i> Password Requirements:
                        </p>
                        <ul class="text-xs text-blue-600 space-y-1 ml-4">
                            <li class="flex items-center">
                                <i class="fas fa-check-circle mr-2 text-blue-400"></i>
                                At least 8 characters long
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check-circle mr-2 text-blue-400"></i>
                                Contains at least one letter
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check-circle mr-2 text-blue-400"></i>
                                Contains at least one number
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Terms Agreement -->
                    <div class="flex items-start">
                        <input 
                            type="checkbox" 
                            id="terms" 
                            name="terms" 
                            required
                            class="mt-1 w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                        >
                        <label for="terms" class="ml-2 text-sm text-gray-600">
                            I certify that I am an authorized personnel of the Office of the Prosecutor and agree to the 
                            <a href="#" class="text-blue-600 hover:underline">Terms of Service</a> and 
                            <a href="#" class="text-blue-600 hover:underline">Privacy Policy</a>.
                        </label>
                    </div>
                    
                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="w-full bg-slate-900 text-white py-3.5 px-4 rounded-lg hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition-all duration-200 font-semibold text-base shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <i class="fas fa-user-plus mr-2"></i>
                        Register Account
                    </button>
                    
                    <!-- Divider -->
                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-gray-50 text-gray-500">Already have an account?</span>
                        </div>
                    </div>
                    
                    <!-- Login Link -->
                    <a 
                        href="{{ route('login') }}" 
                        class="w-full inline-flex justify-center items-center bg-white border-2 border-slate-900 text-slate-900 py-3 px-4 rounded-lg hover:bg-slate-900 hover:text-white focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transition-all duration-200 font-semibold"
                    >
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Sign In Instead
                    </a>
                </form>
                
                <!-- Footer -->
                <div class="mt-8 text-center">
                    <p class="text-xs text-gray-500">
                        © {{ date('Y') }} Prosecutor Case Tracking System. All rights reserved.
                    </p>
                    <p class="text-xs text-gray-400 mt-1">
                        <i class="fas fa-shield-alt mr-1"></i>
                        Protected by Government Security Protocols
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.guest>
