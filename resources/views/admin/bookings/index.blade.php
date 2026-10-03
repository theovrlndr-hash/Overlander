@extends('layouts.app')

@section('title', 'Admin — Bookings')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Admin Panel</h1>
        <p class="text-sm text-gray-500 mt-1">Manage Overlander data — Travel Agency</p>
    </div>

    @include('admin._nav')

    <form method="GET" class="bg-white rounded-2xl border border-gray-200 p-4 flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-48">
            <label class="block text-xs font-medium text-gray-500 mb-1">Search guest name / email</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
        </div>
        <div class="min-w-40">
            <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
            <select name="status" class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
                <option value="">All statuses</option>
                @foreach(['pending', 'confirmed', 'cancelled', 'completed'] as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="px-4 py-2 bg-brand-500 text-white text-sm font-medium rounded-xl hover:bg-brand-600">Search</button>
        <a href="{{ route('admin.bookings.export', request()->only(['status', 'search'])) }}"
           class="px-4 py-2 border border-gray-300 text-gray-600 text-sm font-medium rounded-xl hover:bg-gray-50 transition-colors">Export CSV</a>
    </form>

    <div class="flex items-center gap-2 text-xs text-gray-500">
        <span>WhatsApp message language:</span>
        @foreach(\App\Support\BookingMessages::LANGS as $code => $label)
            <a href="{{ request()->fullUrlWithQuery(['wa' => $code]) }}"
               class="px-2.5 py-1 rounded-full border {{ $waLang === $code ? 'bg-brand-500 text-white border-brand-500' : 'border-gray-200 text-gray-600 hover:bg-gray-50' }}">{{ $label }}</a>
        @endforeach
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wide">
                    <tr>
                        <th class="text-left px-6 py-3 font-medium">Guest</th>
                        <th class="text-left px-6 py-3 font-medium">Package</th>
                        <th class="text-left px-6 py-3 font-medium">Trip Date</th>
                        <th class="text-center px-6 py-3 font-medium">Pax</th>
                        <th class="text-right px-6 py-3 font-medium">Total</th>
                        <th class="text-center px-6 py-3 font-medium">Status</th>
                        <th class="text-left px-6 py-3 font-medium">WhatsApp</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($bookings as $booking)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3">
                            <p class="font-medium text-gray-900">{{ $booking->guest_name }}</p>
                            <p class="text-xs text-gray-500">{{ $booking->guest_email }} · {{ $booking->guest_phone }}</p>
                        </td>
                        <td class="px-6 py-3 text-gray-700">
                            {{ $booking->is_custom ? 'Custom Trip' : $booking->package?->name }}
                            @if($booking->packagePlan) <span class="text-xs text-gray-400">({{ $booking->packagePlan->name }})</span> @endif
                            @if($booking->is_custom && $booking->destinations->isNotEmpty())
                                <p class="text-xs text-brand-600 mt-0.5">{{ $booking->destinations->pluck('name')->join(' → ') }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-3 text-gray-500">{{ $booking->trip_date->format('d M Y') }}</td>
                        <td class="px-6 py-3 text-center text-gray-700">{{ $booking->pax }}</td>
                        <td class="px-6 py-3 text-right text-gray-700">{{ $booking->total_price ? '$' . number_format($booking->total_price, 0) : '—' }}</td>
                        <td class="px-6 py-3">
                            <form method="POST" action="{{ route('admin.bookings.status', $booking) }}">
                                @csrf @method('PATCH')
                                <select name="status" onchange="this.form.submit()"
                                        class="text-xs font-medium rounded-full px-2 py-1 border-0
                                        {{ match($booking->status) {
                                            'confirmed' => 'bg-green-100 text-green-700',
                                            'cancelled' => 'bg-red-100 text-red-700',
                                            'completed' => 'bg-blue-100 text-blue-700',
                                            default => 'bg-amber-100 text-amber-700',
                                        } }}">
                                    @foreach(['pending', 'confirmed', 'cancelled', 'completed'] as $status)
                                        <option value="{{ $status }}" @selected($booking->status === $status)>{{ ucfirst($status) }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </td>
                        <td class="px-6 py-3">
                            @php $templates = \App\Support\BookingMessages::available($booking); @endphp
                            @if(\App\Support\BookingMessages::phone($booking->guest_phone))
                            <select onchange="if (this.value) { window.open(this.value, '_blank', 'noopener'); this.selectedIndex = 0; }"
                                    aria-label="Send a WhatsApp message"
                                    class="text-xs border border-green-200 text-green-700 bg-green-50 rounded-lg px-2 py-1.5 cursor-pointer hover:bg-green-100 focus:border-green-400 focus:ring-2 focus:ring-green-100">
                                <option value="">💬 Message…</option>
                                @foreach($templates as $key => $label)
                                    <option value="{{ \App\Support\BookingMessages::url($booking, $key, $waLang) }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @else
                                <span class="text-xs text-gray-300">No phone</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7"class="px-6 py-12 text-center text-gray-400">No bookings yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($bookings->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">{{ $bookings->links() }}</div>
        @endif
    </div>
</div>
@endsection
