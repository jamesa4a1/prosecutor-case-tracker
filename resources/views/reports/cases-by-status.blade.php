<x-layouts.app title="Cases by Status" header="Cases by Status">
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center space-x-2 text-sm">
            <li><a href="{{ route('reports.index') }}" class="text-primary-600 hover:text-primary-700">Reports</a></li>
            <li><i class="fas fa-chevron-right text-gray-400 text-xs"></i></li>
            <li class="text-gray-500">Cases by Status</li>
        </ol>
    </nav>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <form method="GET" action="{{ route('reports.casesByStatus') }}" class="flex flex-wrap items-end gap-4">
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

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Chart -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Distribution</h3>
            <div class="h-80">
                <canvas id="statusChart"></canvas>
            </div>
        </div>

        <!-- Data Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Summary</h3>
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 text-sm font-semibold text-gray-600">Status</th>
                        <th class="text-right py-3 text-sm font-semibold text-gray-600">Count</th>
                        <th class="text-right py-3 text-sm font-semibold text-gray-600">Percentage</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($data as $row)
                    <tr>
                        <td class="py-3">
                            @php
                                $colors = [
                                    'Pending' => 'bg-yellow-100 text-yellow-800',
                                    'Under Investigation' => 'bg-blue-100 text-blue-800',
                                    'Filed' => 'bg-purple-100 text-purple-800',
                                    'Closed' => 'bg-green-100 text-green-800',
                                    'Archived' => 'bg-gray-100 text-gray-800',
                                ];
                            @endphp
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $colors[$row->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $row->status }}
                            </span>
                        </td>
                        <td class="py-3 text-right font-medium text-gray-900">{{ $row->count }}</td>
                        <td class="py-3 text-right text-gray-600">{{ $total > 0 ? round(($row->count / $total) * 100, 1) : 0 }}%</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="border-t-2 border-gray-200">
                        <td class="py-3 font-semibold text-gray-900">Total</td>
                        <td class="py-3 text-right font-semibold text-gray-900">{{ $total }}</td>
                        <td class="py-3 text-right font-semibold text-gray-900">100%</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    @push('scripts')
    <script>
        const ctx = document.getElementById('statusChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($data->pluck('status')) !!},
                datasets: [{
                    data: {!! json_encode($data->pluck('count')) !!},
                    backgroundColor: ['#FCD34D', '#60A5FA', '#A78BFA', '#34D399', '#9CA3AF'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
    @endpush
</x-layouts.app>
