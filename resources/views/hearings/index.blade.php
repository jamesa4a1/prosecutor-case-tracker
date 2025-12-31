<x-layouts.app title="Hearings" header="Hearings">
    <!-- Header Actions -->
    <div class="mb-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div class="flex-1 max-w-lg">
            <form method="GET" action="{{ route('hearings.index') }}" class="flex gap-2">
                <div class="relative flex-1">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}" 
                        placeholder="Search by case number or title..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                    Search
                </button>
            </form>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('hearings.calendar') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                <i class="fas fa-calendar-alt mr-2"></i>Calendar View
            </a>
            <a href="{{ route('hearings.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                <i class="fas fa-plus mr-2"></i>Schedule Hearing
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div x-data="{ showFilters: false }" class="mb-6">
        <button @click="showFilters = !showFilters" class="text-sm text-gray-600 hover:text-gray-900 flex items-center">
            <i class="fas fa-filter mr-2"></i>
            <span x-text="showFilters ? 'Hide Filters' : 'Show Filters'"></span>
            <i class="fas fa-chevron-down ml-2 transition-transform" :class="{ 'rotate-180': showFilters }"></i>
        </button>

        <div x-show="showFilters" x-cloak class="mt-4 p-4 bg-white rounded-lg border border-gray-200">
            <form method="GET" action="{{ route('hearings.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <input type="hidden" name="search" value="{{ request('search') }}">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date From</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date To</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Prosecutor</label>
                    <select name="prosecutor_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="">All Prosecutors</option>
                        @foreach($prosecutors as $prosecutor)
                        <option value="{{ $prosecutor->id }}" {{ request('prosecutor_id') == $prosecutor->id ? 'selected' : '' }}>
                            {{ $prosecutor->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Result Status</label>
                    <select name="result_status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="">All Statuses</option>
                        <option value="Scheduled" {{ request('result_status') == 'Scheduled' ? 'selected' : '' }}>Scheduled</option>
                        <option value="Completed" {{ request('result_status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                        <option value="Postponed" {{ request('result_status') == 'Postponed' ? 'selected' : '' }}>Postponed</option>
                        <option value="Cancelled" {{ request('result_status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <div class="md:col-span-4 flex justify-end gap-2">
                    <a href="{{ route('hearings.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Clear Filters
                    </a>
                    <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Hearings Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        @if($hearings->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date & Time</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Case</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Court/Branch</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Prosecutor</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($hearings as $hearing)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $hearing->date_time->format('M d, Y') }}</div>
                            <div class="text-sm text-gray-500">{{ $hearing->date_time->format('g:i A') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('cases.show', $hearing->case) }}" class="text-primary-600 hover:text-primary-700">
                                <div class="text-sm font-medium">{{ $hearing->case->case_number }}</div>
                            </a>
                            <div class="text-sm text-gray-500">{{ Str::limit($hearing->case->title, 40) }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $hearing->court_branch ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $hearing->assignedProsecutor->name ?? 'Unassigned' }}</td>
                        <td class="px-6 py-4">
                            @php
                                $statusClasses = [
                                    'Scheduled' => 'bg-blue-100 text-blue-800',
                                    'Completed' => 'bg-green-100 text-green-800',
                                    'Postponed' => 'bg-yellow-100 text-yellow-800',
                                    'Cancelled' => 'bg-red-100 text-red-800',
                                ];
                                $status = $hearing->result_status ?? 'Scheduled';
                            @endphp
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $statusClasses[$status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route('hearings.show', $hearing) }}" class="p-2 text-gray-600 hover:text-primary-600" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('hearings.edit', $hearing) }}" class="p-2 text-gray-600 hover:text-primary-600" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('hearings.destroy', $hearing) }}" class="inline"
                                    onsubmit="return confirm('Are you sure you want to delete this hearing?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-600 hover:text-red-600" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $hearings->links() }}
        </div>
        @else
        <div class="p-12 text-center">
            <i class="fas fa-calendar-times text-gray-300 text-5xl mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No hearings found</h3>
            <p class="text-gray-500 mb-4">Get started by scheduling a new hearing.</p>
            <a href="{{ route('hearings.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                <i class="fas fa-plus mr-2"></i>Schedule Hearing
            </a>
        </div>
        @endif
    </div>
</x-layouts.app>
