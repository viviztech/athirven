<?php

use App\Models\Article;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    public string $query = '';

    #[Computed]
    public function results()
    {
        if (mb_strlen(trim($this->query)) < 2) {
            return collect();
        }

        $like = '%'.$this->query.'%';

        return Article::published()
            ->where(fn ($q) => $q->where('title', 'like', $like)
                ->orWhere('excerpt', 'like', $like)
                ->orWhere('body', 'like', $like))
            ->with('authors', 'category')
            ->latest('published_at')
            ->limit(20)
            ->get();
    }
};
?>

<div>
    <input
        type="search"
        wire:model.live.debounce.400ms="query"
        placeholder="தேடல்..."
        autofocus
        class="w-full border border-hairline bg-paper-raised px-4 py-3 text-ink placeholder:text-slate focus:border-ambedkar focus:outline-none"
    >

    <div class="mt-10 grid gap-x-8 gap-y-12 sm:grid-cols-2">
        @if (mb_strlen(trim($query)) >= 2)
            @forelse ($this->results as $article)
                <x-frontend.article-card :article="$article" />
            @empty
                <p class="text-slate">"{{ $query }}" க்கு பொருந்தும் கட்டுரைகள் இல்லை.</p>
            @endforelse
        @else
            <p class="text-slate">தேட குறைந்தது 2 எழுத்துகள் தட்டச்சு செய்யவும்.</p>
        @endif
    </div>
</div>
