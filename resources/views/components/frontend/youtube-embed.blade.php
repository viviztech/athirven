@props(['videoId', 'caption' => null])

@if ($videoId)
    <div class="my-4">
        <div class="relative aspect-video overflow-hidden rounded-lg">
            <iframe
                class="absolute inset-0 h-full w-full"
                src="https://www.youtube-nocookie.com/embed/{{ $videoId }}"
                title="{{ $caption ?? 'YouTube video' }}"
                loading="lazy"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen
            ></iframe>
        </div>
        @if ($caption)
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $caption }}</p>
        @endif
    </div>
@endif
