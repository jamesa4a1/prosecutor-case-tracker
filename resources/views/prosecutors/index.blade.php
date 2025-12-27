<x-layouts.app title="Prosecutors" header="Prosecutors">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Prosecutors</h1>
                <p class="text-slate-600 mt-1">Directory of prosecution officers</p>
            </div>
            @if(in_array(auth()->user()->role ?? '', ['Admin', 'Clerk']))
            <a href="{{ route('prosecutors.create') }}" 
                class="inline-flex items-center px-5 py-3 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                <i class="fas fa-plus mr-2"></i>Add Prosecutor
            </a>
            @endif
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-8">
        <form method="GET" action="{{ route('prosecutors.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search Input -->
            <div class="relative md:col-span-2">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Search by name or email..."
                    class="w-full pl-12 pr-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
            </div>
            
            <!-- Office Filter -->
            <div>
                <select name="office" 
                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    <option value="">All Offices</option>
                    @foreach($offices as $office)
                    <option value="{{ $office }}" {{ request('office') == $office ? 'selected' : '' }}>{{ $office }}</option>
                    @endforeach
                </select>
            </div>
            
            <!-- Status Filter -->
            <div class="flex gap-2">
                <select name="status" 
                    class="flex-1 px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                <button type="submit" class="px-5 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors">
                    <i class="fas fa-filter"></i>
                </button>
                @if(request()->hasAny(['search', 'status', 'office']))
                <a href="{{ route('prosecutors.index') }}" class="px-4 py-3 border border-slate-300 rounded-xl text-slate-600 hover:bg-slate-50 transition-colors">
                    <i class="fas fa-times"></i>
                </a>
                @endif
            </div>
        </form>
    </div>

    @if($prosecutors->count() > 0)
    <!-- Prosecutors Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
        @foreach($prosecutors as $prosecutor)
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 hover:shadow-lg hover:border-blue-300 transition-all duration-300 overflow-hidden group">
            <!-- Card Header with Gradient -->
            <div class="h-20 bg-gradient-to-r from-blue-600 to-blue-700 relative">
                <div class="absolute -bottom-10 left-1/2 -translate-x-1/2">
                    <div class="w-20 h-20 bg-white rounded-full border-4 border-white shadow-lg flex items-center justify-center">
                        <span class="text-2xl font-bold text-blue-600">{{ strtoupper(substr($prosecutor->name, 0, 2)) }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Card Body -->
            <div class="pt-12 px-6 pb-6 text-center">
                <h3 class="text-lg font-bold text-slate-900 mb-1 group-hover:text-blue-600 transition-colors">
                    {{ $prosecutor->name }}
                </h3>
                <p class="text-slate-500 text-sm mb-3">{{ $prosecutor->position ?? 'Prosecutor' }}</p>
                
                <!-- Office Badge -->
                @if($prosecutor->office)
                <div class="inline-flex items-center px-3 py-1 bg-slate-100 text-slate-600 rounded-full text-xs font-medium mb-4">
                    <i class="fas fa-building mr-1.5"></i>{{ $prosecutor->office }}
                </div>
                @endif
                
                <!-- Stats -->
                <div class="flex justify-center gap-4 mb-4">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-blue-600">{{ $prosecutor->cases_count }}</p>
                        <p class="text-xs text-slate-500">Cases</p>
                    </div>
                    <div class="w-px bg-slate-200"></div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-purple-600">{{ $prosecutor->assigned_hearings_count }}</p>
                        <p class="text-xs text-slate-500">Hearings</p>
                    </div>
                </div>
                
                <!-- Status Badge -->
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium mb-4 {{ $prosecutor->is_active ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-600' }}">
                    <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $prosecutor->is_active ? 'bg-green-500' : 'bg-slate-400' }}"></span>
                    {{ $prosecutor->is_active ? 'Active' : 'Inactive' }}
                </span>
                
                <!-- Action Buttons -->
                <div class="flex gap-2">
                    <a href="{{ route('prosecutors.show', $prosecutor) }}" 
                        class="flex-1 py-2.5 px-4 bg-blue-50 text-blue-600 font-medium rounded-lg hover:bg-blue-100 transition-colors text-center">
                        <i class="fas fa-eye mr-2"></i>Profile
                    </a>
                    @if(in_array(auth()->user()->role ?? '', ['Admin', 'Clerk']))
                    <a href="{{ route('prosecutors.edit', $prosecutor) }}" 
                        class="py-2.5 px-4 bg-slate-50 text-slate-600 font-medium rounded-lg hover:bg-slate-100 transition-colors">
                        <i class="fas fa-edit"></i>
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="flex justify-center">
        {{ $prosecutors->links() }}
    </div>
    @else
    <!-- Empty State -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-16 text-center">
        <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-user-tie text-slate-400 text-3xl"></i>
        </div>
        <h3 class="text-xl font-semibold text-slate-900 mb-2">No prosecutors found</h3>
        <p class="text-slate-500 mb-6">
            @if(request()->hasAny(['search', 'status', 'office']))
                No prosecutors match your current filters. Try adjusting your search criteria.
            @else
                Get started by adding a new prosecutor to the directory.
            @endif
        </p>
        @if(in_array(auth()->user()->role ?? '', ['Admin', 'Clerk']))
        <a href="{{ route('prosecutors.create') }}" 
            class="inline-flex items-center px-5 py-3 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-colors">
            <i class="fas fa-plus mr-2"></i>Add Prosecutor
        </a>
        @endif
    </div>
    @endif
</x-layouts.app>
