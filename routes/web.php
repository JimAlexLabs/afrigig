<?php

use App\Http\Controllers\JobController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HealthController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/health', [HealthController::class, 'check']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Job routes
    Route::resource('jobs', JobController::class);
    Route::post('/jobs/{job}/bids', [JobController::class, 'submitBid'])->name('jobs.bids.store');
    Route::post('/jobs/{job}/bids/{bid}/accept', [JobController::class, 'acceptBid'])->name('jobs.bids.accept');

    // Payment routes
    Route::get('/payments/{milestone}', [PaymentController::class, 'show'])->name('payments.show');
    Route::post('/payments/{milestone}/mpesa', [PaymentController::class, 'processMpesa'])->name('payments.mpesa.process');
    Route::post('/payments/{milestone}/paypal', [PaymentController::class, 'processPaypal'])->name('payments.paypal.process');
    Route::post('/payments/mpesa/callback', [PaymentController::class, 'mpesaCallback'])->name('payments.mpesa.callback');
    Route::get('/payments/paypal/success', [PaymentController::class, 'paypalSuccess'])->name('payments.paypal.success');
    Route::get('/payments/paypal/cancel', [PaymentController::class, 'paypalCancel'])->name('payments.paypal.cancel');
    Route::get('/payments/{payment}/status', [PaymentController::class, 'checkStatus'])->name('payments.status');

    // Admin routes
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::get('/jobs', [AdminController::class, 'jobs'])->name('jobs');
        Route::get('/payments', [AdminController::class, 'payments'])->name('payments');
        Route::post('/users/{user}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('users.toggle-status');
        Route::post('/users/{user}/verify', [AdminController::class, 'verifyUser'])->name('users.verify');
    });
});

require __DIR__.'/auth.php'; 