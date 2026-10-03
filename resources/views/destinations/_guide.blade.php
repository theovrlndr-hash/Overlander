{{-- Best time / what to bring / what we provide / safety for one destination. Renders nothing for the parts that are empty. --}}
@php
    $bestMonths = $destination->bestMonthNumbers();
    $bestLines = \App\Models\Destination::lines($destination->best_time);
    $packingLines = \App\Models\Destination::lines($destination->packing);
    $providedLines = \App\Models\Destination::lines($destination->provided);
    $safetyLines = \App\Models\Destination::lines($destination->safety);
@endphp

<div class="space-y-4">
    @if($bestMonths || $bestLines)
    <div class="rounded-2xl border border-gray-200 bg-white p-5">
        <h3 class="text-base font-bold text-gray-900 mb-3">🗓️ {{ __('guide.best_time_title') }}</h3>
        @if($bestMonths)
        <div class="grid grid-cols-6 sm:grid-cols-12 gap-1 mb-1" aria-label="{{ __('guide.best_months_legend') }}">
            @for($m = 1; $m <= 12; $m++)
                <span class="text-center text-[11px] font-medium rounded-md py-1.5 {{ in_array($m, $bestMonths) ? 'bg-brand-500 text-white' : 'bg-gray-100 text-gray-400' }}">
                    {{ \Illuminate\Support\Carbon::create(2026, $m, 1)->locale(app()->getLocale())->isoFormat('MMM') }}
                </span>
            @endfor
        </div>
        <p class="text-[11px] text-gray-400 mb-3 flex items-center gap-1.5"><span class="inline-block w-2.5 h-2.5 rounded bg-brand-500"></span>{{ __('guide.best_months_legend') }}</p>
        @endif
        @if($bestLines)
        <ul class="space-y-1.5 text-sm text-gray-700 leading-relaxed">
            @foreach($bestLines as $line)
                <li class="flex gap-2"><span class="text-brand-500 shrink-0">•</span><span>{{ $line }}</span></li>
            @endforeach
        </ul>
        @endif
    </div>
    @endif

    @if($packingLines || $providedLines)
    <div class="grid sm:grid-cols-2 gap-4">
        @if($packingLines)
        <div class="rounded-2xl border border-gray-200 bg-white p-5 {{ $providedLines ? '' : 'sm:col-span-2' }}">
            <h3 class="text-base font-bold text-gray-900 mb-3">🎒 {{ __('guide.packing_title') }}</h3>
            <ul class="space-y-1.5 text-sm text-gray-700 leading-relaxed">
                @foreach($packingLines as $line)
                    <li class="flex gap-2"><span class="text-brand-500 shrink-0">✓</span><span>{{ $line }}</span></li>
                @endforeach
            </ul>
        </div>
        @endif
        @if($providedLines)
        <div class="rounded-2xl border border-green-200 bg-green-50/60 p-5">
            <h3 class="text-base font-bold text-gray-900 mb-1">🤝 {{ __('guide.provided_title') }}</h3>
            <p class="text-xs text-gray-500 mb-3">{{ __('guide.provided_hint') }}</p>
            <ul class="space-y-1.5 text-sm text-gray-700 leading-relaxed">
                @foreach($providedLines as $line)
                    <li class="flex gap-2"><span class="text-green-600 shrink-0">✓</span><span>{{ $line }}</span></li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
    @endif

    @if($safetyLines)
    <div class="rounded-2xl border border-amber-200 bg-amber-50/60 p-5">
        <h3 class="text-base font-bold text-gray-900 mb-3">⚠️ {{ __('guide.safety_title') }}</h3>
        <ul class="space-y-1.5 text-sm text-gray-700 leading-relaxed">
            @foreach($safetyLines as $line)
                <li class="flex gap-2"><span class="text-amber-600 shrink-0">!</span><span>{{ $line }}</span></li>
            @endforeach
        </ul>
    </div>
    @endif
</div>
