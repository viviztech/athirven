<x-frontend.layout title="பிரிவுகள்">
    <h1 class="font-headline text-3xl font-bold text-ink">பிரிவுகள்</h1>

    <div class="mt-10 space-y-8">
        @foreach ($categories as $category)
            <div class="border-b border-hairline pb-8">
                <a href="{{ route('categories.show', $category) }}" class="font-headline text-lg font-bold text-ink hover:text-ambedkar">
                    {{ $category->name_ta }}
                </a>
                @if ($category->children->isNotEmpty())
                    <div class="mt-3 flex flex-wrap gap-2 font-meta text-xs uppercase">
                        @foreach ($category->children as $child)
                            <a
                                href="{{ route('categories.show', $child) }}"
                                class="border border-hairline px-3 py-1 text-slate hover:border-ambedkar hover:text-ambedkar"
                            >
                                {{ $child->name_ta }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</x-frontend.layout>
