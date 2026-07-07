<x-frontend.layout title="பிரிவுகள்">
    <h1 class="text-3xl font-semibold">பிரிவுகள்</h1>

    <div class="mt-8 space-y-6">
        @foreach ($categories as $category)
            <div>
                <a href="{{ route('categories.show', $category) }}" class="text-lg font-semibold hover:underline">
                    {{ $category->name_ta }}
                </a>
                @if ($category->children->isNotEmpty())
                    <div class="mt-2 flex flex-wrap gap-2">
                        @foreach ($category->children as $child)
                            <a
                                href="{{ route('categories.show', $child) }}"
                                class="rounded-full border border-gray-300 px-3 py-1 text-sm text-gray-600 hover:border-gray-500 dark:border-gray-700 dark:text-gray-400 dark:hover:border-gray-500"
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
