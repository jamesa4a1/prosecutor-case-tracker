<x-layouts.app title="Monthly Summary" header="Monthly Summary">
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center space-x-2 text-sm">
            <li><a href="{{ route('reports.index') }}" class="text-primary-600 hover:text-primary-700">Reports</a></li>
            <li><i class="fas fa-chevron-right text-gray-400 text-xs"></i></li>
            <li class="text-gray-500">Monthly Summary</li>
        </ol>
    </nav>

    <!-- Year Filter -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <form method="GET" action="{{ route('reports.monthlySummary') }}" class="flex items-center gap-4">
            <label class="text-sm font-medium text-gray-700">Year:</label>
            <select name="year" onchange="this.form.submit()"
                class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                @for($y = now()->year; $y >= now()->year - 5; $y--)
                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </form>
    </div>

    <!-- Chart -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Monthly Trends - {{ $year }}</h3>
        <div class="h-80">
            <canvas id="monthlyChart"></canvas>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900">Monthly Breakdown</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Month</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Total Cases</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Pending</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Closed</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Hearings</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @php
                        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                        $totalCases = 0;
                        $totalPending = 0;
                        $totalClosed = 0;
                        $totalHearings = 0;
                    @endphp
                    @for($m = 1; $m <= 12; $m++)
                    @php
                        $monthData = $monthlyData->firstWhere('month', $m);
                        $hearingData = $hearingsData->get($m);
                        $cases = $monthData->total ?? 0;
                        $pending = $monthData->pending ?? 0;
                        $closed = $monthData->closed ?? 0;
                        $hearings = $hearingData->total ?? 0;
                        
                        $totalCases += $cases;
                        $totalPending += $pending;
                        $totalClosed += $closed;
                        $totalHearings += $hearings;
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $months[$m-1] }}</td>
                        <td class="px-6 py-4 text-center text-gray-600">{{ $cases }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $pending > 0 ? 'bg-yellow-100 text-yellow-800' : 'text-gray-500' }}">
                                {{ $pending }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $closed > 0 ? 'bg-green-100 text-green-800' : 'text-gray-500' }}">
                                {{ $closed }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $hearings > 0 ? 'bg-purple-100 text-purple-800' : 'text-gray-500' }}">
                                {{ $hearings }}
                            </span>
                        </td>
                    </tr>
                    @endfor
                </tbody>
                <tfoot>
                    <tr class="bg-gray-50 font-semibold">
                        <td class="px-6 py-4 text-gray-900">Total</td>
                        <td class="px-6 py-4 text-center text-gray-900">{{ $totalCases }}</td>
                        <td class="px-6 py-4 text-center text-yellow-800">{{ $totalPending }}</td>
                        <td class="px-6 py-4 text-center text-green-800">{{ $totalClosed }}</td>
                        <td class="px-6 py-4 text-center text-purple-800">{{ $totalHearings }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    @push('scripts')
    <script>
        const ctx = document.getElementById('monthlyChart').getContext('2d');
        
        @php
            $chartLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            $caseData = [];
            $hearingChartData = [];
            for ($m = 1; $m <= 12; $m++) {
                $monthData = $monthlyData->firstWhere('month', $m);
                $hearingData = $hearingsData->get($m);
                $caseData[] = $monthData->total ?? 0;
                $hearingChartData[] = $hearingData->total ?? 0;
            }
        @endphp
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [
                    {
                        label: 'Cases Filed',
                        data: {!! json_encode($caseData) !!},
                        borderColor: '#6366F1',
                        backgroundColor: 'rgba(99, 102, 241, 0.1)',
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Hearings',
                        data: {!! json_encode($hearingChartData) !!},
                        borderColor: '#A855F7',
                        backgroundColor: 'rgba(168, 85, 247, 0.1)',
                        fill: true,
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>
    @endpush
</x-layouts.app>
