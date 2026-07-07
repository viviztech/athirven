<?php

use App\Http\Controllers\Frontend\ArticleController;
use App\Http\Controllers\Frontend\AuthorController;
use App\Http\Controllers\Frontend\CategoryController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\IssueController;
use App\Http\Controllers\Frontend\SearchController;
use App\Http\Controllers\Frontend\TagController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/issues', [IssueController::class, 'index'])->name('issues.index');
Route::get('/issues/{issue}', [IssueController::class, 'show'])->name('issues.show');

Route::get('/articles/{article}', [ArticleController::class, 'show'])->name('articles.show');

Route::get('/authors/{author}', [AuthorController::class, 'show'])->name('authors.show');

Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

Route::get('/tags/{tag}', [TagController::class, 'show'])->name('tags.show');

Route::get('/search', [SearchController::class, 'index'])->name('search');
