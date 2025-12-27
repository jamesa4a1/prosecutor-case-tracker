<x-layouts.app title="Prosecutor Profile - {{ $prosecutor->name }}" header="Prosecutor Profile">
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center space-x-2 text-sm">
            <li><a href="{{ route('prosecutors.index') }}" class="text-blue-600 hover:text-blue-700 transition-colors">Prosecutors</a></li>
            <li><i class="fas fa-chevron-right text-slate-400 text-xs"></i></li>
            <li class="text-slate-500">{{ $prosecutor->name }}</li>
        </ol>
    </nav>

    <!-- Profile Header Card -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-8">
        <!-- Cover Gradient -->
        <div class="h-32 bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-700 relative">
            @if(in_array(auth()->user()->role ?? '', ['Admin', 'Clerk']))
            <div class="absolute top-4 right-4 flex gap-2">
                <a href="{{ route('prosecutors.edit', $prosecutor) }}" 
                    class="px-4 py-2 bg-white/20 backdrop-blur text-white rounded-lg hover:bg-white/30 transition-colors text-sm font-medium">
                    <i class="fas fa-edit mr-2"></i>Edit Profile
                </a>
            </div>
            @endif
        </div>
        
        <!-- Profile Info -->
        <div class="relative px-8 pb-8">
            <!-- Avatar -->
            <div class="absolute -top-12 left-8">
                <div class="w-24 h-24 bg-white rounded-xl border-4 border-white shadow-xl flex items-center justify-center">
                    <span class="text-3xl font-bold text-blue-600">{{ strtoupper(substr($prosecutor->name, 0, 2)) }}</span>
                </div>
            </div>
            
            <div class="pt-16 flex flex-col lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <h1 class="text-2xl font-bold text-slate-900">{{ $prosecutor->name }}</h1>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $prosecutor->is_active ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-600' }}">
                            <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $prosecutor->is_active ? 'bg-green-500' : 'bg-slate-400' }}"></span>
                            {{ $prosecutor->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <p class="text-lg text-slate-600 mb-3">{{ $prosecutor->position ?? 'Prosecutor' }}</p>
                    <div class="flex flex-wrap gap-4 text-sm text-slate-500">
                        <span class="flex items-center">
                            <i class="fas fa-envelope mr-2 text-slate-400"></i>{{ $prosecutor->email }}
                        </span>
                        @if($prosecutor->office)
                        <span class="flex items-center">
                            <i class="fas fa-building mr-2 text-slate-400"></i>{{ $prosecutor->office }}
                        </span>
                        @endif
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="flex gap-3 mt-4 lg:mt-0">
                    <a href="mailto:{{ $prosecutor->email }}" 
                        class="px-4 py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors font-medium">
                        <i class="fas fa-envelope mr-2"></i>Send Email
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500 font-medium">Total Cases</p>
                    <p class="text-3xl font-bold text-slate-900 mt-1">{{ $caseStats['total'] }}</p>
                </div>
                <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-folder text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500 font-medium">Active Cases</p>
                    <p class="text-3xl font-bold text-amber-600 mt-1">{{ $caseStats['active'] }}</p>
                </div>
                <div class="w-14 h-14 bg-amber-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-hourglass-half text-amber-600 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500 font-medium">Closed Cases</p>
                    <p class="text-3xl font-bold text-green-600 mt-1">{{ $caseStats['closed'] }}</p>
                </div>
                <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500 font-medium">Success Rate</p>
                    <p class="text-3xl font-bold text-purple-600 mt-1">
                        {{ $caseStats['total'] > 0 ? round(($caseStats['closed'] / $caseStats['total']) * 100) : 0 }}%
                    </p>
                </div>
                <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Content -->
    <div x-data="{ activeTab: 'cases' }">
        <!-- Tab Navigation -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 mb-6">
            <div class="flex border-b border-slate-200">
                <button @click="activeTab = 'cases'" 
                    :class="{ 'border-b-2 border-blue-600 text-blue-600': activeTab === 'cases', 'text-slate-500 hover:text-slate-700': activeTab !== 'cases' }"
                    class="px-6 py-4 font-medium text-sm transition-colors">
                    <i class="fas fa-folder-open mr-2"></i>Assigned Cases ({{ $prosecutor->cases->count() }})
                </button>
                <button @click="activeTab = 'hearings'" 
                    :class="{ 'border-b-2 border-blue-600 text-blue-600': activeTab === 'hearings', 'text-slate-500 hover:text-slate-700': activeTab !== 'hearings' }"
                    class="px-6 py-4 font-medium text-sm transition-colors">
                    <i class="fas fa-calendar mr-2"></i>Upcoming Hearings ({{ $prosecutor->assignedHearings->count() }})
                </button>
            </div>
        </div>

        <!-- Cases Tab -->
        <div x-show="activeTab === 'cases'" x-cloak>
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-200 bg-slate-50">
                    <h2 class="text-lg font-semibold text-slate-900">Assigned Cases</h2>
                    <p class="text-sm text-slate-500 mt-1">Cases currently assigned to this prosecutor</p>
                </div>
                @if($prosecutor->cases->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Case Number</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Title</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Offense</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Date Filed</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($prosecutor->cases as $case)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4">
                                    <a href="{{ route('cases.show', $case) }}" class="font-semibold text-blue-600 hover:text-blue-700 transition-colors">
                                        {{ $case->case_number }}
                                    </a>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-slate-900 font-medium">{{ Str::limit($case->title, 40) }}</p>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ Str::limit($case->offense ?? '-', 30) }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full {{ $case->status_badge_class }}">
                                        {{ $case->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-500">
                                    {{ $case->date_filed ? $case->date_filed->format('M d, Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('cases.show', $case) }}" 
                                        class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors text-sm font-medium">
                                        <i class="fas fa-eye mr-1.5"></i>View
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="p-12 text-center">
                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-folder-open text-slate-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">No cases assigned</h3>
                    <p class="text-slate-500">This prosecutor currently has no assigned cases.</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Hearings Tab -->
        <div x-show="activeTab === 'hearings'" x-cloak>
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-200 bg-slate-50">
                    <h2 class="text-lg font-semibold text-slate-900">Upcoming Hearings</h2>
                    <p class="text-sm text-slate-500 mt-1">Scheduled court appearances for this prosecutor</p>
                </div>
                @if($prosecutor->assignedHearings->count() > 0)
                <div class="divide-y divide-slate-100">
                    @foreach($prosecutor->assignedHearings as $hearing)
                    <div class="p-6 hover:bg-slate-50 transition-colors">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div class="flex items-start gap-4">
                                <div class="w-14 h-14 bg-blue-100 rounded-xl flex flex-col items-center justify-center flex-shrink-0">
                                    <span class="text-xs font-semibold text-blue-600 uppercase">{{ $hearing->date_time->format('M') }}</span>
                                    <span class="text-xl font-bold text-blue-700">{{ $hearing->date_time->format('d') }}</span>
                                </div>
                                <div>
                                    <a href="{{ route('cases.show', $hearing->case) }}" class="font-semibold text-slate-900 hover:text-blue-600 transition-colors">
                                        {{ $hearing->case->case_number }} - {{ Str::limit($hearing->case->title, 40) }}
                                    </a>
                                    <div class="flex flex-wrap gap-3 mt-2 text-sm text-slate-500">
                                        <span class="flex items-center">
                                            <i class="fas fa-clock mr-1.5 text-slate-400"></i>{{ $hearing->date_time->format('g:i A') }}
                                        </span>
                                        @if($hearing->location)
                                        <span class="flex items-center">
                                            <i class="fas fa-map-marker-alt mr-1.5 text-slate-400"></i>{{ $hearing->location }}
                                        </span>
                                        @endif
                                        @if($hearing->hearing_type)
                                        <span class="inline-flex items-center px-2 py-0.5 bg-purple-100 text-purple-700 rounded text-xs font-medium">
                                            {{ $hearing->hearing_type }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('cases.show', $hearing->case) }}" 
                                class="inline-flex items-center px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors text-sm font-medium">
                                <i class="fas fa-eye mr-2"></i>View Case
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="p-12 text-center">
                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-calendar text-slate-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">No upcoming hearings</h3>
                    <p class="text-slate-500">This prosecutor has no scheduled hearings.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>
