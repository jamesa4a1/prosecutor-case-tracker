<x-layouts.app title="Reports & Analytics" header="Reports">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900">Reports & Analytics</h1>
        <p class="text-slate-600 mt-2">Generate insights on case load, outcomes, and hearing schedules</p>
    </div>

    <!-- Quick Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Cases</p>
                    <p class="text-3xl font-bold mt-1">{{ \App\Models\CaseModel::count() }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-folder text-xl"></i>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-white/20">
                <span class="text-blue-100 text-sm">
                    <i class="fas fa-arrow-up mr-1"></i>{{ \App\Models\CaseModel::whereMonth('created_at', now()->month)->count() }} this month
                </span>
            </div>
        </div>
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Resolved</p>
                    <p class="text-3xl font-bold mt-1">{{ \App\Models\CaseModel::where('status', 'Closed')->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-white/20">
                @php
                    $total = \App\Models\CaseModel::count();
                    $closed = \App\Models\CaseModel::where('status', 'Closed')->count();
                    $rate = $total > 0 ? round(($closed / $total) * 100) : 0;
                @endphp
                <span class="text-green-100 text-sm">{{ $rate }}% resolution rate</span>
            </div>
        </div>
        <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-amber-100 text-sm font-medium">Active Cases</p>
                    <p class="text-3xl font-bold mt-1">{{ \App\Models\CaseModel::whereNotIn('status', ['Closed', 'Archived'])->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-xl"></i>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-white/20">
                <span class="text-amber-100 text-sm">Requires attention</span>
            </div>
        </div>
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Hearings (30 days)</p>
                    <p class="text-3xl font-bold mt-1">{{ \App\Models\Hearing::where('date_time', '>=', now())->where('date_time', '<=', now()->addDays(30))->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-gavel text-xl"></i>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-white/20">
                <span class="text-purple-100 text-sm">Scheduled appearances</span>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Cases by Status -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-200 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Cases by Status</h2>
                    <p class="text-sm text-slate-500 mt-1">Distribution of cases across statuses</p>
                </div>
                <a href="{{ route('reports.casesByStatus') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                    View Details <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="p-6">
                @if($casesByStatus->count() > 0)
                <div class="space-y-4">
                    @php
                        $totalCases = $casesByStatus->sum('count');
                        $statusColors = [
                            'For Filing' => 'bg-yellow-500',
                            'Under Preliminary Investigation' => 'bg-blue-500',
                            'Filed in Court' => 'bg-purple-500',
                            'For Archive' => 'bg-slate-400',
                            'Closed' => 'bg-green-500',
                            'Pending' => 'bg-amber-500',
                        ];
                    @endphp
                    @foreach($casesByStatus as $status)
                    @php
                        $percentage = $totalCases > 0 ? round(($status->count / $totalCases) * 100) : 0;
                        $color = $statusColors[$status->status] ?? 'bg-slate-400';
                    @endphp
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-slate-700">{{ $status->status }}</span>
                            <span class="text-sm text-slate-500">{{ $status->count }} ({{ $percentage }}%)</span>
                        </div>
                        <div class="w-full h-3 bg-slate-100 rounded-full overflow-hidden">
                            <div class="{{ $color }} h-full rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8 text-slate-500">
                    <i class="fas fa-chart-pie text-4xl mb-3 text-slate-300"></i>
                    <p>No case data available</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Top Prosecutors -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-200 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Cases by Prosecutor</h2>
                    <p class="text-sm text-slate-500 mt-1">Top prosecutors by caseload</p>
                </div>
                <a href="{{ route('reports.casesByProsecutor') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                    View All <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="p-6">
                @if($topProsecutors->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                <th class="pb-3">Prosecutor</th>
                                <th class="pb-3 text-center">Active</th>
                                <th class="pb-3 text-center">Closed</th>
                                <th class="pb-3 text-center">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($topProsecutors as $prosecutor)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="py-3">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center mr-3">
                                            <span class="text-white text-xs font-semibold">{{ strtoupper(substr($prosecutor->name, 0, 2)) }}</span>
                                        </div>
                                        <a href="{{ route('prosecutors.show', $prosecutor) }}" class="font-medium text-slate-900 hover:text-blue-600 transition-colors">
                                            {{ $prosecutor->name }}
                                        </a>
                                    </div>
                                </td>
                                <td class="py-3 text-center">
                                    <span class="inline-flex px-2 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-semibold">
                                        {{ $prosecutor->cases_count - $prosecutor->closed_cases_count }}
                                    </span>
                                </td>
                                <td class="py-3 text-center">
                                    <span class="inline-flex px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                        {{ $prosecutor->closed_cases_count }}
                                    </span>
                                </td>
                                <td class="py-3 text-center">
                                    <span class="inline-flex px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
                                        {{ $prosecutor->cases_count }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-8 text-slate-500">
                    <i class="fas fa-users text-4xl mb-3 text-slate-300"></i>
                    <p>No prosecutor data available</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Upcoming Hearings & Report Links -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Upcoming Hearings (30 days) -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-200 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Upcoming Hearings</h2>
                    <p class="text-sm text-slate-500 mt-1">Next 30 days schedule</p>
                </div>
                <a href="{{ route('hearings.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                    View All <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="divide-y divide-slate-100 max-h-96 overflow-y-auto">
                @if($upcomingHearings->count() > 0)
                @foreach($upcomingHearings as $hearing)
                <div class="px-6 py-4 hover:bg-slate-50 transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex flex-col items-center justify-center flex-shrink-0">
                                <span class="text-xs font-semibold text-blue-600 uppercase">{{ $hearing->date_time->format('M') }}</span>
                                <span class="text-lg font-bold text-blue-700">{{ $hearing->date_time->format('d') }}</span>
                            </div>
                            <div>
                                <p class="font-medium text-slate-900">{{ $hearing->case->case_number ?? 'N/A' }}</p>
                                <p class="text-sm text-slate-500">{{ Str::limit($hearing->case->title ?? 'Unknown', 30) }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-slate-900">{{ $hearing->date_time->format('g:i A') }}</p>
                            <p class="text-xs text-slate-500">{{ $hearing->location ?? 'TBD' }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
                @else
                <div class="p-8 text-center text-slate-500">
                    <i class="fas fa-calendar-check text-4xl mb-3 text-slate-300"></i>
                    <p>No upcoming hearings in the next 30 days</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Quick Report Links -->
        <div class="space-y-4">
            <a href="{{ route('reports.casesByStatus') }}" 
                class="block bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-lg hover:border-blue-300 transition-all duration-300 group">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                        <i class="fas fa-chart-pie text-blue-600 text-xl"></i>
                    </div>
                    <i class="fas fa-arrow-right text-slate-300 group-hover:text-blue-500 group-hover:translate-x-1 transition-all"></i>
                </div>
                <h3 class="font-semibold text-slate-900 mb-1 group-hover:text-blue-600 transition-colors">Cases by Status</h3>
                <p class="text-sm text-slate-500">Detailed breakdown with charts</p>
            </a>

            <a href="{{ route('reports.casesByProsecutor') }}" 
                class="block bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-lg hover:border-green-300 transition-all duration-300 group">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center group-hover:bg-green-200 transition-colors">
                        <i class="fas fa-user-tie text-green-600 text-xl"></i>
                    </div>
                    <i class="fas fa-arrow-right text-slate-300 group-hover:text-green-500 group-hover:translate-x-1 transition-all"></i>
                </div>
                <h3 class="font-semibold text-slate-900 mb-1 group-hover:text-green-600 transition-colors">Prosecutor Performance</h3>
                <p class="text-sm text-slate-500">Workload and resolution rates</p>
            </a>

            <a href="{{ route('reports.monthlySummary') }}" 
                class="block bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-lg hover:border-purple-300 transition-all duration-300 group">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center group-hover:bg-purple-200 transition-colors">
                        <i class="fas fa-calendar-alt text-purple-600 text-xl"></i>
                    </div>
                    <i class="fas fa-arrow-right text-slate-300 group-hover:text-purple-500 group-hover:translate-x-1 transition-all"></i>
                </div>
                <h3 class="font-semibold text-slate-900 mb-1 group-hover:text-purple-600 transition-colors">Monthly Summary</h3>
                <p class="text-sm text-slate-500">Year-over-year trends</p>
            </a>
        </div>
    </div>

    <!-- Export Section -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-200 bg-slate-50">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-download text-blue-600"></i>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Export Data</h2>
                    <p class="text-sm text-slate-500">Download reports in various formats</p>
                </div>
            </div>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Export Cases -->
                <div class="border border-slate-200 rounded-xl p-6 hover:border-blue-300 transition-colors">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-folder text-blue-600"></i>
                        </div>
                        <h3 class="font-semibold text-slate-900">Export Cases</h3>
                    </div>
                    <form action="{{ route('reports.export', 'cases') }}" method="GET" class="space-y-4">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1">From Date</label>
                                <input type="date" name="date_from" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1">To Date</label>
                                <input type="date" name="date_to" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                        <select name="status" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Statuses</option>
                            @foreach(\App\Models\CaseModel::STATUSES as $status)
                            <option value="{{ $status }}">{{ $status }}</option>
                            @endforeach
                        </select>
                        <button type="submit" name="format" value="csv" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium transition-colors">
                            <i class="fas fa-file-csv mr-2"></i>Download CSV
                        </button>
                    </form>
                </div>

                <!-- Export Hearings -->
                <div class="border border-slate-200 rounded-xl p-6 hover:border-purple-300 transition-colors">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar text-purple-600"></i>
                        </div>
                        <h3 class="font-semibold text-slate-900">Export Hearings</h3>
                    </div>
                    <form action="{{ route('reports.export', 'hearings') }}" method="GET" class="space-y-4">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1">From Date</label>
                                <input type="date" name="date_from" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1">To Date</label>
                                <input type="date" name="date_to" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            </div>
                        </div>
                        <select name="prosecutor_id" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            <option value="">All Prosecutors</option>
                            @foreach($prosecutors as $prosecutor)
                            <option value="{{ $prosecutor->id }}">{{ $prosecutor->name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" name="format" value="csv" class="w-full px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 text-sm font-medium transition-colors">
                            <i class="fas fa-file-csv mr-2"></i>Download CSV
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
