<x-layouts.app title="Hearing Calendar" header="Hearing Calendar">
    @php
        $currentMonth = request('month', now()->month);
        $currentYear = request('year', now()->year);
        $currentDate = \Carbon\Carbon::createFromDate($currentYear, $currentMonth, 1);
        $prevMonth = $currentDate->copy()->subMonth();
        $nextMonth = $currentDate->copy()->addMonth();
        
        $startOfMonth = $currentDate->copy()->startOfMonth();
        $endOfMonth = $currentDate->copy()->endOfMonth();
        $startOfCalendar = $startOfMonth->copy()->startOfWeek(\Carbon\Carbon::SUNDAY);
        $endOfCalendar = $endOfMonth->copy()->endOfWeek(\Carbon\Carbon::SATURDAY);
        
        $days = [];
        $day = $startOfCalendar->copy();
        while ($day <= $endOfCalendar) {
            $days[] = $day->copy();
            $day->addDay();
        }
        
        $hearingsByDate = $hearings->groupBy(function($hearing) {
            return $hearing->date_time->format('Y-m-d');
        });
    @endphp

    <!-- Header Actions -->
    <div class="mb-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <a href="{{ route('hearings.calendar', ['month' => $prevMonth->month, 'year' => $prevMonth->year]) }}" 
                class="p-2 text-gray-600 hover:text-primary-600">
                <i class="fas fa-chevron-left"></i>
            </a>
            <h2 class="text-xl font-semibold text-gray-900">{{ $currentDate->format('F Y') }}</h2>
            <a href="{{ route('hearings.calendar', ['month' => $nextMonth->month, 'year' => $nextMonth->year]) }}" 
                class="p-2 text-gray-600 hover:text-primary-600">
                <i class="fas fa-chevron-right"></i>
            </a>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('hearings.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                <i class="fas fa-list mr-2"></i>List View
            </a>
            <a href="{{ route('hearings.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                <i class="fas fa-plus mr-2"></i>Schedule Hearing
            </a>
        </div>
    </div>

    <!-- Calendar Grid -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Day Headers -->
        <div class="grid grid-cols-7 bg-gray-50 border-b border-gray-200">
            @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $dayName)
            <div class="px-4 py-3 text-center text-sm font-semibold text-gray-600">{{ $dayName }}</div>
            @endforeach
        </div>

        <!-- Calendar Days -->
        <div class="grid grid-cols-7">
            @foreach($days as $day)
            @php
                $isCurrentMonth = $day->month == $currentMonth;
                $isToday = $day->isToday();
                $dateKey = $day->format('Y-m-d');
                $dayHearings = $hearingsByDate->get($dateKey, collect());
            @endphp
            <div class="min-h-[120px] border-b border-r border-gray-100 p-2 {{ !$isCurrentMonth ? 'bg-gray-50' : '' }}">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-sm font-medium {{ $isToday ? 'w-7 h-7 flex items-center justify-center bg-primary-600 text-white rounded-full' : ($isCurrentMonth ? 'text-gray-900' : 'text-gray-400') }}">
                        {{ $day->day }}
                    </span>
                    @if($dayHearings->count() > 0 && $isCurrentMonth)
                    <span class="text-xs text-gray-500">{{ $dayHearings->count() }}</span>
                    @endif
                </div>
                
                @if($isCurrentMonth)
                <div class="space-y-1">
                    @foreach($dayHearings->take(3) as $hearing)
                    <a href="{{ route('hearings.edit', $hearing) }}" 
                        class="block text-xs p-1 rounded bg-primary-100 text-primary-800 hover:bg-primary-200 truncate"
                        title="{{ $hearing->case->case_number }} - {{ $hearing->date_time->format('g:i A') }}">
                        {{ $hearing->date_time->format('g:i A') }} - {{ Str::limit($hearing->case->case_number, 10) }}
                    </a>
                    @endforeach
                    @if($dayHearings->count() > 3)
                    <p class="text-xs text-gray-500 text-center">+{{ $dayHearings->count() - 3 }} more</p>
                    @endif
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>

    <!-- Upcoming Hearings List -->
    <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Hearings This Month</h3>
        
        @if($hearings->count() > 0)
        <div class="space-y-3">
            @foreach($hearings->sortBy('date_time') as $hearing)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div class="flex items-center space-x-4">
                    <div class="text-center">
                        <div class="text-lg font-bold text-primary-600">{{ $hearing->date_time->format('d') }}</div>
                        <div class="text-xs text-gray-500">{{ $hearing->date_time->format('M') }}</div>
                    </div>
                    <div>
                        <a href="{{ route('cases.show', $hearing->case) }}" class="font-medium text-gray-900 hover:text-primary-600">
                            {{ $hearing->case->case_number }}
                        </a>
                        <p class="text-sm text-gray-500">{{ $hearing->date_time->format('g:i A') }} • {{ $hearing->court_branch ?? 'TBD' }}</p>
                    </div>
                </div>
                <a href="{{ route('hearings.edit', $hearing) }}" class="text-gray-400 hover:text-primary-600">
                    <i class="fas fa-edit"></i>
                </a>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-gray-500 text-center py-4">No hearings scheduled for this month.</p>
        @endif
    </div>
</x-layouts.app>
