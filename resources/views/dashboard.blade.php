<x-layouts.app title="{{ auth()->user()->role->value }} Dashboard" header="{{ auth()->user()->role->value }} Dashboard">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900">{{ auth()->user()->role->value }} Dashboard</h1>
        <p class="text-slate-600 mt-2">Overview of cases, hearings, and prosecutor activity</p>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <!-- Total Active Cases -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-lg transition-all duration-300">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-2">Total Active Cases</p>
                    <p class="text-4xl font-bold text-slate-900">{{ number_format($totalActiveCases ?? 0) }}</p>
                    <p class="text-sm text-teal-600 mt-3 flex items-center font-medium">
                        <i class="fas fa-arrow-up mr-1 text-xs"></i>
                        <span>+{{ $newCasesThisMonth ?? 0 }} this month</span>
                    </p>
                </div>
                <div class="w-14 h-14 bg-teal-50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-briefcase text-2xl text-teal-600"></i>
                </div>
            </div>
        </div>

        <!-- Pending Hearings (Next 7 Days) -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-lg transition-all duration-300">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-2">Pending Hearings</p>
                    <p class="text-4xl font-bold text-slate-900">{{ isset($upcomingHearings) ? $upcomingHearings->count() : 0 }}</p>
                    <p class="text-sm text-amber-600 mt-3 flex items-center font-medium">
                        <i class="fas fa-clock mr-1 text-xs"></i>
                        <span>Next 7 days</span>
                    </p>
                </div>
                <div class="w-14 h-14 bg-amber-50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-calendar-check text-2xl text-amber-600"></i>
                </div>
            </div>
        </div>

        <!-- For Filing -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-lg transition-all duration-300">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-2">For Filing</p>
                    <p class="text-4xl font-bold text-slate-900">{{ number_format($pendingCases ?? 0) }}</p>
                    <p class="text-sm text-blue-600 mt-3 flex items-center font-medium">
                        <i class="fas fa-file-alt mr-1 text-xs"></i>
                        <span>Awaiting action</span>
                    </p>
                </div>
                <div class="w-14 h-14 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-file-signature text-2xl text-blue-600"></i>
                </div>
            </div>
        </div>

        <!-- Closed Cases -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-lg transition-all duration-300">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-2">Closed Cases</p>
                    <p class="text-4xl font-bold text-slate-900">{{ number_format($closedCases ?? 0) }}</p>
                    <p class="text-sm text-green-600 mt-3 flex items-center font-medium">
                        <i class="fas fa-check-circle mr-1 text-xs"></i>
                        <span>This year</span>
                    </p>
                </div>
                <div class="w-14 h-14 bg-green-50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-check-double text-2xl text-green-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <!-- Left Column: Upcoming Hearings -->
        <div class="xl:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-200 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar-alt text-teal-600"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900">Upcoming Hearings</h3>
                    </div>
                    <a href="{{ route('hearings.index') }}" class="text-teal-600 hover:text-teal-700 text-sm font-medium flex items-center group">
                        View All <i class="fas fa-arrow-right ml-2 text-xs group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
                
                @if(isset($upcomingHearings) && $upcomingHearings->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200">
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Date & Time</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Case Number</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Case Title</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Location</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Prosecutor</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($upcomingHearings as $hearing)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-slate-900">
                                        {{ \Carbon\Carbon::parse($hearing->date_time)->format('M d, Y') }}
                                    </div>
                                    <div class="text-xs text-slate-500">
                                        {{ \Carbon\Carbon::parse($hearing->date_time)->format('h:i A') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-semibold text-teal-600">{{ $hearing->case->case_number ?? 'N/A' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-slate-900 max-w-xs truncate">{{ $hearing->case->title ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $hearing->hearing_type ?? 'Hearing' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-slate-600">{{ $hearing->court_branch ?? $hearing->venue ?? 'TBD' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-slate-900">{{ $hearing->assignedProsecutor->name ?? 'Unassigned' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <a href="{{ route('cases.show', $hearing->case_id) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-teal-700 bg-teal-50 rounded-lg hover:bg-teal-100 transition-colors">
                                        <i class="fas fa-eye mr-1.5"></i> View
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="px-6 py-16 text-center">
                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-calendar-times text-2xl text-slate-400"></i>
                    </div>
                    <h4 class="text-lg font-medium text-slate-900 mb-2">No Upcoming Hearings</h4>
                    <p class="text-slate-500 text-sm">There are no scheduled hearings at this time.</p>
                </div>
                @endif
            </div>

            <!-- Recent Case Activity -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mt-8">
                <div class="px-6 py-5 border-b border-slate-200 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-history text-purple-600"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900">Recent Case Activity</h3>
                    </div>
                    <a href="{{ route('cases.index') }}" class="text-purple-600 hover:text-purple-700 text-sm font-medium flex items-center group">
                        View All <i class="fas fa-arrow-right ml-2 text-xs group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
                
                @if(isset($recentStatusChanges) && $recentStatusChanges->count() > 0)
                <div class="divide-y divide-slate-100">
                    @foreach($recentStatusChanges as $change)
                    <div class="px-6 py-4 hover:bg-slate-50 transition-colors">
                        <div class="flex items-start space-x-4">
                            <div class="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-exchange-alt text-slate-500"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-slate-900">
                                    <span class="font-semibold text-teal-600">{{ $change->case->case_number ?? 'Case' }}</span>
                                    changed from 
                                    <span class="font-medium">"{{ $change->from_status ?? 'N/A' }}"</span>
                                    to 
                                    <span class="font-medium text-green-600">"{{ $change->to_status }}"</span>
                                </p>
                                <p class="text-xs text-slate-500 mt-1">
                                    by {{ $change->changedByUser->name ?? 'System' }} &middot; {{ $change->changed_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="px-6 py-12 text-center">
                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-inbox text-2xl text-slate-400"></i>
                    </div>
                    <h4 class="text-lg font-medium text-slate-900 mb-2">No Recent Activity</h4>
                    <p class="text-slate-500 text-sm">Case status changes will appear here.</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Right Column: Recent Cases & Quick Stats -->
        <div class="space-y-8">
            <!-- Recent Cases Widget -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-200 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-folder-open text-blue-600"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900">Recent Cases</h3>
                    </div>
                    <a href="{{ route('cases.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium flex items-center group">
                        View All <i class="fas fa-arrow-right ml-2 text-xs group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
                
                @if(isset($recentCases) && $recentCases->count() > 0)
                <div class="divide-y divide-slate-100">
                    @foreach($recentCases as $case)
                    <a href="{{ route('cases.show', $case) }}" class="block px-6 py-4 hover:bg-slate-50 transition-colors">
                        <div class="flex items-start justify-between">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-teal-600">{{ $case->case_number }}</p>
                                <p class="text-sm text-slate-900 mt-1 truncate">{{ $case->title }}</p>
                                <p class="text-xs text-slate-500 mt-1">{{ $case->prosecutor->name ?? 'Unassigned' }}</p>
                            </div>
                            <div class="ml-4 flex-shrink-0">
                                @php
                                    $statusColors = [
                                        'Filed' => 'bg-green-100 text-green-800',
                                        'Under Investigation' => 'bg-amber-100 text-amber-800',
                                        'For Filing' => 'bg-blue-100 text-blue-800',
                                        'Preliminary Investigation' => 'bg-purple-100 text-purple-800',
                                        'In Court' => 'bg-cyan-100 text-cyan-800',
                                        'Pre-Trial' => 'bg-indigo-100 text-indigo-800',
                                        'Trial' => 'bg-orange-100 text-orange-800',
                                        'Closed' => 'bg-slate-100 text-slate-800',
                                        'Dismissed' => 'bg-red-100 text-red-800',
                                        'Pending' => 'bg-yellow-100 text-yellow-800',
                                        'Archived' => 'bg-slate-100 text-slate-800',
                                    ];
                                    $statusValue = $case->status instanceof \App\Enums\CaseStatus ? $case->status->value : $case->status;
                                    $colorClass = $statusColors[$statusValue] ?? 'bg-slate-100 text-slate-800';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $colorClass }}">
                                    {{ $statusValue }}
                                </span>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
                @else
                <div class="px-6 py-12 text-center">
                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-folder text-2xl text-slate-400"></i>
                    </div>
                    <h4 class="text-lg font-medium text-slate-900 mb-2">No Cases Yet</h4>
                    <p class="text-slate-500 text-sm mb-4">Start by adding your first case.</p>
                    <a href="{{ route('cases.create') }}" class="inline-flex items-center px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors text-sm font-medium">
                        <i class="fas fa-plus mr-2"></i> Add Case
                    </a>
                </div>
                @endif
            </div>

            <!-- Case Status Distribution -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-chart-pie text-amber-600"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900">Case Distribution</h3>
                    </div>
                </div>
                
                <div class="p-6">
                    @if(isset($statusDistribution) && count($statusDistribution) > 0)
                    <div class="space-y-4">
                        @php
                            $total = array_sum($statusDistribution);
                            $colors = [
                                'Filed' => 'bg-green-500',
                                'Under Investigation' => 'bg-amber-500',
                                'For Filing' => 'bg-blue-500',
                                'Preliminary Investigation' => 'bg-purple-500',
                                'In Court' => 'bg-cyan-500',
                                'Pre-Trial' => 'bg-indigo-500',
                                'Trial' => 'bg-orange-500',
                                'Closed' => 'bg-slate-500',
                                'Dismissed' => 'bg-red-500',
                            ];
                        @endphp
                        @foreach($statusDistribution as $status => $count)
                        @php
                            $percentage = $total > 0 ? round(($count / $total) * 100) : 0;
                            $barColor = $colors[$status] ?? 'bg-slate-500';
                        @endphp
                        <div>
                            <div class="flex items-center justify-between text-sm mb-1">
                                <span class="text-slate-700 font-medium">{{ $status }}</span>
                                <span class="text-slate-500">{{ $count }} ({{ $percentage }}%)</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2">
                                <div class="{{ $barColor }} h-2 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-chart-pie text-2xl text-slate-400"></i>
                        </div>
                        <p class="text-slate-500 text-sm">No case data available yet.</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-700">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center">
                            <i class="fas fa-bolt text-amber-400"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-white">Quick Actions</h3>
                    </div>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('cases.create') }}" class="flex items-center px-4 py-3 bg-white/10 rounded-lg text-white hover:bg-white/20 transition-colors group">
                        <div class="w-10 h-10 bg-teal-500/20 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-plus text-teal-400"></i>
                        </div>
                        <div>
                            <p class="font-medium">New Case</p>
                            <p class="text-xs text-slate-400">Create a new case file</p>
                        </div>
                        <i class="fas fa-chevron-right ml-auto text-slate-500 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                    <a href="{{ route('hearings.create') }}" class="flex items-center px-4 py-3 bg-white/10 rounded-lg text-white hover:bg-white/20 transition-colors group">
                        <div class="w-10 h-10 bg-blue-500/20 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-calendar-plus text-blue-400"></i>
                        </div>
                        <div>
                            <p class="font-medium">Schedule Hearing</p>
                            <p class="text-xs text-slate-400">Add a new hearing date</p>
                        </div>
                        <i class="fas fa-chevron-right ml-auto text-slate-500 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                    <a href="{{ route('cases.index') }}" class="flex items-center px-4 py-3 bg-white/10 rounded-lg text-white hover:bg-white/20 transition-colors group">
                        <div class="w-10 h-10 bg-amber-500/20 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-search text-amber-400"></i>
                        </div>
                        <div>
                            <p class="font-medium">Search Cases</p>
                            <p class="text-xs text-slate-400">Find existing cases</p>
                        </div>
                        <i class="fas fa-chevron-right ml-auto text-slate-500 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
