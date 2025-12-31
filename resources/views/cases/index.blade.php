<x-layouts.app title="All Cases" header="All Cases">
    <!-- Page Header with Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <p class="text-gray-600">Manage and track all cases in the system</p>
        </div>
        <a href="{{ route('cases.create') }}" class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
            <i class="fas fa-plus mr-2"></i>
            Add New Case
        </a>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <form method="GET" action="{{ route('cases.index') }}">
            <div class="flex flex-col lg:flex-row lg:items-end gap-4">
                <!-- Search -->
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <div class="relative">
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}"
                            placeholder="Search by case number, title, or accused..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                        >
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Quick Filters -->
                <div class="w-full lg:w-48">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="">All Statuses</option>
                        @foreach(\App\Models\CaseModel::STATUSES as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full lg:w-40">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                    <select name="type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="">All Types</option>
                        @foreach(\App\Models\CaseModel::TYPES as $type)
                        <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition-colors">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                    <a href="{{ route('cases.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        <i class="fas fa-times mr-2"></i>Clear
                    </a>
                </div>
            </div>

            <!-- Advanced Filters (Collapsible) -->
            <div x-data="{ open: false }" class="mt-4">
                <button type="button" @click="open = !open" class="text-sm text-primary-600 hover:text-primary-700">
                    <i class="fas fa-sliders-h mr-1"></i>
                    Advanced Filters
                    <i class="fas fa-chevron-down ml-1" :class="{ 'rotate-180': open }"></i>
                </button>
                
                <div x-show="open" x-cloak class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4 pt-4 border-t border-gray-200">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Prosecutor</label>
                        <select name="prosecutor_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            <option value="">All Prosecutors</option>
                            @foreach($prosecutors as $prosecutor)
                            <option value="{{ $prosecutor->id }}" {{ request('prosecutor_id') == $prosecutor->id ? 'selected' : '' }}>{{ $prosecutor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date Filed From</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date Filed To</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Cases Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        @if($cases->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Case No.</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Case Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Offense</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Accused</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prosecutor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Filed</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($cases as $case)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('cases.show', $case) }}" class="text-sm font-medium text-primary-600 hover:text-primary-700">
                                {{ $case->case_number }}
                            </a>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-900">{{ Str::limit($case->title, 30) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-600">{{ Str::limit($case->offense, 20) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-600">{{ $case->accused ?? 'N/A' }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $case->status_badge_class }}">
                                {{ $case->status instanceof \App\Enums\CaseStatus ? $case->status->value : $case->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-600">{{ $case->prosecutor->name ?? 'Unassigned' }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-500">{{ $case->date_filed->format('M d, Y') }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('cases.show', $case) }}" class="p-2 text-primary-600 hover:bg-primary-50 rounded-lg" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('cases.edit', $case) }}" class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $cases->links() }}
        </div>
        @else
        <div class="p-12 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-folder-open text-2xl text-gray-400"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No cases found</h3>
            <p class="text-gray-500 mb-6">Get started by creating your first case.</p>
            <a href="{{ route('cases.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Add New Case
            </a>
        </div>
        @endif
    </div>
</x-layouts.app>
