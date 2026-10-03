@extends('layouts.app')

@section('title', 'Destinations — Overlander')

@section('content')
<div class="space-y-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">{{ __('destinations.title') }}</h1>
        <p class="text-gray-500 mt-1">{{ __('destinations.subtitle') }}</p>
    </div>

    <form method="GET" class="flex flex-wrap gap-3 items-end bg-white rounded-2xl border border-gray-200 p-4">
        <div class="flex-1 min-w-48">
            <label class="block text-xs font-medium text-gray-500 mb-1">{{ __('destinations.search_label') }}</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
        </div>
        <div class="flex flex-wrap gap-2">
            @foreach($categories as $cat)
                <a href="{{ route('destinations.index', ['category' => $cat->slug]) }}"
                   class="category-chip px-3 py-1.5 rounded-full text-xs font-medium border
                   {{ request('category') === $cat->slug ? 'bg-brand-500 text-white border-brand-500' : 'border-gray-200 text-gray-600 hover:bg-gray-50' }}">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>
        <button type="submit" class="btn-pop px-4 py-2 bg-brand-500 text-white text-sm font-medium rounded-xl hover:bg-brand-600">{{ __('destinations.search') }}</button>
    </form>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        @forelse($destinations as $destination)
        <a href="{{ route('destinations.show', $destination) }}" class="block rounded-2xl overflow-hidden border border-gray-100 provider-card">
            <img src="{{ $destination->cover_photo_url }}" class="w-full h-44 object-cover" alt="{{ $destination->name }}">
            <div class="p-4">
                <p class="font-semibold text-gray-800">{{ $destination->name }}</p>
                <p class="text-xs text-gray-500">{{ $destination->location }}</p>
                @include('_rating', ['avg' => $destination->reviews_avg_rating ?? 0, 'count' => $destination->reviews_count, 'class' => 'mt-1'])
                <div class="flex flex-wrap gap-1 mt-2">
                    @foreach($destination->categories as $cat)
                        <span class="text-xs px-2 py-0.5 rounded-full bg-brand-50 text-brand-600">{{ $cat->name }}</span>
                    @endforeach
                </div>
            </div>
        </a>
        @empty
        <p class="col-span-3 text-center text-gray-400 py-12">{{ __('destinations.empty') }}</p>
        @endforelse
    </div>

    {{ $destinations->links() }}
</div>
@endsection
