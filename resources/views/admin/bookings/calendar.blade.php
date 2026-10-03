@extends('layouts.app')

@section('title', 'Admin — Booking Calendar')

@section('content')
@php
    $first = $month->copy()->startOfMonth();
    $gridStart = $first->copy()->startOfWeek(\Illuminate\Support\Carbon::MONDAY);
    $gridEnd = $first->copy()->endOfMonth()->endOfWeek(\Illuminate\Support\Carbon::SUNDAY);
    $query = fn (array $extra = []) => array_filter(array_merge([
        'month' => $month->format('Y-m'),
        'package' => $packageFilter ?: null,
        'cancelled' => $showCancelled ? 1 : null,
    ], $extra), fn ($v) => $v !== null && $v !== '');
    $chip = [
        'confirmed' => 'bg-green-100 text-green-800 hover:bg-green-200',
        'pending' => 'bg-amber-100 text-amber-800 hover:bg-amber-200',
        'completed' => 'bg-blue-100 text-blue-800 hover:bg-blue-200',
        'cancelled' => 'bg-gray-100 text-gray-400 line-through hover:bg-gray-200',
    ];
    $monthPax = $bookings->where('status', '!=', 'cancelled')->sum('pax');
@endphp
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Admin Panel</h1>
        <p class="text-sm text-gray-500 mt-1">Manage Overlander data — Travel Agency</p>
    </div>

    @include('admin._nav')

    <div class="bg-white rounded-2xl border border-gray-200 p-4 flex flex-wrap items-center gap-3">
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.bookings.calendar', $query(['month' => $month->copy()->subMonth()->format('Y-m')])) }}"
               class="w-9 h-9 flex items-center justify-center rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50" aria-label="Previous month">‹</a>
            <h2 class="text-lg font-bold text-gray-900 w-44 text-center">{{ $month->format('F Y') }}</h2>
            <a href="{{ route('admin.bookings.calendar', $query(['month' => $month->copy()->addMonth()->format('Y-m')])) }}"
               class="w-9 h-9 flex items-center justify-center rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50" aria-label="Next month">›</a>
            <a href="{{ route('admin.bookings.calendar', $query(['month' => now()->format('Y-m')])) }}"
               class="ml-1 px-3 py-2 text-xs font-medium rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50">Today</a>
        </div>

        <form method="GET" class="flex flex-wrap items-center gap-3 sm:ml-auto text-sm">
            <input type="hidden" name="month" value="{{ $month->format('Y-m') }}">
            <select name="package" onchange="this.form.submit()"
                    class="border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
                <option value="">All trips</option>
                <option value="custom" @selected($packageFilter === 'custom')>Custom trips</option>
                @foreach($packages as $p)
                    <option value="{{ $p->id }}" @selected($packageFilter === (string) $p->id)>{{ \Illuminate\Support\Str::limit($p->name, 48) }}</option>
                @endforeach
            </select>
            <label class="flex items-center gap-2 text-gray-600 cursor-pointer">
                <input type="checkbox" name="cancelled" value="1" @checked($showCancelled) onchange="this.form.submit()" class="rounded border-gray-300 text-brand-500">
                Show cancelled
            </label>
        </form>
    </div>

    <div class="flex flex-wrap gap-4 text-xs text-gray-500">
        <span><strong class="text-gray-800">{{ $bookings->where('status', '!=', 'cancelled')->count() }}</strong> bookings this month</span>
        <span><strong class="text-gray-800">{{ $monthPax }}</strong> travelers</span>
        <span class="flex items-center gap-1"><span class="w-3 h-3 rounded bg-amber-200"></span> Pending</span>
        <span class="flex items-center gap-1"><span class="w-3 h-3 rounded bg-green-200"></span> Confirmed</span>
        <span class="flex items-center gap-1"><span class="w-3 h-3 rounded bg-blue-200"></span> Completed</span>
        @if($selectedPackage?->capacity)
            <span>Capacity: <strong class="text-gray-800">{{ $selectedPackage->capacity }}</strong> per date</span>
        @endif
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 overflow-x-auto">
        <div class="min-w-[760px]">
            <div class="grid grid-cols-7 bg-gray-50 border-b border-gray-200 text-xs font-medium text-gray-500 uppercase tracking-wide">
                @foreach(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $d)
                    <div class="px-3 py-2 text-center">{{ $d }}</div>
                @endforeach
            </div>

            <div class="grid grid-cols-7">
                @for($day = $gridStart->copy(); $day->lte($gridEnd); $day->addDay())
                    @php
                        $inMonth = $day->month === $month->month;
                        $list = $byDate[$day->toDateString()] ?? collect();
                        $active = $list->where('status', '!=', 'cancelled');
                        $paxDay = $active->sum('pax');
                        $isToday = $day->isToday();
                        $full = $selectedPackage?->capacity && $paxDay >= $selectedPackage->capacity;
                    @endphp
                    <div class="min-h-28 border-b border-r border-gray-100 p-1.5 {{ $inMonth ? 'bg-white' : 'bg-gray-50/70' }} {{ $full ? 'ring-1 ring-inset ring-red-300' : '' }}">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-xs font-medium {{ $isToday ? 'bg-brand-500 text-white rounded-full w-6 h-6 flex items-center justify-center' : ($inMonth ? 'text-gray-700' : 'text-gray-300') }}">{{ $day->day }}</span>
                            @if($paxDay > 0)
                                <span class="text-[10px] font-semibold {{ $full ? 'text-red-600' : 'text-gray-400' }}">
                                    {{ $paxDay }}{{ $selectedPackage?->capacity ? '/' . $selectedPackage->capacity : '' }} pax
                                </span>
                            @endif
                        </div>
                        <div class="space-y-1">
                            @foreach($list as $b)
                                <a href="{{ route('admin.bookings.index', ['search' => $b->guest_email]) }}"
                                   title="#{{ $b->id }} · {{ $b->guest_name }} · {{ $b->pax }} pax · {{ $b->is_custom ? 'Custom trip' : $b->package?->name }} · {{ ucfirst($b->status) }}"
                                   class="block rounded-md px-1.5 py-1 text-[11px] leading-tight transition-colors {{ $chip[$b->status] ?? $chip['pending'] }}">
                                    <span class="font-semibold">{{ $b->pax }}× {{ \Illuminate\Support\Str::limit($b->guest_name, 14, '…') }}</span>
                                    <span class="block opacity-70 truncate">{{ $b->is_custom ? 'Custom trip' : \Illuminate\Support\Str::limit($b->package?->name, 22, '…') }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>

    <p class="text-xs text-gray-400">Click a booking to open it in the bookings list, where you can change its status or message the traveler on WhatsApp.</p>
</div>
@endsection
