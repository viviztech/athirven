@props(['src', 'caption' => null])

<div>
    <audio controls preload="none" class="w-full">
        <source src="{{ $src }}">
    </audio>
    @if ($caption)
        <p class="mt-2 font-meta text-xs text-slate">{{ $caption }}</p>
    @endif
</div>
