<x-layouts.app title="Edit User" header="Edit User">
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center space-x-2 text-sm">
            <li><a href="{{ route('admin.users.index') }}" class="text-primary-600 hover:text-primary-700">Users</a></li>
            <li><i class="fas fa-chevron-right text-gray-400 text-xs"></i></li>
            <li class="text-gray-500">{{ $user->name }}</li>
        </ol>
    </nav>

    <form action="{{ route('admin.users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">User Information</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" required
                        value="{{ old('name', $user->name) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('name') border-red-500 @enderror">
                    @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" id="email" required
                        value="{{ old('email', $user->email) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('email') border-red-500 @enderror">
                    @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        New Password <span class="text-gray-400 text-xs">(leave blank to keep current)</span>
                    </label>
                    <input type="password" name="password" id="password"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('password') border-red-500 @enderror">
                    @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                        Confirm New Password
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>

                <!-- Role -->
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-1">
                        Role <span class="text-red-500">*</span>
                    </label>
                    <select name="role" id="role" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('role') border-red-500 @enderror"
                        {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                        @foreach($roles as $value => $label)
                        <option value="{{ $value }}" {{ old('role', $user->role) == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @if($user->id === auth()->id())
                    <input type="hidden" name="role" value="{{ $user->role }}">
                    <p class="mt-1 text-xs text-gray-500">You cannot change your own role</p>
                    @endif
                    @error('role')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Active Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <div class="flex items-center space-x-4 mt-2">
                        <label class="inline-flex items-center">
                            <input type="radio" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                                class="text-primary-600 focus:ring-primary-500"
                                {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                            <span class="ml-2 text-sm text-gray-700">Active</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="is_active" value="0" {{ !old('is_active', $user->is_active) ? 'checked' : '' }}
                                class="text-primary-600 focus:ring-primary-500"
                                {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                            <span class="ml-2 text-sm text-gray-700">Inactive</span>
                        </label>
                    </div>
                    @if($user->id === auth()->id())
                    <input type="hidden" name="is_active" value="{{ $user->is_active ? '1' : '0' }}">
                    <p class="mt-1 text-xs text-gray-500">You cannot deactivate your own account</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Account Info -->
        <div class="bg-gray-50 rounded-xl border border-gray-200 p-4 mb-6">
            <p class="text-sm text-gray-500">
                <span class="font-medium">Account created:</span> {{ $user->created_at->format('M d, Y \a\t g:i A') }}
                <span class="mx-2">•</span>
                <span class="font-medium">Last updated:</span> {{ $user->updated_at->format('M d, Y \a\t g:i A') }}
            </p>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end space-x-4">
            <a href="{{ route('admin.users.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                <i class="fas fa-save mr-2"></i>Update User
            </button>
        </div>
    </form>
</x-layouts.app>
