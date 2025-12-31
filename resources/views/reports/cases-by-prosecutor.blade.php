<x-layouts.app title="Cases by Prosecutor" header="Cases by Prosecutor">
    <!-- Page Header -->
    <div class="mb-8">
        <nav class="mb-4">
            <ol class="flex items-center space-x-2 text-sm">
                <li><a href="{{ route('reports.index') }}" class="text-teal-600 hover:text-teal-700 font-medium">Reports</a></li>
                <li><i class="fas fa-chevron-right text-slate-400 text-xs"></i></li>
                <li class="text-slate-500">Cases by Prosecutor</li>
            </ol>
        </nav>
        <h1 class="text-3xl font-bold text-slate-900">Cases by Prosecutor</h1>
        <p class="text-slate-600 mt-2">Analyze case distribution and workload across prosecutors</p>
    </div>

    <!-- Filter Card -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-8">
        <form method="GET" action="{{ route('reports.casesByProsecutor') }}" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-slate-700 mb-2">
                    <i class="fas fa-calendar-alt mr-1 text-slate-400"></i> From Date
                </label>
                <input type="date" name="date_from" value="{{ $dateFrom }}"
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors">
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-slate-700 mb-2">
                    <i class="fas fa-calendar-alt mr-1 text-slate-400"></i> To Date
                </label>
                <input type="date" name="date_to" value="{{ $dateTo }}"
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-6 py-2.5 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors font-medium">
                    <i class="fas fa-filter mr-2"></i>Apply Filter
                </button>
                <a href="{{ route('reports.casesByProsecutor') }}" class="px-4 py-2.5 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors font-medium">
                    <i class="fas fa-redo"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-teal-100 text-sm font-medium">Total Cases</p>
                    <p class="text-3xl font-bold mt-1">{{ $totalCases }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-folder-open text-xl"></i>
                </div>
            </div>
            <p class="text-teal-100 text-sm mt-3">
                {{ \Carbon\Carbon::parse($dateFrom)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($dateTo)->format('M d, Y') }}
            </p>
        </div>
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Closed Cases</p>
                    <p class="text-3xl font-bold mt-1">{{ $totalClosed }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
            </div>
            <p class="text-green-100 text-sm mt-3">
                {{ $totalCases > 0 ? round(($totalClosed / $totalCases) * 100) : 0 }}% resolution rate
            </p>
        </div>
        <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-amber-100 text-sm font-medium">Pending Cases</p>
                    <p class="text-3xl font-bold mt-1">{{ $totalPending }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-xl"></i>
                </div>
            </div>
            <p class="text-amber-100 text-sm mt-3">
                Awaiting resolution
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <!-- Chart Section -->
        <div class="xl:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden h-full">
                <div class="px-6 py-5 border-b border-slate-200">
                    <h3 class="text-lg font-semibold text-slate-900">
                        <i class="fas fa-chart-pie mr-2 text-teal-600"></i>Caseload Distribution
                    </h3>
                </div>
                <div class="p-6">
                    @if($data->where('cases_count', '>', 0)->count() > 0)
                    <div class="h-64">
                        <canvas id="prosecutorPieChart"></canvas>
                    </div>
                    @else
                    <div class="h-64 flex items-center justify-center text-slate-500">
                        <div class="text-center">
                            <i class="fas fa-chart-pie text-4xl mb-3 text-slate-300"></i>
                            <p>No case data for this period</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="xl:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-200 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-slate-900">
                        <i class="fas fa-users mr-2 text-teal-600"></i>Prosecutor Workload
                    </h3>
                    <span class="text-sm text-slate-500">{{ $data->count() }} prosecutors</span>
                </div>
                
                @if($data->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200">
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Prosecutor</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Closed</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Pending</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Workload</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @php $maxCases = $data->max('cases_count') ?: 1; @endphp
                            @foreach($data as $prosecutor)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-teal-100 rounded-full flex items-center justify-center mr-3">
                                            <span class="text-teal-700 font-semibold text-sm">{{ strtoupper(substr($prosecutor->name, 0, 2)) }}</span>
                                        </div>
                                        <div>
                                            <a href="{{ route('prosecutors.show', $prosecutor) }}" class="font-medium text-slate-900 hover:text-teal-600 transition-colors">
                                                {{ $prosecutor->name }}
                                            </a>
                                            <p class="text-xs text-slate-500">{{ $prosecutor->position ?? 'Prosecutor' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ $prosecutor->cases_count > 0 ? 'bg-teal-100 text-teal-800' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $prosecutor->cases_count }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $prosecutor->closed_cases_count > 0 ? 'bg-green-100 text-green-800' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $prosecutor->closed_cases_count }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $prosecutor->pending_cases_count > 0 ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $prosecutor->pending_cases_count }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 w-48">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-1 bg-slate-200 rounded-full h-2.5 overflow-hidden">
                                            <div class="bg-gradient-to-r from-teal-500 to-teal-600 h-2.5 rounded-full transition-all duration-500" 
                                                 style="width: {{ $maxCases > 0 ? ($prosecutor->cases_count / $maxCases) * 100 : 0 }}%"></div>
                                        </div>
                                        <span class="text-xs text-slate-500 w-10 text-right">
                                            {{ $totalCases > 0 ? round(($prosecutor->cases_count / $totalCases) * 100) : 0 }}%
                                        </span>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-slate-50 font-semibold">
                                <td class="px-6 py-4 text-slate-900">Total</td>
                                <td class="px-6 py-4 text-center text-teal-800">{{ $totalCases }}</td>
                                <td class="px-6 py-4 text-center text-green-800">{{ $totalClosed }}</td>
                                <td class="px-6 py-4 text-center text-amber-800">{{ $totalPending }}</td>
                                <td class="px-6 py-4"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @else
                <div class="p-12 text-center">
                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-2xl text-slate-400"></i>
                    </div>
                    <h4 class="text-lg font-medium text-slate-900 mb-2">No Prosecutors Found</h4>
                    <p class="text-slate-500 text-sm">There are no active prosecutors in the system.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        @if($data->where('cases_count', '>', 0)->count() > 0)
        const pieCtx = document.getElementById('prosecutorPieChart').getContext('2d');
        
        // Filter prosecutors with cases
        const prosecutorsWithCases = {!! json_encode($data->where('cases_count', '>', 0)->values()) !!};
        
        const colors = [
            '#0D9488', '#0891B2', '#6366F1', '#8B5CF6', '#EC4899', 
            '#F59E0B', '#10B981', '#EF4444', '#3B82F6', '#84CC16'
        ];
        
        new Chart(pieCtx, {
            type: 'doughnut',
            data: {
                labels: prosecutorsWithCases.map(p => p.name),
                datasets: [{
                    data: prosecutorsWithCases.map(p => p.cases_count),
                    backgroundColor: colors.slice(0, prosecutorsWithCases.length),
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
                            padding: 15,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            font: {
                                size: 11
                            }
                        }
                    }
                },
                cutout: '60%'
            }
        });
        @endif
    </script>
    @endpush
</x-layouts.app>
