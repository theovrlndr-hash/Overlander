@extends('layouts.app')

@section('title', __('reviews.title') . ' — Overlander')

@section('content')
<div class="space-y-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">{{ __('reviews.title') }}</h1>
        <p class="text-gray-500 mt-1">{{ __('reviews.subtitle') }}</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        @forelse($reviews as $review)
        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-full bg-brand-100 text-brand-600 flex items-center justify-center font-bold shrink-0">
                    {{ strtoupper(substr($review->reviewer_name, 0, 1)) }}
                </div>
                <div>
                    <p class="font-medium text-sm text-gray-800">{{ $review->reviewer_name }}</p>
                    <p class="text-yellow-500 text-xs">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</p>
                </div>
            </div>
            <p class="review-comment text-gray-600 text-sm leading-relaxed mb-1 line-clamp-3">"{{ $review->comment }}"</p>
            <button type="button" class="review-toggle text-xs text-brand-500 hover:underline mb-3"
                    data-label-more="{{ __('reviews.read_more') }}" data-label-less="{{ __('reviews.read_less') }}"
                    onclick="const p=this.previousElementSibling; const clamped=p.classList.toggle('line-clamp-3'); this.textContent = clamped ? this.dataset.labelMore : this.dataset.labelLess;">
                {{ __('reviews.read_more') }}
            </button>
            @include('_review-photos', ['review' => $review])
            @if($review->destination)
                <a href="{{ route('destinations.show', $review->destination) }}" class="inline-block text-xs px-2 py-0.5 rounded-full bg-brand-50 text-brand-600 hover:bg-brand-100">{{ $review->destination->name }}</a>
            @else
                <span class="inline-block text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-500">{{ __('reviews.general_trip') }}</span>
            @endif
        </div>
        @empty
        <p class="col-span-3 text-center text-gray-400 py-12">{{ __('reviews.empty') }}</p>
        @endforelse
    </div>

    {{ $reviews->links() }}
</div>
@endsection
