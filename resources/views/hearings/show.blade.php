<x-layouts.app title="Hearing Details" header="Hearing Details">
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center space-x-2 text-sm">
            <li><a href="{{ route('hearings.index') }}" class="text-primary-600 hover:text-primary-700">Hearings</a></li>
            <li><i class="fas fa-chevron-right text-gray-400 text-xs"></i></li>
            <li class="text-gray-500">Hearing Details</li>
        </ol>
    </nav>

    <!-- Hearing Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between">
            <div>
                <div class="flex items-center space-x-3 mb-2">
                    <h1 class="text-2xl font-bold text-gray-900">Hearing for {{ $hearing->case->case_number ?? 'Unknown Case' }}</h1>
                    @php
                        $statusClasses = [
                            'Scheduled' => 'bg-blue-100 text-blue-800',
                            'Completed' => 'bg-green-100 text-green-800',
                            'Postponed' => 'bg-yellow-100 text-yellow-800',
                            'Cancelled' => 'bg-red-100 text-red-800',
                        ];
                        $status = $hearing->result_status ?? 'Scheduled';
                    @endphp
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusClasses[$status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ $status }}
                    </span>
                </div>
                <p class="text-lg text-gray-600">{{ $hearing->case->title ?? 'N/A' }}</p>
            </div>
            <div class="mt-4 lg:mt-0 flex flex-wrap gap-3">
                <a href="{{ route('hearings.edit', $hearing) }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                    <i class="fas fa-edit mr-2"></i>Edit Hearing
                </a>
                @if($hearing->case)
                <a href="{{ route('cases.show', $hearing->case) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-folder-open mr-2"></i>View Case
                </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Hearing Details Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Main Details -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-calendar-alt text-primary-600 mr-2"></i>
                Hearing Information
            </h3>
            <dl class="space-y-4">
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <dt class="text-sm font-medium text-gray-500">Date & Time</dt>
                    <dd class="text-sm text-gray-900">
                        {{ $hearing->date_time ? $hearing->date_time->format('M d, Y \a\t g:i A') : 'Not scheduled' }}
                    </dd>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <dt class="text-sm font-medium text-gray-500">Court/Branch</dt>
                    <dd class="text-sm text-gray-900">{{ $hearing->court_branch ?? 'Not specified' }}</dd>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <dt class="text-sm font-medium text-gray-500">Hearing Type</dt>
                    <dd class="text-sm text-gray-900">{{ $hearing->hearing_type ?? 'General Hearing' }}</dd>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="text-sm">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClasses[$status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $status }}
                        </span>
                    </dd>
                </div>
                <div class="flex justify-between py-2">
                    <dt class="text-sm font-medium text-gray-500">Created</dt>
                    <dd class="text-sm text-gray-900">{{ $hearing->created_at->format('M d, Y') }}</dd>
                </div>
            </dl>
        </div>

        <!-- Assignment Details -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-user-tie text-primary-600 mr-2"></i>
                Assignment
            </h3>
            <dl class="space-y-4">
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <dt class="text-sm font-medium text-gray-500">Assigned Prosecutor</dt>
                    <dd class="text-sm text-gray-900">
                        @if($hearing->assignedProsecutor)
                            <a href="{{ route('prosecutors.show', $hearing->assignedProsecutor) }}" class="text-primary-600 hover:text-primary-700">
                                {{ $hearing->assignedProsecutor->name }}
                            </a>
                        @else
                            <span class="text-gray-400">Unassigned</span>
                        @endif
                    </dd>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <dt class="text-sm font-medium text-gray-500">Case Number</dt>
                    <dd class="text-sm text-gray-900">
                        @if($hearing->case)
                            <a href="{{ route('cases.show', $hearing->case) }}" class="text-primary-600 hover:text-primary-700">
                                {{ $hearing->case->case_number }}
                            </a>
                        @else
                            <span class="text-gray-400">N/A</span>
                        @endif
                    </dd>
                </div>
                <div class="flex justify-between py-2">
                    <dt class="text-sm font-medium text-gray-500">Case Type</dt>
                    <dd class="text-sm text-gray-900">{{ $hearing->case->type ?? 'N/A' }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Remarks Section -->
    @if($hearing->remarks)
    <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-sticky-note text-primary-600 mr-2"></i>
            Remarks
        </h3>
        <p class="text-gray-600 whitespace-pre-wrap">{{ $hearing->remarks }}</p>
    </div>
    @endif

    <!-- Actions Footer -->
    <div class="mt-6 flex items-center justify-between">
        <a href="{{ route('hearings.index') }}" class="text-gray-600 hover:text-gray-900">
            <i class="fas fa-arrow-left mr-2"></i>Back to Hearings
        </a>
        <div class="flex gap-3">
            <a href="{{ route('hearings.edit', $hearing) }}" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            <form method="POST" action="{{ route('hearings.destroy', $hearing) }}" class="inline"
                onsubmit="return confirm('Are you sure you want to delete this hearing?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    <i class="fas fa-trash mr-2"></i>Delete
                </button>
            </form>
        </div>
    </div>
</x-layouts.app>
