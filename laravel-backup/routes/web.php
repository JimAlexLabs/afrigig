<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\JobController;
use App\Http\Controllers\User\BidController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\MessageController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('terms', function () {
    return view('legal.terms');
})->name('terms');

Route::get('policy', function () {
    return view('legal.policy');
})->name('policy');

// Social Login Routes (Temporarily Disabled)
Route::controller(SocialAuthController::class)->group(function () {
    Route::get('auth/{provider}', 'redirect')->name('social.login');
    Route::get('auth/{provider}/callback', 'callback');
});

// Public Job Listing
Route::get('/jobs', [JobController::class, 'public'])->name('jobs.public');
Route::get('/jobs/{job}', [JobController::class, 'show'])->name('jobs.show');

// Authenticated Routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Jobs Routes
    Route::prefix('jobs')->name('jobs.')->group(function () {
        Route::get('/browse', [JobController::class, 'browse'])->name('browse');
        Route::get('/recommended', [JobController::class, 'recommended'])->name('recommended');
        Route::get('/my-jobs', [JobController::class, 'myJobs'])->name('my-jobs');
        Route::get('/create', [JobController::class, 'create'])->name('create');
        Route::post('/', [JobController::class, 'store'])->name('store');
        Route::get('/{job}/edit', [JobController::class, 'edit'])->name('edit');
        Route::put('/{job}', [JobController::class, 'update'])->name('update');
        Route::delete('/{job}', [JobController::class, 'destroy'])->name('destroy');
        Route::post('/{job}/bid', [JobController::class, 'submitBid'])->name('bid');
        Route::get('/{job}/bids', [JobController::class, 'bids'])->name('bids');
        Route::post('/{job}/bids/{bid}/accept', [JobController::class, 'acceptBid'])->name('accept-bid');
    });

    // Bid Routes
    Route::prefix('bids')->name('bids.')->group(function () {
        Route::get('/', [BidController::class, 'index'])->name('index');
        Route::get('/my', [BidController::class, 'myBids'])->name('my');
        Route::post('/{bid}/withdraw', [BidController::class, 'withdraw'])->name('withdraw');
        Route::post('/{bid}/accept', [BidController::class, 'accept'])->name('accept');
    });

    // Message Routes
    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/', [MessageController::class, 'index'])->name('index');
        Route::get('/job/{job}', [MessageController::class, 'jobMessages'])->name('job');
        Route::post('/', [MessageController::class, 'store'])->name('store');
        Route::get('/{message}', [MessageController::class, 'show'])->name('show');
        Route::post('/{message}/read', [MessageController::class, 'markAsRead'])->name('read');
    });

    // Cart Routes
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/add/{job}', [CartController::class, 'add'])->name('add');
        Route::put('/{item}', [CartController::class, 'update'])->name('update');
        Route::delete('/{item}', [CartController::class, 'remove'])->name('remove');
        Route::post('/checkout', [CartController::class, 'checkout'])->name('checkout');
    });

    // Community Routes
    Route::get('/community', [CommunityController::class, 'index'])->name('community.index');
    Route::get('/community/discussions', [CommunityController::class, 'discussions'])->name('community.discussions');
    Route::get('/community/events', [CommunityController::class, 'events'])->name('community.events');

    // Analytics Routes
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/analytics/earnings', [AnalyticsController::class, 'earnings'])->name('analytics.earnings');
    Route::get('/analytics/performance', [AnalyticsController::class, 'performance'])->name('analytics.performance');

    // Settings Routes
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/notifications', [SettingsController::class, 'notifications'])->name('notifications');
        Route::put('/notifications', [SettingsController::class, 'updateNotifications'])->name('notifications.update');
        Route::get('/security', [SettingsController::class, 'security'])->name('security');
        Route::put('/security', [SettingsController::class, 'updateSecurity'])->name('security.update');
    });
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('users', [AdminController::class, 'users'])->name('users');
    Route::get('jobs', [AdminController::class, 'jobs'])->name('jobs');
    Route::get('bids', [AdminController::class, 'bids'])->name('bids');
    Route::get('categories', [AdminController::class, 'categories'])->name('categories');
    Route::get('skills', [AdminController::class, 'skills'])->name('skills');
});

require __DIR__.'/auth.php';
