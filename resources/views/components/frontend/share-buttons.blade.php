@props(['url', 'title'])

@php
    $whatsappUrl = 'https://wa.me/?text='.rawurlencode($title.' '.$url);
    $telegramUrl = 'https://t.me/share/url?url='.rawurlencode($url).'&text='.rawurlencode($title);
@endphp

<div class="flex flex-wrap items-center gap-4 font-meta text-xs tracking-wider uppercase">
    <span class="text-slate">பகிரவும்</span>
    <a
        href="{{ $whatsappUrl }}"
        target="_blank"
        rel="noopener noreferrer"
        class="border border-hairline px-3 py-1.5 text-ink hover:border-ambedkar hover:text-ambedkar"
    >
        WhatsApp
    </a>
    <a
        href="{{ $telegramUrl }}"
        target="_blank"
        rel="noopener noreferrer"
        class="border border-hairline px-3 py-1.5 text-ink hover:border-ambedkar hover:text-ambedkar"
    >
        Telegram
    </a>
</div>
