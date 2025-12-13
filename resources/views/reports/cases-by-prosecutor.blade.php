<x-layouts.app title="Cases by Prosecutor" header="Cases by Prosecutor">
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center space-x-2 text-sm">
            <li><a href="{{ route('reports.index') }}" class="text-primary-600 hover:text-primary-700">Reports</a></li>
            <li><i class="fas fa-chevron-right text-gray-400 text-xs"></i></li>
            <li class="text-gray-500">Cases by Prosecutor</li>
        </ol>
    </nav>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <form method="GET" action="{{ route('reports.casesByProsecutor') }}" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date From</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}"
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date To</label>
                <input type="date" name="date_to" value="{{ $dateTo }}"
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
            </div>
            <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                Apply Filter
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 gap-6">
        <!-- Chart -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Caseload Distribution</h3>
            <div class="h-80">
                <canvas id="prosecutorChart"></canvas>
            </div>
        </div>

        <!-- Data Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900">Prosecutor Caseload</h3>
            </div>
            @if($data->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Prosecutor</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Position</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Office</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Cases</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Progress</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @php $maxCases = $data->max('cases_count') ?: 1; @endphp
                        @foreach($data as $prosecutor)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <a href="{{ route('prosecutors.show', $prosecutor) }}" class="font-medium text-gray-900 hover:text-primary-600">
                                    {{ $prosecutor->name }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $prosecutor->position ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $prosecutor->office ?? '-' }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-primary-100 text-primary-800">
                                    {{ $prosecutor->cases_count }}
                                </span>
                            </td>
                            <td class="px-6 py-4 w-48">
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-primary-600 h-2.5 rounded-full" style="width: {{ ($prosecutor->cases_count / $maxCases) * 100 }}%"></div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="p-12 text-center text-gray-500">
                <i class="fas fa-chart-bar text-4xl mb-3"></i>
                <p>No data available for the selected period</p>
            </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        const ctx = document.getElementById('prosecutorChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($data->pluck('name')) !!},
                datasets: [{
                    label: 'Cases',
                    data: {!! json_encode($data->pluck('cases_count')) !!},
                    backgroundColor: '#6366F1',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
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
