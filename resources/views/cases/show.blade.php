<x-layouts.app title="Case Details" header="Case Details">
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center space-x-2 text-sm">
            <li><a href="{{ route('cases.index') }}" class="text-primary-600 hover:text-primary-700">Cases</a></li>
            <li><i class="fas fa-chevron-right text-gray-400 text-xs"></i></li>
            <li class="text-gray-500">{{ $case->case_number }}</li>
        </ol>
    </nav>

    <!-- Case Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
            <div>
                <div class="flex items-center space-x-3 mb-2">
                    <h1 class="text-2xl font-bold text-gray-900">{{ $case->case_number }}</h1>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $case->status_badge_class }}">
                        {{ $case->status }}
                    </span>
                </div>
                <p class="text-lg text-gray-600">{{ $case->title }}</p>
            </div>
            <div class="mt-4 lg:mt-0 flex flex-wrap gap-3">
                <a href="{{ route('cases.edit', $case) }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
                <a href="{{ route('hearings.create', ['case_id' => $case->id]) }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-calendar-plus mr-2"></i>Add Hearing
                </a>
                <button onclick="document.getElementById('noteModal').classList.remove('hidden')" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-sticky-note mr-2"></i>Add Note
                </button>
            </div>
        </div>
    </div>

    <!-- Tabbed Content -->
    <div x-data="{ activeTab: 'overview' }" class="space-y-6">
        <!-- Tab Navigation -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <nav class="flex border-b border-gray-200">
                <button @click="activeTab = 'overview'" 
                    :class="{ 'border-primary-600 text-primary-600': activeTab === 'overview', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'overview' }"
                    class="px-6 py-4 text-sm font-medium border-b-2 transition-colors">
                    <i class="fas fa-info-circle mr-2"></i>Overview
                </button>
                <button @click="activeTab = 'parties'" 
                    :class="{ 'border-primary-600 text-primary-600': activeTab === 'parties', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'parties' }"
                    class="px-6 py-4 text-sm font-medium border-b-2 transition-colors">
                    <i class="fas fa-users mr-2"></i>Parties
                </button>
                <button @click="activeTab = 'hearings'" 
                    :class="{ 'border-primary-600 text-primary-600': activeTab === 'hearings', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'hearings' }"
                    class="px-6 py-4 text-sm font-medium border-b-2 transition-colors">
                    <i class="fas fa-calendar mr-2"></i>Hearings ({{ $case->hearings->count() }})
                </button>
                <button @click="activeTab = 'activity'" 
                    :class="{ 'border-primary-600 text-primary-600': activeTab === 'activity', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'activity' }"
                    class="px-6 py-4 text-sm font-medium border-b-2 transition-colors">
                    <i class="fas fa-history mr-2"></i>Activity
                </button>
            </nav>
        </div>

        <!-- Overview Tab -->
        <div x-show="activeTab === 'overview'" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Case Details Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Case Details</h3>
                <dl class="space-y-4">
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Offense</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ $case->offense }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Type</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ $case->type }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Date Filed</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ $case->date_filed->format('M d, Y') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Court/Branch</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ $case->court_branch ?? 'Not assigned' }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Assignment Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Assignment</h3>
                <dl class="space-y-4">
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Prosecutor</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ $case->prosecutor->name ?? 'Unassigned' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Investigating Officer</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ $case->investigating_officer ?? 'N/A' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Agency/Station</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ $case->agency_station ?? 'N/A' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Next Hearing</dt>
                        <dd class="text-sm font-medium text-gray-900">
                            {{ $case->next_hearing_at ? $case->next_hearing_at->format('M d, Y g:i A') : 'Not scheduled' }}
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Notes Card -->
            @if($case->notes)
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Case Notes</h3>
                <p class="text-gray-600 whitespace-pre-wrap">{{ $case->notes }}</p>
            </div>
            @endif
        </div>

        <!-- Parties Tab -->
        <div x-show="activeTab === 'parties'" x-cloak class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="p-4 bg-blue-50 rounded-lg">
                    <h4 class="text-sm font-medium text-blue-900 mb-2">
                        <i class="fas fa-user-shield mr-2"></i>Complainant
                    </h4>
                    <p class="text-blue-800">{{ $case->complainant ?? 'Not specified' }}</p>
                </div>
                <div class="p-4 bg-red-50 rounded-lg">
                    <h4 class="text-sm font-medium text-red-900 mb-2">
                        <i class="fas fa-user-times mr-2"></i>Accused
                    </h4>
                    <p class="text-red-800">{{ $case->accused ?? 'Not specified' }}</p>
                </div>
            </div>
        </div>

        <!-- Hearings Tab -->
        <div x-show="activeTab === 'hearings'" x-cloak class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Hearings</h3>
                <a href="{{ route('hearings.create', ['case_id' => $case->id]) }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>Add Hearing
                </a>
            </div>
            
            @if($case->hearings->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date & Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Court/Branch</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prosecutor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Result</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Remarks</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($case->hearings->sortByDesc('date_time') as $hearing)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                {{ $hearing->date_time->format('M d, Y') }}<br>
                                <span class="text-gray-500">{{ $hearing->date_time->format('g:i A') }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $hearing->court_branch ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $hearing->assignedProsecutor->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $hearing->result_status ?? 'Pending' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ Str::limit($hearing->remarks, 50) ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="p-8 text-center text-gray-500">
                <i class="fas fa-calendar-times text-4xl mb-3"></i>
                <p>No hearings scheduled for this case</p>
            </div>
            @endif
        </div>

        <!-- Activity Tab -->
        <div x-show="activeTab === 'activity'" x-cloak class="space-y-6">
            <!-- Notes -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Notes</h3>
                </div>
                @if($case->notes()->count() > 0)
                <div class="divide-y divide-gray-100">
                    @foreach($case->notes()->with('user')->latest()->get() as $note)
                    <div class="p-6">
                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-primary-600 text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-gray-900">{{ $note->user->name ?? 'Unknown' }}</p>
                                    <p class="text-xs text-gray-500">{{ $note->created_at->diffForHumans() }}</p>
                                </div>
                                <p class="mt-1 text-sm text-gray-600">{{ $note->body }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="p-8 text-center text-gray-500">
                    <i class="fas fa-sticky-note text-4xl mb-3"></i>
                    <p>No notes added yet</p>
                </div>
                @endif
            </div>

            <!-- Status History -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Status History</h3>
                </div>
                @if($case->statusHistories->count() > 0)
                <div class="p-6">
                    <div class="flow-root">
                        <ul class="-mb-8">
                            @foreach($case->statusHistories->sortByDesc('changed_at') as $history)
                            <li>
                                <div class="relative pb-8">
                                    @if(!$loop->last)
                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></span>
                                    @endif
                                    <div class="relative flex space-x-3">
                                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-primary-100">
                                            <i class="fas fa-exchange-alt text-primary-600 text-sm"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm text-gray-600">
                                                Status changed 
                                                @if($history->from_status)
                                                from <span class="font-medium">{{ $history->from_status }}</span>
                                                @endif
                                                to <span class="font-medium">{{ $history->to_status }}</span>
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                by {{ $history->changedByUser->name ?? 'Unknown' }} • {{ $history->changed_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @else
                <div class="p-8 text-center text-gray-500">
                    <i class="fas fa-history text-4xl mb-3"></i>
                    <p>No status changes recorded</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Add Note Modal -->
    <div id="noteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-lg mx-4">
            <div class="p-6 border-b border-gray-100">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">Add Note</h3>
                    <button onclick="document.getElementById('noteModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <form method="POST" action="{{ route('notes.store') }}">
                @csrf
                <input type="hidden" name="case_id" value="{{ $case->id }}">
                <div class="p-6">
                    <textarea name="body" rows="4" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                        placeholder="Enter your note..."></textarea>
                </div>
                <div class="p-6 border-t border-gray-100 flex justify-end space-x-3">
                    <button type="button" onclick="document.getElementById('noteModal').classList.add('hidden')"
                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                        <i class="fas fa-save mr-2"></i>Save Note
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
