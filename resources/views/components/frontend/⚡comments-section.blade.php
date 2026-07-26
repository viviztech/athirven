<?php

use App\Enums\CommentStatus;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    public Article $article;

    public string $authorName = '';

    public string $body = '';

    public bool $submitted = false;

    protected function rules(): array
    {
        return [
            'authorName' => ['required', 'string', 'max:100'],
            'body' => ['required', 'string', 'max:2000'],
        ];
    }

    #[Computed]
    public function approvedComments()
    {
        return $this->article->comments()
            ->where('status', CommentStatus::Approved)
            ->oldest()
            ->get();
    }

    public function submit(): void
    {
        $this->validate();

        $rateLimitKey = 'comment-submit:'.request()->ip();

        if (RateLimiter::tooManyAttempts($rateLimitKey, 5)) {
            $this->addError('body', 'அதிக முயற்சிகள். சிறிது நேரம் கழித்து மீண்டும் முயற்சிக்கவும்.');

            return;
        }

        RateLimiter::hit($rateLimitKey, 300);

        // Pre-moderation is the default for political categories (see docs/architecture.md);
        // post-moderated articles publish the comment immediately instead.
        $status = $this->article->comment_moderation_mode === 'post'
            ? CommentStatus::Approved
            : CommentStatus::Pending;

        Comment::create([
            'article_id' => $this->article->id,
            'author_display_name' => $this->authorName,
            'body' => $this->body,
            'status' => $status,
            'ip_hash' => hash('sha256', request()->ip().config('app.key')),
        ]);

        $this->reset(['authorName', 'body']);
        $this->submitted = true;
        unset($this->approvedComments);
    }
};
?>

<div>
    <h2 class="font-headline text-xl font-bold text-ink">கருத்துகள் ({{ $this->approvedComments->count() }})</h2>

    <div class="mt-6 space-y-6">
        @forelse ($this->approvedComments as $comment)
            <div class="border-b border-hairline pb-4">
                <p class="font-meta text-xs tracking-wider text-ink uppercase">{{ $comment->author_display_name }}</p>
                <p class="mt-2 text-ink">{{ $comment->body }}</p>
                <p class="mt-2 font-meta text-[11px] text-slate">{{ $comment->created_at->diffForHumans() }}</p>
            </div>
        @empty
            <p class="text-slate">இதுவரை கருத்துகள் இல்லை.</p>
        @endforelse
    </div>

    <div class="mt-8 border border-hairline p-5">
        @if ($submitted)
            <p class="font-meta text-sm text-ambedkar">
                உங்கள் கருத்து சமர்ப்பிக்கப்பட்டது. மதிப்பாய்வுக்குப் பிறகு வெளியிடப்படும்.
            </p>
        @else
            <form wire:submit="submit" class="space-y-3">
                <div>
                    <input
                        type="text"
                        wire:model="authorName"
                        placeholder="உங்கள் பெயர் (புனைப்பெயராகவும் இருக்கலாம்)"
                        class="w-full border border-hairline bg-paper-raised px-3 py-2 text-ink placeholder:text-slate focus:border-ambedkar focus:outline-none"
                    >
                    @error('authorName') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <textarea
                        wire:model="body"
                        rows="3"
                        placeholder="உங்கள் கருத்து..."
                        class="w-full border border-hairline bg-paper-raised px-3 py-2 text-ink placeholder:text-slate focus:border-ambedkar focus:outline-none"
                    ></textarea>
                    @error('body') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <button type="submit" class="bg-ambedkar px-4 py-2 font-meta text-xs tracking-wider text-white uppercase hover:bg-ambedkar-ink">
                    சமர்ப்பிக்கவும்
                </button>
            </form>
        @endif
    </div>
</div>
