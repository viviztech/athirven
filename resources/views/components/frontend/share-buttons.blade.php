@props(['url', 'title'])

@php
    $whatsappUrl = 'https://wa.me/?text='.rawurlencode($title.' '.$url);
    $telegramUrl = 'https://t.me/share/url?url='.rawurlencode($url).'&text='.rawurlencode($title);
@endphp

<div class="flex flex-wrap gap-2 text-sm">
    <a
        href="{{ $whatsappUrl }}"
        target="_blank"
        rel="noopener noreferrer"
        class="rounded-full border border-gray-300 px-3 py-1 text-gray-600 hover:border-gray-500 dark:border-gray-700 dark:text-gray-400 dark:hover:border-gray-500"
    >
        WhatsApp-ல் பகிரவும்
    </a>
    <a
        href="{{ $telegramUrl }}"
        target="_blank"
        rel="noopener noreferrer"
        class="rounded-full border border-gray-300 px-3 py-1 text-gray-600 hover:border-gray-500 dark:border-gray-700 dark:text-gray-400 dark:hover:border-gray-500"
    >
        Telegram-ல் பகிரவும்
    </a>
</div>
