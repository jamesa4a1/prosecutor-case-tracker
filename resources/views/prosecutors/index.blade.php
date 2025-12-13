<x-layouts.app title="Prosecutors" header="Prosecutors">
    <!-- Header Actions -->
    <div class="mb-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div class="flex-1 max-w-lg">
            <form method="GET" action="{{ route('prosecutors.index') }}" class="flex gap-2">
                <div class="relative flex-1">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}" 
                        placeholder="Search prosecutors..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                    Search
                </button>
            </form>
        </div>
        <a href="{{ route('prosecutors.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
            <i class="fas fa-plus mr-2"></i>Add Prosecutor
        </a>
    </div>

    <!-- Filters -->
    <div class="mb-6 flex flex-wrap gap-4">
        <form method="GET" action="{{ route('prosecutors.index') }}" class="flex flex-wrap gap-4">
            <input type="hidden" name="search" value="{{ request('search') }}">
            
            <select name="status" onchange="this.form.submit()"
                class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                <option value="">All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>

            <select name="office" onchange="this.form.submit()"
                class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                <option value="">All Offices</option>
                @foreach($offices as $office)
                <option value="{{ $office }}" {{ request('office') == $office ? 'selected' : '' }}>{{ $office }}</option>
                @endforeach
            </select>

            @if(request()->hasAny(['status', 'office']))
            <a href="{{ route('prosecutors.index', ['search' => request('search')]) }}" 
                class="px-3 py-2 text-gray-600 hover:text-gray-900">
                Clear Filters
            </a>
            @endif
        </form>
    </div>

    <!-- Prosecutors Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        @if($prosecutors->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Position</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Office</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Cases</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Hearings</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($prosecutors as $prosecutor)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center mr-3">
                                    <span class="text-primary-600 font-semibold">{{ substr($prosecutor->name, 0, 2) }}</span>
                                </div>
                                <div>
                                    <a href="{{ route('prosecutors.show', $prosecutor) }}" class="font-medium text-gray-900 hover:text-primary-600">
                                        {{ $prosecutor->name }}
                                    </a>
                                    <p class="text-sm text-gray-500">{{ $prosecutor->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $prosecutor->position ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $prosecutor->office ?? '-' }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $prosecutor->cases_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                {{ $prosecutor->assigned_hearings_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $prosecutor->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $prosecutor->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route('prosecutors.show', $prosecutor) }}" class="p-2 text-gray-600 hover:text-primary-600" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('prosecutors.edit', $prosecutor) }}" class="p-2 text-gray-600 hover:text-primary-600" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('prosecutors.destroy', $prosecutor) }}" class="inline"
                                    onsubmit="return confirm('Are you sure you want to delete this prosecutor?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-600 hover:text-red-600" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $prosecutors->links() }}
        </div>
        @else
        <div class="p-12 text-center">
            <i class="fas fa-user-tie text-gray-300 text-5xl mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No prosecutors found</h3>
            <p class="text-gray-500 mb-4">Get started by adding a new prosecutor.</p>
            <a href="{{ route('prosecutors.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                <i class="fas fa-plus mr-2"></i>Add Prosecutor
            </a>
        </div>
        @endif
    </div>
</x-layouts.app>
