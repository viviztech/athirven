<?php

namespace Tests\Feature;

use App\Enums\ArticleAuthorRole;
use App\Filament\Resources\Articles\Pages\CreateArticle;
use App\Filament\Resources\Articles\Pages\EditArticle;
use App\Models\Article;
use App\Models\Author;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ArticleAuthorRepeaterFixTest extends TestCase
{
    use RefreshDatabase;

    public function test_attaches_an_existing_author_instead_of_crashing_on_create(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        $user = User::factory()->create();
        $user->givePermissionTo(['articles.create', 'articles.edit.own']);
        $this->actingAs($user);

        $author = Author::create(['pen_name' => 'Test Author']);

        Livewire::test(CreateArticle::class)
            ->fillForm([
                'title' => 'Repeater fix test',
                'type' => 'opinion',
                'body' => '<p>Body</p>',
                'authors' => [
                    [
                        'author_id' => $author->id,
                        'role' => ArticleAuthorRole::Author->value,
                        'sort_order' => 0,
                    ],
                ],
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $article = Article::where('title', 'Repeater fix test')->firstOrFail();

        $this->assertSame(1, Author::count());
        $this->assertSame(1, $article->authors()->count());
        $this->assertSame($author->id, $article->authors->first()->id);
        $this->assertSame('author', $article->authors->first()->pivot->role);
    }

    public function test_keeps_the_correct_author_attached_through_an_edit(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        $user = User::factory()->create();
        $user->givePermissionTo(['articles.create', 'articles.edit.own']);
        $this->actingAs($user);

        $author = Author::create(['pen_name' => 'Edit Author']);
        $article = Article::create([
            'title' => 'Edit repeater test',
            'type' => 'opinion',
            'status' => 'draft',
            'body' => '<p>Body</p>',
            'created_by_id' => $user->id,
        ]);
        $article->authors()->attach($author->id, ['role' => 'author', 'sort_order' => 0]);

        Livewire::test(EditArticle::class, ['record' => $article->getRouteKey()])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame(1, Author::count());
        $this->assertSame(1, $article->fresh()->authors()->count());
    }
}
