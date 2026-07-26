<?php

use App\Http\Controllers\Frontend\AccountController;
use App\Http\Controllers\Frontend\ArticleController;
use App\Http\Controllers\Frontend\Auth\LoginController;
use App\Http\Controllers\Frontend\Auth\RegisterController;
use App\Http\Controllers\Frontend\AuthorController;
use App\Http\Controllers\Frontend\CategoryController;
use App\Http\Controllers\Frontend\DonationController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\IssueController;
use App\Http\Controllers\Frontend\SearchController;
use App\Http\Controllers\Frontend\SubscriptionController;
use App\Http\Controllers\Frontend\TagController;
use App\Http\Controllers\Webhooks\RazorpayWebhookController;
use App\Http\Controllers\Webhooks\StripeWebhookController;
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

Route::get('/register', [RegisterController::class, 'create'])->name('register')->middleware('guest');
Route::post('/register', [RegisterController::class, 'store'])->middleware('guest');
Route::get('/login', [LoginController::class, 'create'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'store'])->middleware('guest');
Route::post('/logout', [LoginController::class, 'destroy'])->name('logout')->middleware('auth');

Route::get('/subscribe', [SubscriptionController::class, 'index'])->name('subscriptions.index');
Route::get('/subscribe/success', [SubscriptionController::class, 'success'])->name('subscriptions.success');
Route::get('/subscribe/cancelled', [SubscriptionController::class, 'cancelled'])->name('subscriptions.cancelled');
Route::get('/subscribe/{plan}', [SubscriptionController::class, 'show'])->name('subscriptions.show');
Route::post('/subscribe/{plan}', [SubscriptionController::class, 'checkout'])->name('subscriptions.checkout')->middleware('auth');

Route::get('/donate', [DonationController::class, 'index'])->name('donations.index');
Route::post('/donate', [DonationController::class, 'store'])->name('donations.store');
Route::get('/donate/success', [DonationController::class, 'success'])->name('donations.success');

Route::get('/account', [AccountController::class, 'index'])->name('account')->middleware('auth');
Route::post('/account/subscriptions/{subscription}/cancel', [AccountController::class, 'cancelSubscription'])
    ->name('account.subscriptions.cancel')
    ->middleware('auth');

Route::post('/webhooks/stripe', [StripeWebhookController::class, 'handleWebhook'])->name('webhooks.stripe');
Route::post('/webhooks/razorpay', RazorpayWebhookController::class)->name('webhooks.razorpay');
