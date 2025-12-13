<x-layouts.app title="Dashboard" header="Dashboard">
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Active Cases -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Active Cases</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($totalActiveCases) }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-folder-open text-xl text-blue-600"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-green-600 flex items-center">
                    <i class="fas fa-arrow-up mr-1"></i>
                    Active
                </span>
            </div>
        </div>

        <!-- New Cases This Month -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">New This Month</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($newCasesThisMonth) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-plus-circle text-xl text-green-600"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-gray-500">
                <i class="fas fa-calendar mr-1"></i>
                {{ now()->format('F Y') }}
            </div>
        </div>

        <!-- Pending Cases -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Pending Cases</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($pendingCases) }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-xl text-yellow-600"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-yellow-600">
                <i class="fas fa-exclamation-circle mr-1"></i>
                Requires attention
            </div>
        </div>

        <!-- Closed Cases -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Closed Cases</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($closedCases) }}</p>
                </div>
                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-xl text-gray-600"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-green-600">
                <i class="fas fa-check mr-1"></i>
                Resolved
            </div>
        </div>
    </div>

    <!-- Charts and Tables Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Status Distribution Chart -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Case Status Distribution</h3>
                <button class="text-sm text-primary-600 hover:text-primary-700">
                    <i class="fas fa-expand-alt mr-1"></i>
                    View Details
                </button>
            </div>
            <div class="h-64">
                <canvas id="statusChart"></canvas>
            </div>
        </div>

        <!-- Upcoming Hearings -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Upcoming Hearings</h3>
                <a href="{{ route('hearings.index') }}" class="text-sm text-primary-600 hover:text-primary-700">
                    View All <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            
            @if($upcomingHearings->count() > 0)
            <div class="space-y-4 max-h-64 overflow-y-auto">
                @foreach($upcomingHearings as $hearing)
                <div class="flex items-start space-x-4 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="flex-shrink-0 w-12 h-12 bg-primary-100 rounded-lg flex flex-col items-center justify-center">
                        <span class="text-xs font-bold text-primary-600">{{ $hearing->date_time->format('M') }}</span>
                        <span class="text-lg font-bold text-primary-700">{{ $hearing->date_time->format('d') }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">
                            {{ $hearing->case->case_number ?? 'N/A' }}
                        </p>
                        <p class="text-sm text-gray-500 truncate">
                            {{ $hearing->case->accused ?? 'Unknown' }}
                        </p>
                        <p class="text-xs text-gray-400">
                            <i class="fas fa-clock mr-1"></i>{{ $hearing->date_time->format('g:i A') }}
                            @if($hearing->court_branch)
                            <span class="ml-2"><i class="fas fa-gavel mr-1"></i>{{ $hearing->court_branch }}</span>
                            @endif
                        </p>
                    </div>
                    <a href="{{ route('cases.show', $hearing->case_id) }}" class="text-primary-600 hover:text-primary-700">
                        <i class="fas fa-eye"></i>
                    </a>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-calendar-times text-4xl mb-3"></i>
                <p>No upcoming hearings scheduled</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Recent Cases Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Recent Cases</h3>
                <a href="{{ route('cases.index') }}" class="text-sm text-primary-600 hover:text-primary-700">
                    View All Cases <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
        
        @if($recentCases->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Case No.</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Accused</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prosecutor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Updated</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($recentCases as $case)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-primary-600">{{ $case->case_number }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-900">{{ Str::limit($case->title, 30) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-600">{{ $case->accused ?? 'N/A' }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $case->status_badge_class }}">
                                {{ $case->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-600">{{ $case->prosecutor->name ?? 'Unassigned' }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-500">{{ $case->updated_at->diffForHumans() }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <a href="{{ route('cases.show', $case) }}" class="text-primary-600 hover:text-primary-700 mr-3">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('cases.edit', $case) }}" class="text-gray-600 hover:text-gray-700">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="p-8 text-center text-gray-500">
            <i class="fas fa-folder-open text-4xl mb-3"></i>
            <p>No cases found</p>
            <a href="{{ route('cases.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                <i class="fas fa-plus mr-2"></i>
                Create First Case
            </a>
        </div>
        @endif
    </div>

    @push('scripts')
    <script>
        // Status Distribution Chart
        const statusData = @json($statusDistribution);
        const labels = Object.keys(statusData);
        const data = Object.values(statusData);
        
        const colors = {
            'For Filing': '#FCD34D',
            'Under Preliminary Investigation': '#60A5FA',
            'Filed in Court': '#A78BFA',
            'For Archive': '#9CA3AF',
            'Closed': '#34D399'
        };
        
        const backgroundColors = labels.map(label => colors[label] || '#6B7280');
        
        const ctx = document.getElementById('statusChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: backgroundColors,
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                },
                cutout: '60%'
            }
        });
    </script>
    @endpush
</x-layouts.app>
