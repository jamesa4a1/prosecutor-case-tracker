<x-layouts.app title="Cases by Status" header="Cases by Status">
    <!-- Page Header -->
    <div class="mb-6">
        <nav class="mb-4">
            <ol class="flex items-center space-x-2 text-sm">
                <li><a href="{{ route('reports.index') }}" class="text-teal-600 hover:text-teal-700 font-medium">Reports</a></li>
                <li><i class="fas fa-chevron-right text-slate-400 text-xs"></i></li>
                <li class="text-slate-500">Cases by Status</li>
            </ol>
        </nav>
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Cases by Status</h1>
                <p class="text-slate-600 mt-1 text-sm">View case distribution by status</p>
            </div>
            <!-- Filters -->
            <div class="w-80">
                <form method="GET" action="{{ route('reports.casesByStatus') }}" class="flex items-center gap-2">
                    <select name="prosecutor_id" onchange="this.form.submit()" class="flex-1 px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all bg-white hover:border-slate-400">
                        <option value="">All Prosecutors</option>
                        @foreach($prosecutors as $prosecutor)
                        <option value="{{ $prosecutor->id }}" {{ $prosecutorId == $prosecutor->id ? 'selected' : '' }}>{{ $prosecutor->name }}</option>
                        @endforeach
                    </select>
                    @if($prosecutorId)
                    <a href="{{ route('reports.casesByStatus') }}" class="flex items-center justify-center w-9 h-9 text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition-all" title="Clear filter">
                        <i class="fas fa-times text-sm"></i>
                    </a>
                    @endif
                </form>
            </div>
        </div>
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
                                $statusValue = $row->status instanceof \App\Enums\CaseStatus ? $row->status->value : $row->status;
                                $colors = [
                                    'Pending' => 'bg-yellow-100 text-yellow-800',
                                    'Under Investigation' => 'bg-blue-100 text-blue-800',
                                    'Filed' => 'bg-purple-100 text-purple-800',
                                    'Closed' => 'bg-green-100 text-green-800',
                                    'Archived' => 'bg-gray-100 text-gray-800',
                                ];
                            @endphp
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $colors[$statusValue] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $statusValue }}
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
        @php
            $chartLabels = $data->map(function($row) {
                return $row->status instanceof \App\Enums\CaseStatus ? $row->status->value : $row->status;
            });
        @endphp
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    data: {!! json_encode($data->pluck('count')) !!},
                    backgroundColor: ['#0D9488', '#F59E0B', '#6366F1', '#10B981', '#EF4444', '#8B5CF6', '#3B82F6'],
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
                            pointStyle: 'circle'
                        }
                    }
                },
                cutout: '60%'
            }
        });
    </script>
    @endpush
</x-layouts.app>
