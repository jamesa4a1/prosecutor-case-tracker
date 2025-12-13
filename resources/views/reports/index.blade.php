<x-layouts.app title="Reports" header="Reports">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Cases by Status -->
        <a href="{{ route('reports.casesByStatus') }}" 
            class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow group">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4 group-hover:bg-blue-200 transition-colors">
                <i class="fas fa-chart-pie text-blue-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Cases by Status</h3>
            <p class="text-sm text-gray-500">View distribution of cases across different statuses</p>
        </a>

        <!-- Cases by Prosecutor -->
        <a href="{{ route('reports.casesByProsecutor') }}" 
            class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow group">
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4 group-hover:bg-green-200 transition-colors">
                <i class="fas fa-user-tie text-green-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Cases by Prosecutor</h3>
            <p class="text-sm text-gray-500">Caseload distribution across prosecutors</p>
        </a>

        <!-- Monthly Summary -->
        <a href="{{ route('reports.monthlySummary') }}" 
            class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow group">
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4 group-hover:bg-purple-200 transition-colors">
                <i class="fas fa-calendar-alt text-purple-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Monthly Summary</h3>
            <p class="text-sm text-gray-500">Monthly trends for cases and hearings</p>
        </a>
    </div>

    <!-- Export Section -->
    <div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Export Data</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Export Cases -->
            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="font-medium text-gray-900 mb-3">Export Cases</h3>
                <form action="{{ route('reports.export', 'cases') }}" method="GET" class="space-y-3">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">From</label>
                            <input type="date" name="date_from" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">To</label>
                            <input type="date" name="date_to" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                    </div>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        <option value="">All Statuses</option>
                        <option value="Pending">Pending</option>
                        <option value="Under Investigation">Under Investigation</option>
                        <option value="Filed">Filed</option>
                        <option value="Closed">Closed</option>
                        <option value="Archived">Archived</option>
                    </select>
                    <button type="submit" class="w-full px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 text-sm">
                        <i class="fas fa-download mr-2"></i>Export CSV
                    </button>
                </form>
            </div>

            <!-- Export Hearings -->
            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="font-medium text-gray-900 mb-3">Export Hearings</h3>
                <form action="{{ route('reports.export', 'hearings') }}" method="GET" class="space-y-3">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">From</label>
                            <input type="date" name="date_from" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">To</label>
                            <input type="date" name="date_to" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                    </div>
                    <button type="submit" class="w-full px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 text-sm">
                        <i class="fas fa-download mr-2"></i>Export CSV
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
