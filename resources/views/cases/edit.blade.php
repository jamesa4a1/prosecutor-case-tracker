<x-layouts.app title="Edit Case" header="Edit Case">
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center space-x-2 text-sm">
            <li><a href="{{ route('cases.index') }}" class="text-primary-600 hover:text-primary-700">Cases</a></li>
            <li><i class="fas fa-chevron-right text-gray-400 text-xs"></i></li>
            <li><a href="{{ route('cases.show', $case) }}" class="text-primary-600 hover:text-primary-700">{{ $case->case_number }}</a></li>
            <li><i class="fas fa-chevron-right text-gray-400 text-xs"></i></li>
            <li class="text-gray-500">Edit</li>
        </ol>
    </nav>

    <form method="POST" action="{{ route('cases.update', $case) }}" class="space-y-8">
        @csrf
        @method('PUT')

        <!-- Case Information Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                <i class="fas fa-folder text-primary-600 mr-3"></i>
                Case Information
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Case Number -->
                <div>
                    <label for="case_number" class="block text-sm font-medium text-gray-700 mb-2">
                        Case Number <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="case_number" 
                        name="case_number" 
                        value="{{ old('case_number', $case->case_number) }}"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('case_number') border-red-500 @enderror"
                    >
                    @error('case_number')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Title -->
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Case Title <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="title" 
                        name="title" 
                        value="{{ old('title', $case->title) }}"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('title') border-red-500 @enderror"
                    >
                    @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Offense -->
                <div>
                    <label for="offense" class="block text-sm font-medium text-gray-700 mb-2">
                        Offense <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="offense" 
                        name="offense" 
                        value="{{ old('offense', $case->offense) }}"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('offense') border-red-500 @enderror"
                    >
                    @error('offense')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Type -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                        Case Type <span class="text-red-500">*</span>
                    </label>
                    <select 
                        id="type" 
                        name="type" 
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('type') border-red-500 @enderror"
                    >
                        @foreach($types as $type)
                        <option value="{{ $type }}" {{ old('type', $case->type) == $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                    @error('type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date Filed -->
                <div>
                    <label for="date_filed" class="block text-sm font-medium text-gray-700 mb-2">
                        Date Filed <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="date" 
                        id="date_filed" 
                        name="date_filed" 
                        value="{{ old('date_filed', $case->date_filed->format('Y-m-d')) }}"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('date_filed') border-red-500 @enderror"
                    >
                    @error('date_filed')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Parties Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                <i class="fas fa-users text-primary-600 mr-3"></i>
                Parties Involved
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="complainant" class="block text-sm font-medium text-gray-700 mb-2">Complainant</label>
                    <input type="text" id="complainant" name="complainant" value="{{ old('complainant', $case->complainant) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
                <div>
                    <label for="accused" class="block text-sm font-medium text-gray-700 mb-2">Accused</label>
                    <input type="text" id="accused" name="accused" value="{{ old('accused', $case->accused) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
            </div>
        </div>

        <!-- Investigation Details Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                <i class="fas fa-search text-primary-600 mr-3"></i>
                Investigation Details
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="investigating_officer" class="block text-sm font-medium text-gray-700 mb-2">Investigating Officer</label>
                    <input type="text" id="investigating_officer" name="investigating_officer" value="{{ old('investigating_officer', $case->investigating_officer) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
                <div>
                    <label for="agency_station" class="block text-sm font-medium text-gray-700 mb-2">Agency/Station</label>
                    <input type="text" id="agency_station" name="agency_station" value="{{ old('agency_station', $case->agency_station) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
            </div>
        </div>

        <!-- Assignment Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                <i class="fas fa-user-tie text-primary-600 mr-3"></i>
                Assignment & Status
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="prosecutor_id" class="block text-sm font-medium text-gray-700 mb-2">Assigned Prosecutor</label>
                    <select id="prosecutor_id" name="prosecutor_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="">Select Prosecutor</option>
                        @foreach($prosecutors as $prosecutor)
                        <option value="{{ $prosecutor->id }}" {{ old('prosecutor_id', $case->prosecutor_id) == $prosecutor->id ? 'selected' : '' }}>
                            {{ $prosecutor->name }} {{ $prosecutor->position ? '(' . $prosecutor->position . ')' : '' }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                    <select id="status" name="status" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('status') border-red-500 @enderror">
                        @php
                            $currentStatus = old('status', $case->status instanceof \App\Enums\CaseStatus ? $case->status->value : $case->status);
                        @endphp
                        @foreach($statuses as $status)
                        <option value="{{ $status }}" {{ $currentStatus == $status ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                    @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Hearing Details Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                <i class="fas fa-calendar-alt text-primary-600 mr-3"></i>
                Hearing Details
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="next_hearing_at" class="block text-sm font-medium text-gray-700 mb-2">Next Hearing Date & Time</label>
                    <input type="datetime-local" id="next_hearing_at" name="next_hearing_at" 
                        value="{{ old('next_hearing_at', $case->next_hearing_at ? $case->next_hearing_at->format('Y-m-d\TH:i') : '') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
                <div>
                    <label for="court_branch" class="block text-sm font-medium text-gray-700 mb-2">Court/Branch</label>
                    <input type="text" id="court_branch" name="court_branch" value="{{ old('court_branch', $case->court_branch) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
            </div>
        </div>

        <!-- Notes Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                <i class="fas fa-sticky-note text-primary-600 mr-3"></i>
                Additional Notes
            </h3>
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                <textarea id="notes" name="notes" rows="4"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                >{{ old('notes', $case->notes) }}</textarea>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end space-x-4">
            <a href="{{ route('cases.show', $case) }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                <i class="fas fa-save mr-2"></i>
                Update Case
            </button>
        </div>
    </form>
</x-layouts.app>
