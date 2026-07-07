<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;

class ArticlePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('articles.create') || $user->can('articles.edit.any') || $user->can('articles.review');
    }

    public function view(User $user, Article $article): bool
    {
        return $this->update($user, $article);
    }

    public function create(User $user): bool
    {
        return $user->can('articles.create');
    }

    public function update(User $user, Article $article): bool
    {
        if ($user->can('articles.edit.any')) {
            return true;
        }

        return $user->can('articles.edit.own') && $article->created_by_id === $user->id;
    }

    public function delete(User $user, Article $article): bool
    {
        return $user->can('articles.delete');
    }
}
