<x-layouts.app title="Settings" header="Settings">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900">Settings</h1>
        <p class="text-slate-600 mt-2">Manage your account preferences and system configuration</p>
    </div>

    <div class="flex flex-col lg:flex-row gap-8" x-data="{ activeTab: 'profile' }">
        <!-- Settings Navigation -->
        <div class="lg:w-64 flex-shrink-0">
            <nav class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <button @click="activeTab = 'profile'" 
                    :class="{ 'bg-blue-50 border-l-4 border-blue-600 text-blue-700': activeTab === 'profile', 'text-slate-600 hover:bg-slate-50': activeTab !== 'profile' }"
                    class="w-full flex items-center px-4 py-3 text-left transition-colors">
                    <i class="fas fa-user w-5 mr-3"></i>
                    <span class="font-medium">Profile</span>
                </button>
                <button @click="activeTab = 'security'" 
                    :class="{ 'bg-blue-50 border-l-4 border-blue-600 text-blue-700': activeTab === 'security', 'text-slate-600 hover:bg-slate-50': activeTab !== 'security' }"
                    class="w-full flex items-center px-4 py-3 text-left border-t border-slate-100 transition-colors">
                    <i class="fas fa-shield-alt w-5 mr-3"></i>
                    <span class="font-medium">Account & Security</span>
                </button>
                <button @click="activeTab = 'notifications'" 
                    :class="{ 'bg-blue-50 border-l-4 border-blue-600 text-blue-700': activeTab === 'notifications', 'text-slate-600 hover:bg-slate-50': activeTab !== 'notifications' }"
                    class="w-full flex items-center px-4 py-3 text-left border-t border-slate-100 transition-colors">
                    <i class="fas fa-bell w-5 mr-3"></i>
                    <span class="font-medium">Notifications</span>
                </button>
                @if(auth()->user()->isAdmin())
                <button @click="activeTab = 'system'" 
                    :class="{ 'bg-blue-50 border-l-4 border-blue-600 text-blue-700': activeTab === 'system', 'text-slate-600 hover:bg-slate-50': activeTab !== 'system' }"
                    class="w-full flex items-center px-4 py-3 text-left border-t border-slate-100 transition-colors">
                    <i class="fas fa-cogs w-5 mr-3"></i>
                    <span class="font-medium">System</span>
                </button>
                @endif
            </nav>

            <!-- Help Card -->
            <div class="mt-6 bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl p-5 text-white">
                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center mb-3">
                    <i class="fas fa-question-circle text-xl"></i>
                </div>
                <h4 class="font-semibold mb-2">Need Help?</h4>
                <p class="text-blue-100 text-sm mb-3">Contact support for assistance with your account settings.</p>
                <a href="#" class="inline-flex items-center text-sm font-medium text-white hover:text-blue-100">
                    Get Support <i class="fas fa-arrow-right ml-2 text-xs"></i>
                </a>
            </div>
        </div>

        <!-- Settings Content -->
        <div class="flex-1">
            <!-- Profile Tab -->
            <div x-show="activeTab === 'profile'" x-cloak>
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-200">
                        <h3 class="text-lg font-semibold text-slate-900">Profile Information</h3>
                        <p class="text-sm text-slate-500 mt-1">Update your personal information and profile picture</p>
                    </div>
                    <form action="{{ route('settings.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-6">
                        @csrf
                        @method('PUT')
                        
                        <!-- Avatar Section -->
                        <div class="flex items-center space-x-6 mb-8 pb-8 border-b border-slate-200" x-data="avatarUpload()">
                            <div class="relative">
                                <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center shadow-lg overflow-hidden">
                                    @if(auth()->user()->avatar)
                                        <img id="avatarPreview" src="{{ asset('storage/' . auth()->user()->avatar) }}" class="w-full h-full rounded-full object-cover" alt="Avatar">
                                    @else
                                        <span id="avatarInitials" class="text-3xl font-bold text-white">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                                        <img id="avatarPreview" src="" class="w-full h-full rounded-full object-cover hidden" alt="Avatar">
                                    @endif
                                </div>
                                <label class="absolute bottom-0 right-0 w-8 h-8 bg-white rounded-full shadow-md flex items-center justify-center cursor-pointer hover:bg-slate-50 transition-colors border border-slate-200">
                                    <i class="fas fa-camera text-slate-500 text-sm"></i>
                                    <input type="file" name="avatar" id="avatarInput" class="hidden" accept="image/*" @change="previewAvatar($event)">
                                </label>
                            </div>
                            <div>
                                <h4 class="font-semibold text-slate-900">{{ auth()->user()->name }}</h4>
                                <p class="text-sm text-slate-500">{{ auth()->user()->role?->value ?? 'User' }}</p>
                                <p class="text-xs text-slate-400 mt-1">JPG, PNG or GIF. Max 2MB.</p>
                                <button type="button" x-show="hasNewAvatar" @click="removeAvatar()" class="text-xs text-red-600 hover:text-red-700 mt-1">Remove new photo</button>
                            </div>
                        </div>

                        <!-- Profile Form -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Full Name</label>
                                <input type="text" name="name" value="{{ auth()->user()->name }}" 
                                    class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Email Address</label>
                                <input type="email" name="email" value="{{ auth()->user()->email }}" 
                                    class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Role</label>
                                <input type="text" value="{{ auth()->user()->role?->value ?? 'User' }}" disabled
                                    class="w-full px-4 py-3 rounded-lg border border-slate-200 bg-slate-50 text-slate-500 cursor-not-allowed">
                                <p class="text-xs text-slate-400 mt-1">Contact admin to change role</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Office / Station</label>
                                <input type="text" name="office" value="{{ auth()->user()->office ?? '' }}" 
                                    class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    placeholder="Enter your office location">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-slate-700 mb-2">Phone Number</label>
                                <input type="tel" name="phone" value="{{ auth()->user()->phone ?? '' }}" 
                                    class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    placeholder="+63 912 345 6789">
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-slate-200 flex justify-end">
                            <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                                <i class="fas fa-save mr-2"></i>Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Security Tab -->
            <div x-show="activeTab === 'security'" x-cloak>
                <div class="space-y-6">
                    <!-- Change Password -->
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="px-6 py-5 border-b border-slate-200">
                            <h3 class="text-lg font-semibold text-slate-900">Change Password</h3>
                            <p class="text-sm text-slate-500 mt-1">Ensure your account uses a strong password</p>
                        </div>
                        <form action="{{ route('settings.password.update') }}" method="POST" class="p-6">
                            @csrf
                            @method('PUT')
                            
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Current Password</label>
                                    <input type="password" name="current_password" 
                                        class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        placeholder="Enter current password">
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-2">New Password</label>
                                        <input type="password" name="password" 
                                            class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                            placeholder="Enter new password">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-2">Confirm New Password</label>
                                        <input type="password" name="password_confirmation" 
                                            class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                            placeholder="Confirm new password">
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end">
                                <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                                    <i class="fas fa-key mr-2"></i>Update Password
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Two-Factor Authentication -->
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="px-6 py-5 border-b border-slate-200">
                            <h3 class="text-lg font-semibold text-slate-900">Two-Factor Authentication</h3>
                            <p class="text-sm text-slate-500 mt-1">Add additional security to your account</p>
                        </div>
                        <div class="p-6">
                            <div class="flex items-start justify-between">
                                <div class="flex items-start space-x-4">
                                    <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-shield-alt text-amber-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-slate-900">2FA is currently disabled</h4>
                                        <p class="text-sm text-slate-500 mt-1">
                                            Two-factor authentication adds an extra layer of security to your account by requiring a verification code in addition to your password.
                                        </p>
                                    </div>
                                </div>
                                <button type="button" class="px-4 py-2 bg-slate-100 text-slate-700 font-medium rounded-lg hover:bg-slate-200 transition-colors">
                                    Enable
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Login Activity -->
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="px-6 py-5 border-b border-slate-200">
                            <h3 class="text-lg font-semibold text-slate-900">Recent Login Activity</h3>
                            <p class="text-sm text-slate-500 mt-1">Monitor your account access history</p>
                        </div>
                        <div class="divide-y divide-slate-100">
                            <div class="px-6 py-4 flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-laptop text-green-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-slate-900">Windows • Chrome</p>
                                        <p class="text-sm text-slate-500">Manila, Philippines • Current session</p>
                                    </div>
                                </div>
                                <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full">Active</span>
                            </div>
                            <div class="px-6 py-4 flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-mobile-alt text-slate-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-slate-900">Android • Chrome Mobile</p>
                                        <p class="text-sm text-slate-500">Quezon City, Philippines • 2 days ago</p>
                                    </div>
                                </div>
                                <button type="button" class="text-red-600 hover:text-red-700 text-sm font-medium">Revoke</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notifications Tab -->
            <div x-show="activeTab === 'notifications'" x-cloak>
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-200">
                        <h3 class="text-lg font-semibold text-slate-900">Notification Preferences</h3>
                        <p class="text-sm text-slate-500 mt-1">Choose which notifications you want to receive</p>
                    </div>
                    <form action="{{ route('settings.notifications.update') }}" method="POST" class="p-6">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            <!-- Email Notifications -->
                            <div>
                                <h4 class="font-medium text-slate-900 mb-4 flex items-center">
                                    <i class="fas fa-envelope mr-2 text-slate-400"></i>
                                    Email Notifications
                                </h4>
                                <div class="space-y-4 ml-6">
                                    <label class="flex items-center justify-between cursor-pointer group">
                                        <div>
                                            <span class="font-medium text-slate-700">New Case Assignment</span>
                                            <p class="text-sm text-slate-500">Get notified when a new case is assigned to you</p>
                                        </div>
                                        <div class="relative">
                                            <input type="checkbox" name="notify_case_assignment" checked class="sr-only peer">
                                            <div class="w-11 h-6 bg-slate-200 peer-focus:ring-4 peer-focus:ring-blue-100 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                        </div>
                                    </label>
                                    <label class="flex items-center justify-between cursor-pointer group">
                                        <div>
                                            <span class="font-medium text-slate-700">Hearing Reminders</span>
                                            <p class="text-sm text-slate-500">Receive reminders before scheduled hearings</p>
                                        </div>
                                        <div class="relative">
                                            <input type="checkbox" name="notify_hearing_reminder" checked class="sr-only peer">
                                            <div class="w-11 h-6 bg-slate-200 peer-focus:ring-4 peer-focus:ring-blue-100 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                        </div>
                                    </label>
                                    <label class="flex items-center justify-between cursor-pointer group">
                                        <div>
                                            <span class="font-medium text-slate-700">Case Status Changes</span>
                                            <p class="text-sm text-slate-500">Get notified when case status is updated</p>
                                        </div>
                                        <div class="relative">
                                            <input type="checkbox" name="notify_status_change" checked class="sr-only peer">
                                            <div class="w-11 h-6 bg-slate-200 peer-focus:ring-4 peer-focus:ring-blue-100 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                        </div>
                                    </label>
                                    <label class="flex items-center justify-between cursor-pointer group">
                                        <div>
                                            <span class="font-medium text-slate-700">New Notes Added</span>
                                            <p class="text-sm text-slate-500">Get notified when notes are added to your cases</p>
                                        </div>
                                        <div class="relative">
                                            <input type="checkbox" name="notify_new_notes" class="sr-only peer">
                                            <div class="w-11 h-6 bg-slate-200 peer-focus:ring-4 peer-focus:ring-blue-100 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- System Notifications -->
                            <div class="pt-6 border-t border-slate-200">
                                <h4 class="font-medium text-slate-900 mb-4 flex items-center">
                                    <i class="fas fa-bell mr-2 text-slate-400"></i>
                                    System Notifications
                                </h4>
                                <div class="space-y-4 ml-6">
                                    <label class="flex items-center justify-between cursor-pointer group">
                                        <div>
                                            <span class="font-medium text-slate-700">Weekly Summary</span>
                                            <p class="text-sm text-slate-500">Receive a weekly digest of your cases and hearings</p>
                                        </div>
                                        <div class="relative">
                                            <input type="checkbox" name="notify_weekly_summary" checked class="sr-only peer">
                                            <div class="w-11 h-6 bg-slate-200 peer-focus:ring-4 peer-focus:ring-blue-100 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                        </div>
                                    </label>
                                    <label class="flex items-center justify-between cursor-pointer group">
                                        <div>
                                            <span class="font-medium text-slate-700">Security Alerts</span>
                                            <p class="text-sm text-slate-500">Get notified about important security updates</p>
                                        </div>
                                        <div class="relative">
                                            <input type="checkbox" name="notify_security" checked class="sr-only peer">
                                            <div class="w-11 h-6 bg-slate-200 peer-focus:ring-4 peer-focus:ring-blue-100 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-slate-200 flex justify-end">
                            <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                                <i class="fas fa-save mr-2"></i>Save Preferences
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- System Tab (Admin Only) -->
            @if(auth()->user()->isAdmin())
            <div x-show="activeTab === 'system'" x-cloak>
                <div class="space-y-6">
                    <!-- Status Management -->
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="px-6 py-5 border-b border-slate-200">
                            <h3 class="text-lg font-semibold text-slate-900">Case Status Configuration</h3>
                            <p class="text-sm text-slate-500 mt-1">Manage available case statuses in the system</p>
                        </div>
                        <div class="p-6">
                            <div class="flex flex-wrap gap-2 mb-4">
                                @php
                                    $statuses = ['Pending', 'Under Investigation', 'For Filing', 'Filed', 'For Resolution', 'Closed', 'Archived'];
                                    $colors = ['bg-yellow-100 text-yellow-800', 'bg-blue-100 text-blue-800', 'bg-purple-100 text-purple-800', 'bg-green-100 text-green-800', 'bg-orange-100 text-orange-800', 'bg-slate-100 text-slate-800', 'bg-red-100 text-red-800'];
                                @endphp
                                @foreach($statuses as $index => $status)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $colors[$index] }}">
                                    {{ $status }}
                                    <button type="button" class="ml-2 hover:text-red-600"><i class="fas fa-times text-xs"></i></button>
                                </span>
                                @endforeach
                            </div>
                            <div class="flex gap-2">
                                <input type="text" placeholder="Add new status..." class="flex-1 px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                    <i class="fas fa-plus mr-2"></i>Add
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Office/Station Management -->
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="px-6 py-5 border-b border-slate-200">
                            <h3 class="text-lg font-semibold text-slate-900">Offices & Stations</h3>
                            <p class="text-sm text-slate-500 mt-1">Manage prosecutor offices and police stations</p>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div class="p-4 bg-slate-50 rounded-lg border border-slate-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="font-medium text-slate-900">Main Prosecution Office</span>
                                        <button type="button" class="text-slate-400 hover:text-red-600"><i class="fas fa-trash text-sm"></i></button>
                                    </div>
                                    <p class="text-sm text-slate-500">12 prosecutors assigned</p>
                                </div>
                                <div class="p-4 bg-slate-50 rounded-lg border border-slate-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="font-medium text-slate-900">Regional Office - NCR</span>
                                        <button type="button" class="text-slate-400 hover:text-red-600"><i class="fas fa-trash text-sm"></i></button>
                                    </div>
                                    <p class="text-sm text-slate-500">8 prosecutors assigned</p>
                                </div>
                            </div>
                            <button type="button" class="w-full py-3 border-2 border-dashed border-slate-300 rounded-lg text-slate-500 hover:border-blue-500 hover:text-blue-600 transition-colors">
                                <i class="fas fa-plus mr-2"></i>Add New Office
                            </button>
                        </div>
                    </div>

                    <!-- System Info -->
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="px-6 py-5 border-b border-slate-200">
                            <h3 class="text-lg font-semibold text-slate-900">System Information</h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <p class="text-sm text-slate-500 mb-1">Application Version</p>
                                    <p class="font-medium text-slate-900">PCTS v1.0.0</p>
                                </div>
                                <div>
                                    <p class="text-sm text-slate-500 mb-1">Laravel Version</p>
                                    <p class="font-medium text-slate-900">{{ app()->version() }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-slate-500 mb-1">PHP Version</p>
                                    <p class="font-medium text-slate-900">{{ phpversion() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Danger Zone -->
                    <div class="bg-white rounded-xl shadow-sm border border-red-200 overflow-hidden">
                        <div class="px-6 py-5 border-b border-red-200 bg-red-50">
                            <h3 class="text-lg font-semibold text-red-900">Danger Zone</h3>
                            <p class="text-sm text-red-600 mt-1">Irreversible and destructive actions</p>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex items-center justify-between p-4 border border-slate-200 rounded-lg">
                                <div>
                                    <h4 class="font-medium text-slate-900">Clear All Cache</h4>
                                    <p class="text-sm text-slate-500">Clear application, route, and view cache</p>
                                </div>
                                <button type="button" class="px-4 py-2 bg-amber-100 text-amber-700 font-medium rounded-lg hover:bg-amber-200 transition-colors">
                                    Clear Cache
                                </button>
                            </div>
                            <div class="flex items-center justify-between p-4 border border-red-200 rounded-lg bg-red-50">
                                <div>
                                    <h4 class="font-medium text-red-900">Reset System</h4>
                                    <p class="text-sm text-red-600">This will delete all data and reset the system</p>
                                </div>
                                <button type="button" class="px-4 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors">
                                    Reset System
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        function avatarUpload() {
            return {
                hasNewAvatar: false,
                previewAvatar(event) {
                    const file = event.target.files[0];
                    if (file) {
                        // Validate file size (2MB max)
                        if (file.size > 2 * 1024 * 1024) {
                            alert('File size must be less than 2MB');
                            event.target.value = '';
                            return;
                        }
                        
                        // Validate file type
                        const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                        if (!validTypes.includes(file.type)) {
                            alert('Please upload a valid image file (JPG, PNG, or GIF)');
                            event.target.value = '';
                            return;
                        }
                        
                        // Create preview
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            const preview = document.getElementById('avatarPreview');
                            const initials = document.getElementById('avatarInitials');
                            
                            preview.src = e.target.result;
                            preview.classList.remove('hidden');
                            
                            if (initials) {
                                initials.classList.add('hidden');
                            }
                            
                            this.hasNewAvatar = true;
                        };
                        reader.readAsDataURL(file);
                    }
                },
                removeAvatar() {
                    const input = document.getElementById('avatarInput');
                    const preview = document.getElementById('avatarPreview');
                    const initials = document.getElementById('avatarInitials');
                    
                    input.value = '';
                    preview.src = '';
                    preview.classList.add('hidden');
                    
                    if (initials) {
                        initials.classList.remove('hidden');
                    }
                    
                    this.hasNewAvatar = false;
                }
            }
        }
    </script>
    @endpush
</x-layouts.app>
