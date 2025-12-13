<x-layouts.app title="Schedule Hearing" header="Schedule Hearing">
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center space-x-2 text-sm">
            <li><a href="{{ route('hearings.index') }}" class="text-primary-600 hover:text-primary-700">Hearings</a></li>
            <li><i class="fas fa-chevron-right text-gray-400 text-xs"></i></li>
            <li class="text-gray-500">Schedule New</li>
        </ol>
    </nav>

    <form action="{{ route('hearings.store') }}" method="POST">
        @csrf

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Hearing Details</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Case Selection -->
                <div class="md:col-span-2">
                    <label for="case_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Case <span class="text-red-500">*</span>
                    </label>
                    <select name="case_id" id="case_id" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('case_id') border-red-500 @enderror">
                        <option value="">Select a case</option>
                        @foreach($cases as $case)
                        <option value="{{ $case->id }}" {{ old('case_id', $selectedCaseId) == $case->id ? 'selected' : '' }}>
                            {{ $case->case_number }} - {{ Str::limit($case->title, 50) }}
                        </option>
                        @endforeach
                    </select>
                    @error('case_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date & Time -->
                <div>
                    <label for="date_time" class="block text-sm font-medium text-gray-700 mb-1">
                        Date & Time <span class="text-red-500">*</span>
                    </label>
                    <input type="datetime-local" name="date_time" id="date_time" required
                        value="{{ old('date_time') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('date_time') border-red-500 @enderror">
                    @error('date_time')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Court/Branch -->
                <div>
                    <label for="court_branch" class="block text-sm font-medium text-gray-700 mb-1">Court/Branch</label>
                    <input type="text" name="court_branch" id="court_branch"
                        value="{{ old('court_branch') }}"
                        placeholder="e.g., RTC Branch 1"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('court_branch') border-red-500 @enderror">
                    @error('court_branch')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Assigned Prosecutor -->
                <div>
                    <label for="assigned_prosecutor_id" class="block text-sm font-medium text-gray-700 mb-1">Assigned Prosecutor</label>
                    <select name="assigned_prosecutor_id" id="assigned_prosecutor_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('assigned_prosecutor_id') border-red-500 @enderror">
                        <option value="">Select prosecutor</option>
                        @foreach($prosecutors as $prosecutor)
                        <option value="{{ $prosecutor->id }}" {{ old('assigned_prosecutor_id') == $prosecutor->id ? 'selected' : '' }}>
                            {{ $prosecutor->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('assigned_prosecutor_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Result Status -->
                <div>
                    <label for="result_status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="result_status" id="result_status"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="Scheduled" {{ old('result_status') == 'Scheduled' ? 'selected' : '' }}>Scheduled</option>
                        <option value="Completed" {{ old('result_status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                        <option value="Postponed" {{ old('result_status') == 'Postponed' ? 'selected' : '' }}>Postponed</option>
                        <option value="Cancelled" {{ old('result_status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <!-- Remarks -->
                <div class="md:col-span-2">
                    <label for="remarks" class="block text-sm font-medium text-gray-700 mb-1">Remarks</label>
                    <textarea name="remarks" id="remarks" rows="3"
                        placeholder="Additional notes about this hearing..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('remarks') border-red-500 @enderror">{{ old('remarks') }}</textarea>
                    @error('remarks')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end space-x-4">
            <a href="{{ route('hearings.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                <i class="fas fa-save mr-2"></i>Schedule Hearing
            </button>
        </div>
    </form>
</x-layouts.app>
