@php
    $pendingBookings = \App\Models\Booking::where('status', 'pending')->count();
    $navItems = [
        ['label' => 'Overview',   'route' => 'admin.dashboard',        'pattern' => 'admin.dashboard'],
        ['label' => 'Destinations','route' => 'admin.destinations.index','pattern' => 'admin.destinations.*'],
        ['label' => 'Packages',   'route' => 'admin.packages.index',   'pattern' => 'admin.packages.*'],
        ['label' => 'Bookings',   'route' => 'admin.bookings.index',   'pattern' => 'admin.bookings.index'],
        ['label' => 'Calendar',   'route' => 'admin.bookings.calendar','pattern' => 'admin.bookings.calendar'],
        ['label' => 'Articles',   'route' => 'admin.articles.index',   'pattern' => 'admin.articles.*'],
        ['label' => 'Events',     'route' => 'admin.events.index',     'pattern' => 'admin.events.*'],
        ['label' => 'Categories', 'route' => 'admin.categories.index', 'pattern' => 'admin.categories.*'],
        ['label' => 'Reviews',    'route' => 'admin.reviews.index',    'pattern' => 'admin.reviews.*'],
        ['label' => 'Users',      'route' => 'admin.users.index',      'pattern' => 'admin.users.*'],
        ['label' => 'Subscribers','route' => 'admin.subscribers.index','pattern' => 'admin.subscribers.*'],
    ];
@endphp

<div class="flex gap-1 bg-white border border-gray-200 rounded-2xl p-1.5 mb-6 overflow-x-auto">
    @foreach($navItems as $item)
        @php $active = request()->routeIs($item['pattern']); @endphp
        <a href="{{ route($item['route']) }}"
           class="px-4 py-2 rounded-xl text-sm font-medium whitespace-nowrap transition-colors
               {{ $active ? 'bg-brand-500 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100' }}">
            {{ $item['label'] }}
            @if($item['label'] === 'Bookings' && $pendingBookings > 0)
                <span class="ml-1 px-1.5 py-0.5 {{ $active ? 'bg-white text-brand-600' : 'bg-amber-500 text-white' }} text-xs font-bold rounded-full leading-none">
                    {{ $pendingBookings }}
                </span>
            @endif
        </a>
    @endforeach
</div>
