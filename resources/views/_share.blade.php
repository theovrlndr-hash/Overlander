{{-- Share buttons: WhatsApp, copy link and (where the browser supports it) the phone's share sheet. --}}
@php
    $shareUrl = $url ?? url()->current();
    $shareTitle = $title ?? 'Overlander';
    $waText = $shareTitle . ' — ' . $shareUrl;
@endphp
<div class="flex flex-wrap items-center gap-2 share-box" data-url="{{ $shareUrl }}" data-title="{{ $shareTitle }}"
     data-copied="{{ __('share.copied') }}" data-copy="{{ __('share.copy_link') }}">
    <span class="text-xs font-medium text-gray-500 mr-1">{{ __('share.label') }}</span>

    <a href="https://wa.me/?text={{ rawurlencode($waText) }}" target="_blank" rel="noopener"
       class="btn-pop inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-200 hover:bg-green-100 transition-colors">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2 22l5.25-1.38c1.45.79 3.08 1.21 4.79 1.21h.01c5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0012.04 2zm0 18.07h-.01c-1.5 0-2.97-.4-4.25-1.16l-.3-.18-3.12.82.83-3.04-.2-.31a8.2 8.2 0 01-1.26-4.38c0-4.54 3.7-8.24 8.25-8.24 2.2 0 4.27.86 5.83 2.42a8.18 8.18 0 012.41 5.83c0 4.55-3.7 8.24-8.24 8.24zm4.52-6.16c-.25-.12-1.47-.72-1.69-.81-.23-.08-.39-.12-.56.12-.17.25-.64.81-.78.97-.14.17-.29.19-.53.06-.25-.12-1.05-.39-2-1.23-.74-.66-1.24-1.47-1.38-1.72-.14-.25-.02-.38.11-.51.11-.11.25-.29.37-.43.12-.14.16-.25.25-.41.08-.17.04-.31-.02-.43-.06-.12-.56-1.35-.77-1.85-.2-.48-.41-.42-.56-.43-.14-.01-.31-.01-.48-.01a.92.92 0 00-.67.31c-.23.25-.87.85-.87 2.07 0 1.22.89 2.4 1.01 2.57.12.17 1.75 2.67 4.25 3.74.59.26 1.06.41 1.42.52.6.19 1.14.16 1.57.1.48-.07 1.47-.6 1.68-1.18.21-.58.21-1.08.14-1.18-.06-.11-.23-.17-.48-.29z"/></svg>
        WhatsApp
    </a>

    <button type="button" data-no-loading class="share-copy btn-pop inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium bg-gray-50 text-gray-700 border border-gray-200 hover:bg-gray-100 transition-colors">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 007.07 0l3-3a5 5 0 00-7.07-7.07l-1.5 1.5"/><path d="M14 11a5 5 0 00-7.07 0l-3 3a5 5 0 007.07 7.07l1.5-1.5"/></svg>
        <span class="share-copy-label">{{ __('share.copy_link') }}</span>
    </button>

    <button type="button" data-no-loading hidden class="share-native btn-pop inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium bg-brand-50 text-brand-600 border border-brand-200 hover:bg-brand-100 transition-colors">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><path d="M8.6 13.5l6.8 4M15.4 6.5l-6.8 4"/></svg>
        {{ __('share.more') }}
    </button>
</div>

@once
@push('scripts')
<script>
(function () {
    document.querySelectorAll('.share-box').forEach(function (box) {
        const url = box.dataset.url, title = box.dataset.title;
        const copyBtn = box.querySelector('.share-copy');
        const label = box.querySelector('.share-copy-label');
        const nativeBtn = box.querySelector('.share-native');

        copyBtn.addEventListener('click', async function () {
            let ok = false;
            try {
                await navigator.clipboard.writeText(url);
                ok = true;
            } catch (e) {
                // Older browsers / non-secure pages: fall back to a temporary textarea.
                const t = document.createElement('textarea');
                t.value = url; t.style.position = 'fixed'; t.style.opacity = '0';
                document.body.appendChild(t); t.select();
                try { ok = document.execCommand('copy'); } catch (e2) {}
                t.remove();
            }
            if (ok) {
                label.textContent = box.dataset.copied;
                setTimeout(function () { label.textContent = box.dataset.copy; }, 2000);
            }
        });

        if (navigator.share) {
            nativeBtn.hidden = false;
            nativeBtn.addEventListener('click', function () {
                navigator.share({ title: title, url: url }).catch(function () {});
            });
        }
    });
})();
</script>
@endpush
@endonce
