<?php

namespace Database\Seeders;

use App\Enums\ArticleAuthorRole;
use App\Enums\ArticleStatus;
use App\Enums\ArticleType;
use App\Enums\IssueStatus;
use App\Enums\CommentStatus;
use App\Models\Article;
use App\Models\Author;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Issue;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoContentSeeder extends Seeder
{
    public function run(): void
    {
        if (Issue::query()->exists()) {
            $this->command->info('Demo content already present, skipping.');

            return;
        }

        $politics = Category::create(['name_ta' => 'தலித் அரசியல்', 'name_en' => 'Dalit Politics']);
        Category::create(['name_ta' => 'சாதி ஒழிப்பு', 'name_en' => 'Caste Annihilation', 'parent_id' => $politics->id]);
        $culture = Category::create(['name_ta' => 'பண்பாடு', 'name_en' => 'Culture']);
        Category::create(['name_ta' => 'இலக்கியம்', 'name_en' => 'Literature', 'parent_id' => $culture->id]);

        $tagNames = ['அரசியல்', 'கல்வி', 'இலக்கியம்', 'வரலாறு'];
        $tags = collect($tagNames)->map(fn ($name) => Tag::create(['name_ta' => $name]));

        $editor = Author::create([
            'pen_name' => 'செல்வம் இரா.',
            'bio' => 'ஆசிரியர் குழு உறுப்பினர், அதிர்வெண்.',
            'is_pseudonymous' => false,
        ]);

        $pseudonymous = Author::create([
            'pen_name' => 'நிலா',
            'real_name' => 'கள ஆய்வாளர் (பாதுகாக்கப்பட்ட பெயர்)',
            'bio' => 'களப்பணியில் ஈடுபட்டுள்ள எழுத்தாளர்; பாதுகாப்பு காரணமாக புனைப்பெயரில் எழுதுகிறார்.',
            'is_pseudonymous' => true,
            'contact_email' => 'contact+nila@athirven.test',
        ]);

        $now = now();
        $writerId = User::where('email', 'writer@athirven.test')->value('id');

        $issue = Issue::create([
            'issue_number' => 1,
            'title' => 'அதிர்வெண் – இதழ் 1',
            'month' => $now->month,
            'year' => $now->year,
            'publish_date' => $now->toDateString(),
            'status' => IssueStatus::Published,
            'is_premium' => false,
        ]);

        $article1 = Article::create([
            'issue_id' => $issue->id,
            'category_id' => $politics->id,
            'type' => ArticleType::Editorial,
            'status' => ArticleStatus::Published,
            'title' => 'அதிர்வெண் – ஓர் அறிமுகம்',
            'excerpt' => 'தலித் அரசியல் மற்றும் பண்பாட்டு விவாதங்களுக்கான புதிய தளமாக அதிர்வெண் தொடங்குகிறது.',
            'body' => "<p>அதிர்வெண் மாத இதழ் தலித் அரசியல் மற்றும் பண்பாட்டு விவாதங்களை ஆவணப்படுத்தும் நோக்கில் தொடங்கப்படுகிறது. எழுத்தாளர்கள், ஆய்வாளர்கள், கலைஞர்கள் என பலரின் குரல்களை ஒன்றிணைக்கும் இந்த தளம், மாதந்தோறும் கட்டுரைகள், நேர்காணல்கள், கவிதைகள், புத்தக விமர்சனங்கள் என பல்வேறு வடிவங்களில் வெளிவரும்.</p><p>இது ஒரு சோதனை/மாதிரி கட்டுரை.</p>",
            'is_premium' => false,
            'published_at' => $now,
            'order' => 1,
        ]);
        $article1->authors()->attach($editor->id, ['role' => ArticleAuthorRole::Author->value, 'sort_order' => 1]);
        $article1->tags()->attach($tags->firstWhere('name_ta', 'அரசியல்')->id);

        $article2 = Article::create([
            'issue_id' => $issue->id,
            'category_id' => $culture->id,
            'type' => ArticleType::Essay,
            'status' => ArticleStatus::Published,
            'title' => 'தமிழ் இலக்கியத்தில் விளிம்புநிலை குரல்கள்',
            'excerpt' => 'இலக்கியத்தின் வழியே கேட்கப்படாத குரல்களை பதிவு செய்யும் முயற்சி.',
            'body' => '<p>இது ஒரு மாதிரி கட்டுரை உள்ளடக்கம். உண்மையான கட்டுரை இங்கே இடம்பெறும்.</p>',
            'is_premium' => false,
            'published_at' => $now,
            'order' => 2,
        ]);
        $article2->authors()->attach($pseudonymous->id, ['role' => ArticleAuthorRole::Author->value, 'sort_order' => 1]);
        $article2->tags()->attach($tags->firstWhere('name_ta', 'இலக்கியம்')->id);

        $article3 = Article::create([
            'category_id' => $politics->id,
            'created_by_id' => $writerId,
            'type' => ArticleType::Interview,
            'status' => ArticleStatus::Draft,
            'title' => 'களப்பணி குறித்த ஒரு உரையாடல் (வரைவு)',
            'excerpt' => 'இது இன்னும் வெளியிடப்படாத வரைவு கட்டுரை — ஆசிரியர் பணிப்பாய்வை சோதிக்க பயன்படுத்தப்படுகிறது.',
            'body' => '<p>வரைவு நிலையில் உள்ள உள்ளடக்கம்.</p>',
            'is_premium' => true,
            'order' => 1,
        ]);
        $article3->authors()->attach($pseudonymous->id, ['role' => ArticleAuthorRole::Interviewee->value, 'sort_order' => 1]);

        $article4 = Article::create([
            'category_id' => $culture->id,
            'created_by_id' => $writerId,
            'type' => ArticleType::BookReview,
            'status' => ArticleStatus::Submitted,
            'title' => 'ஒரு புத்தக விமர்சனம் (மதிப்பாய்வுக்காக)',
            'excerpt' => 'மதிப்பாய்வு வரிசையை சோதிக்க சமர்ப்பிக்கப்பட்ட கட்டுரை.',
            'body' => '<p>மதிப்பாய்வுக்கு காத்திருக்கும் உள்ளடக்கம்.</p>',
            'submitted_at' => $now->copy()->subDays(2),
            'order' => 1,
        ]);
        $article4->authors()->attach($editor->id, ['role' => ArticleAuthorRole::Author->value, 'sort_order' => 1]);

        Comment::create([
            'article_id' => $article1->id,
            'author_display_name' => 'ஒரு வாசகர்',
            'body' => 'நல்ல முயற்சி, தொடர்ந்து வெளியிடுங்கள்.',
            'status' => CommentStatus::Pending,
            'ip_hash' => hash('sha256', '127.0.0.1'.config('app.key')),
        ]);

        $this->command->info('Demo content seeded: 1 issue, 4 articles, 2 authors, 4 categories, 4 tags, 1 comment.');
    }
}
