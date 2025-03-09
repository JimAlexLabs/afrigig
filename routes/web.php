<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BidController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Job routes
    Route::resource('jobs', JobController::class);
    Route::post('/jobs/{job}/bid', [JobController::class, 'submitBid'])->name('jobs.bid');
    Route::post('/jobs/{job}/accept-bid/{bid}', [JobController::class, 'acceptBid'])->name('jobs.accept-bid');

    // Payment routes
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
    Route::post('/payments/process', [PaymentController::class, 'process'])->name('payments.process');

    // Admin routes
    Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // User management
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::get('/users/{user}', [AdminController::class, 'showUser'])->name('users.show');
        Route::post('/users/{user}/verify', [AdminController::class, 'verifyUser'])->name('users.verify');
        Route::post('/users/{user}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('users.toggle-status');
        
        // Job management
        Route::get('/jobs', [AdminController::class, 'jobs'])->name('jobs');
        Route::get('/jobs/{job}', [AdminController::class, 'showJob'])->name('jobs.show');
        Route::delete('/jobs/{job}', [AdminController::class, 'deleteJob'])->name('jobs.delete');
        
        // Payment management
        Route::get('/payments', [AdminController::class, 'payments'])->name('payments');
        Route::get('/payments/{payment}', [AdminController::class, 'showPayment'])->name('payments.show');
        Route::post('/payments/{payment}/process', [AdminController::class, 'processPayment'])->name('payments.process');
        Route::post('/payments/{payment}/refund', [AdminController::class, 'refundPayment'])->name('payments.refund');
        
        // Reports and analytics
        Route::get('/reports/earnings', [AdminController::class, 'earningsReport'])->name('reports.earnings');
        Route::get('/reports/users', [AdminController::class, 'usersReport'])->name('reports.users');
        Route::get('/reports/jobs', [AdminController::class, 'jobsReport'])->name('reports.jobs');
    });

    // Bid routes
    Route::post('/jobs/{job}/bids', [BidController::class, 'store'])->name('bids.store');
    Route::put('/bids/{bid}', [BidController::class, 'update'])->name('bids.update');
    Route::delete('/bids/{bid}', [BidController::class, 'destroy'])->name('bids.destroy');
    Route::patch('/bids/{bid}/accept', [BidController::class, 'accept'])->name('bids.accept');
    Route::patch('/bids/{bid}/reject', [BidController::class, 'reject'])->name('bids.reject');
});

require __DIR__.'/auth.php';
