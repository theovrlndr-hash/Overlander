@extends('layouts.app')

@section('title', $article->title . ' — Overlander')
@section('meta_description', str(strip_tags($article->excerpt ?? ''))->limit(160))
@section('og_image', $article->cover_photo_url)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <a href="{{ route('articles.index') }}" class="text-sm text-gray-500 hover:text-brand-500">{{ __('articles.back_to_blog') }}</a>

    @if($article->category)
        <span class="text-xs px-2 py-0.5 rounded-full bg-brand-50 text-brand-600">{{ $article->category->name }}</span>
    @endif
    <h1 class="text-3xl font-black text-gray-900">{{ $article->title }}</h1>
    <p class="text-sm text-gray-400">{{ $article->published_at?->format('d M Y') }} @if($article->author) · {{ __('articles.by') }} {{ $article->author->name }} @endif</p>

    @include('_share', ['title' => $article->title . ' — Overlander'])

    <img src="{{ $article->cover_photo_url }}" class="w-full h-72 object-cover rounded-2xl" alt="{{ $article->title }}">

    <div class="prose max-w-none text-gray-700 leading-relaxed whitespace-pre-line">{{ $article->content }}</div>

    @if($related->isNotEmpty())
    <div class="border-t border-gray-100 pt-8">
        <h2 class="text-lg font-bold text-gray-900 mb-4">{{ __('articles.more_articles') }}</h2>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            @foreach($related as $r)
            <a href="{{ route('articles.show', $r) }}" class="block rounded-xl overflow-hidden border border-gray-100 provider-card">
                <img src="{{ $r->cover_photo_url }}" class="w-full h-28 object-cover" alt="">
                <p class="p-3 text-sm font-medium text-gray-800 line-clamp-2">{{ $r->title }}</p>
            </a>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
