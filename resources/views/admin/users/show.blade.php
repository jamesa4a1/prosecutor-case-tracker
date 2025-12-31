<x-layouts.app title="User Details - {{ $user->name }}" header="User Details">
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center space-x-2 text-sm">
            <li><a href="{{ route('admin.users.index') }}" class="text-primary-600 hover:text-primary-700">Users</a></li>
            <li><i class="fas fa-chevron-right text-gray-400 text-xs"></i></li>
            <li class="text-gray-500">{{ $user->name }}</li>
        </ol>
    </nav>

    <!-- User Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" class="w-16 h-16 rounded-full object-cover" alt="{{ $user->name }}">
                    @else
                        <span class="text-primary-600 font-bold text-xl">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                    @endif
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                    <p class="text-gray-600">{{ $user->email }}</p>
                    <div class="flex items-center gap-2 mt-2">
                        @php
                            $roleValue = $user->role instanceof \App\Enums\UserRole ? $user->role->value : $user->role;
                            $roleColors = [
                                'Admin' => 'bg-red-100 text-red-800',
                                'Prosecutor' => 'bg-blue-100 text-blue-800',
                                'Clerk' => 'bg-green-100 text-green-800',
                            ];
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $roleColors[$roleValue] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $roleValue }}
                        </span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="mt-4 lg:mt-0 flex gap-3">
                <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                    <i class="fas fa-edit mr-2"></i>Edit User
                </a>
            </div>
        </div>
    </div>

    <!-- User Details Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Account Information -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-user text-primary-600 mr-2"></i>
                Account Information
            </h3>
            <dl class="space-y-4">
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                    <dd class="text-sm text-gray-900">{{ $user->name }}</dd>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                    <dd class="text-sm text-gray-900">{{ $user->email }}</dd>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <dt class="text-sm font-medium text-gray-500">Phone</dt>
                    <dd class="text-sm text-gray-900">{{ $user->phone ?? 'Not provided' }}</dd>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <dt class="text-sm font-medium text-gray-500">Office</dt>
                    <dd class="text-sm text-gray-900">{{ $user->office ?? 'Not specified' }}</dd>
                </div>
                <div class="flex justify-between py-2">
                    <dt class="text-sm font-medium text-gray-500">Email Verified</dt>
                    <dd class="text-sm">
                        @if($user->email_verified_at)
                            <span class="text-green-600"><i class="fas fa-check-circle mr-1"></i>{{ $user->email_verified_at->format('M d, Y') }}</span>
                        @else
                            <span class="text-red-600"><i class="fas fa-times-circle mr-1"></i>Not verified</span>
                        @endif
                    </dd>
                </div>
            </dl>
        </div>

        <!-- Activity Information -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-clock text-primary-600 mr-2"></i>
                Activity
            </h3>
            <dl class="space-y-4">
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <dt class="text-sm font-medium text-gray-500">Account Created</dt>
                    <dd class="text-sm text-gray-900">{{ $user->created_at->format('M d, Y g:i A') }}</dd>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                    <dd class="text-sm text-gray-900">{{ $user->updated_at->format('M d, Y g:i A') }}</dd>
                </div>
                <div class="flex justify-between py-2">
                    <dt class="text-sm font-medium text-gray-500">Last Login</dt>
                    <dd class="text-sm text-gray-900">{{ $user->last_login_at ? $user->last_login_at->format('M d, Y g:i A') : 'Never' }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Associated Prosecutor Profile -->
    @if($user->prosecutor)
    <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-user-tie text-primary-600 mr-2"></i>
            Linked Prosecutor Profile
        </h3>
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <span class="text-blue-600 font-bold">{{ strtoupper(substr($user->prosecutor->name, 0, 2)) }}</span>
                </div>
                <div>
                    <p class="font-medium text-gray-900">{{ $user->prosecutor->name }}</p>
                    <p class="text-sm text-gray-500">{{ $user->prosecutor->position ?? 'Prosecutor' }}</p>
                </div>
            </div>
            <a href="{{ route('prosecutors.show', $user->prosecutor) }}" class="text-primary-600 hover:text-primary-700">
                View Profile <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
    </div>
    @endif

    <!-- Recent Notes -->
    @if($user->notes && $user->notes->count() > 0)
    <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-sticky-note text-primary-600 mr-2"></i>
            Recent Notes ({{ $user->notes->count() }})
        </h3>
        <div class="space-y-4">
            @foreach($user->notes as $note)
            <div class="p-4 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-600">{{ Str::limit($note->body, 200) }}</p>
                <p class="text-xs text-gray-400 mt-2">{{ $note->created_at->diffForHumans() }}</p>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Actions Footer -->
    <div class="mt-6 flex items-center justify-between">
        <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:text-gray-900">
            <i class="fas fa-arrow-left mr-2"></i>Back to Users
        </a>
        @if($user->id !== auth()->id())
        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline"
            onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                <i class="fas fa-trash mr-2"></i>Delete User
            </button>
        </form>
        @endif
    </div>
</x-layouts.app>
