@props(['videoId', 'caption' => null])

@if ($videoId)
    <div>
        <div class="relative aspect-video overflow-hidden bg-hairline">
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
            <p class="mt-2 font-meta text-xs text-slate">{{ $caption }}</p>
        @endif
    </div>
@endif
