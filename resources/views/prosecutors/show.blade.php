<x-layouts.app title="Prosecutor Profile" header="Prosecutor Profile">
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center space-x-2 text-sm">
            <li><a href="{{ route('prosecutors.index') }}" class="text-primary-600 hover:text-primary-700">Prosecutors</a></li>
            <li><i class="fas fa-chevron-right text-gray-400 text-xs"></i></li>
            <li class="text-gray-500">{{ $prosecutor->name }}</li>
        </ol>
    </nav>

    <!-- Profile Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center">
                    <span class="text-2xl font-bold text-primary-600">{{ substr($prosecutor->name, 0, 2) }}</span>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $prosecutor->name }}</h1>
                    <p class="text-gray-600">{{ $prosecutor->position ?? 'Prosecutor' }}</p>
                    <div class="flex items-center space-x-4 mt-1">
                        <span class="text-sm text-gray-500">
                            <i class="fas fa-envelope mr-1"></i>{{ $prosecutor->email }}
                        </span>
                        @if($prosecutor->office)
                        <span class="text-sm text-gray-500">
                            <i class="fas fa-building mr-1"></i>{{ $prosecutor->office }}
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="mt-4 md:mt-0 flex items-center space-x-3">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $prosecutor->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                    {{ $prosecutor->is_active ? 'Active' : 'Inactive' }}
                </span>
                <a href="{{ route('prosecutors.edit', $prosecutor) }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-folder text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Cases</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $caseStats['total'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-hourglass-half text-yellow-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Active Cases</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $caseStats['active'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Closed Cases</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $caseStats['closed'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Cases -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">Recent Cases</h2>
            </div>
            @if($prosecutor->cases->count() > 0)
            <div class="divide-y divide-gray-100">
                @foreach($prosecutor->cases as $case)
                <div class="p-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center justify-between">
                        <div>
                            <a href="{{ route('cases.show', $case) }}" class="font-medium text-gray-900 hover:text-primary-600">
                                {{ $case->case_number }}
                            </a>
                            <p class="text-sm text-gray-500">{{ Str::limit($case->title, 40) }}</p>
                        </div>
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $case->status_badge_class }}">
                            {{ $case->status }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="p-6 text-center text-gray-500">
                <i class="fas fa-folder-open text-3xl mb-2"></i>
                <p>No cases assigned</p>
            </div>
            @endif
        </div>

        <!-- Upcoming Hearings -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">Upcoming Hearings</h2>
            </div>
            @if($prosecutor->assignedHearings->count() > 0)
            <div class="divide-y divide-gray-100">
                @foreach($prosecutor->assignedHearings as $hearing)
                <div class="p-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-start justify-between">
                        <div>
                            <a href="{{ route('cases.show', $hearing->case) }}" class="font-medium text-gray-900 hover:text-primary-600">
                                {{ $hearing->case->case_number }}
                            </a>
                            <p class="text-sm text-gray-500">{{ $hearing->court_branch ?? 'TBD' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900">{{ $hearing->date_time->format('M d, Y') }}</p>
                            <p class="text-sm text-gray-500">{{ $hearing->date_time->format('g:i A') }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="p-6 text-center text-gray-500">
                <i class="fas fa-calendar text-3xl mb-2"></i>
                <p>No upcoming hearings</p>
            </div>
            @endif
        </div>
    </div>
</x-layouts.app>
