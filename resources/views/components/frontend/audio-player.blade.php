@props(['src', 'caption' => null])

<div class="my-4">
    <audio controls preload="none" class="w-full">
        <source src="{{ $src }}">
    </audio>
    @if ($caption)
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $caption }}</p>
    @endif
</div>
