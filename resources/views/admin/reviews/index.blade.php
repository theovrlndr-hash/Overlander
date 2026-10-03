@extends('layouts.app')

@section('title', 'Admin — Reviews')

@section('content')
<div class="space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-gray-900">Admin Panel</h1>
        <p class="text-sm text-gray-500 mt-1">Manage Overlander data — Travel Agency</p>
    </div>

    @include('admin._nav')

    {{-- Filter --}}
    <form method="GET" action="{{ route('admin.reviews.index') }}"
          class="bg-white rounded-2xl border border-gray-200 p-4 flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-48">
            <label class="block text-xs font-medium text-gray-500 mb-1">Search reviewer name</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="e.g. Robert"
                   class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100 transition-colors">
        </div>
        <div class="min-w-36">
            <label class="block text-xs font-medium text-gray-500 mb-1">Rating</label>
            <select name="rating"
                    class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100 transition-colors">
                <option value="">All ratings</option>
                @for($i = 5; $i >= 1; $i--)
                    <option value="{{ $i }}" @selected(request('rating') == $i)>
                        {{ str_repeat('★', $i) }} ({{ $i }})
                    </option>
                @endfor
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit"
                    class="px-4 py-2 bg-brand-500 text-white text-sm font-medium rounded-xl hover:bg-brand-600 transition-colors">
                Search
            </button>
            @if(request()->hasAny(['search', 'rating']))
                <a href="{{ route('admin.reviews.index') }}"
                   class="px-4 py-2 border border-gray-300 text-gray-600 text-sm font-medium rounded-xl hover:bg-gray-50 transition-colors">
                    Reset
                </a>
            @endif
        </div>
    </form>

    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="font-semibold text-gray-800">
                Reviews
                <span class="text-gray-400 font-normal text-sm">({{ $reviews->total() }} total)</span>
            </h2>
        </div>

        <div class="divide-y divide-gray-100">
            @forelse($reviews as $review)
            <div class="px-6 py-4 flex items-start gap-4">
                <div class="w-9 h-9 rounded-full bg-brand-100 text-brand-600 flex items-center justify-center text-xs font-bold shrink-0">
                    {{ strtoupper(substr($review->reviewer_name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="font-medium text-gray-800 text-sm">{{ $review->reviewer_name }}</span>
                        @if($review->destination)
                            <span class="text-gray-400 text-xs">→</span>
                            <a href="{{ route('destinations.show', $review->destination) }}"
                               class="text-brand-500 hover:underline text-sm font-medium">
                                {{ $review->destination->name }}
                            </a>
                        @else
                            <span class="text-gray-400 text-xs italic">general testimonial</span>
                        @endif
                        <span class="text-yellow-500 text-xs font-medium tracking-wide">
                            {{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}
                        </span>
                        @if($review->booking_id)
                            <span class="text-xs px-1.5 py-0.5 rounded bg-green-100 text-green-700">Verified Booking</span>
                        @endif
                        <span class="text-xs text-gray-400">{{ $review->created_at->format('d M Y') }}</span>
                    </div>
                    @if($review->comment)
                        <p class="text-gray-600 text-sm mt-1">{{ $review->comment }}</p>
                    @else
                        <p class="text-gray-400 text-xs italic mt-1">No comment</p>
                    @endif
                    @include('_review-photos', ['review' => $review])
                </div>
                <div class="flex flex-col gap-1.5 shrink-0">
                    <form method="POST" action="{{ route('admin.reviews.toggle-featured', $review) }}">
                        @csrf @method('PATCH')
                        <button type="submit" data-no-loading
                                class="text-xs px-3 py-1 rounded-lg border transition-colors w-full
                                    {{ $review->is_featured ? 'border-brand-300 text-brand-600 bg-brand-50' : 'border-gray-200 text-gray-500 hover:bg-gray-50' }}">
                            {{ $review->is_featured ? '★ Featured' : 'Mark as Featured' }}
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}"
                          onsubmit="return confirm('Delete this review?')">
                        @csrf @method('DELETE')
                        <button type="submit" data-no-loading
                                class="text-xs px-3 py-1 rounded-lg border border-red-200 text-red-600 hover:bg-red-50 transition-colors w-full">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="px-6 py-12 text-center text-gray-400">No reviews found.</div>
            @endforelse
        </div>

        @if($reviews->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $reviews->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
