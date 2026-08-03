@php
    $shareUrl = urlencode(url()->current());
    $shareTitle = urlencode($event->title);
@endphp

<div class="my-6 flex flex-wrap items-center gap-3 border-y border-gray-100 py-4">
    <span class="text-xs font-medium text-gray-400">Bagikan:</span>

    <a href="https://api.whatsapp.com/send?text={{ $shareTitle }}%20{{ $shareUrl }}" target="_blank"
        rel="noopener noreferrer"
        class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-3 py-1.5 text-xs text-green-700 transition hover:bg-green-100">
        <i class="fab fa-whatsapp" aria-hidden="true"></i> WhatsApp
    </a>

    <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank" rel="noopener noreferrer"
        class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-3 py-1.5 text-xs text-blue-700 transition hover:bg-blue-100">
        <i class="fab fa-facebook-f" aria-hidden="true"></i> Facebook
    </a>

    <a href="https://twitter.com/intent/tweet?text={{ $shareTitle }}&url={{ $shareUrl }}" target="_blank"
        rel="noopener noreferrer"
        class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-3 py-1.5 text-xs text-gray-700 transition hover:bg-gray-200">
        <i class="fab fa-twitter" aria-hidden="true"></i> X
    </a>

    <a href="https://t.me/share/url?url={{ $shareUrl }}&text={{ $shareTitle }}" target="_blank"
        rel="noopener noreferrer"
        class="inline-flex items-center gap-1.5 rounded-full bg-sky-50 px-3 py-1.5 text-xs text-sky-700 transition hover:bg-sky-100">
        <i class="fab fa-telegram-plane" aria-hidden="true"></i> Telegram
    </a>
</div>
