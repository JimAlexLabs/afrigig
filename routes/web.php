<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BidController;
use App\Http\Controllers\SkillAssessmentController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SocialAuthController;

// Terms and Conditions - Moved to top level
Route::get('terms', function () {
    return view('auth.terms');
})->name('terms');

Route::get('/', function () {
    return view('welcome');
});

// Available Jobs - Public Route
Route::get('/available-jobs', [JobController::class, 'available'])->name('jobs.available');

Route::get('/test-pixabay', function () {
    $pixabay = new \App\Services\PixabayService();
    
    // Test configuration
    $config = [
        'api_key_configured' => !empty(config('services.pixabay.key')),
        'api_key_length' => strlen(config('services.pixabay.key')),
        'api_key_preview' => substr(config('services.pixabay.key'), 0, 5) . '...',
    ];
    
    // Test different image types
    $tests = [
        'hero' => $pixabay->getHeroImage(),
        'skill_assessment' => $pixabay->getSkillAssessmentImage(),
        'job_listing' => $pixabay->getJobListingImage(),
        'profile' => $pixabay->getProfileImage(),
        'admin_dashboard' => $pixabay->getAdminDashboardImage(),
    ];
    
    // Get any errors
    $lastError = $pixabay->getLastError();
    
    return view('test-pixabay', compact('config', 'tests', 'lastError'));
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Job routes - now read-only for regular users
    Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
    Route::get('/jobs/{job}', [JobController::class, 'show'])->name('jobs.show');
    Route::post('/jobs/{job}/bid', [JobController::class, 'submitBid'])->name('jobs.bid');

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
        Route::prefix('jobs')->name('jobs.')->group(function () {
            Route::get('/', [AdminController::class, 'jobs'])->name('index');
            Route::get('/create', [AdminController::class, 'createJob'])->name('create');
            Route::post('/', [AdminController::class, 'storeJob'])->name('store');
            Route::get('/{job}', [AdminController::class, 'showJob'])->name('show');
            Route::get('/{job}/edit', [AdminController::class, 'editJob'])->name('edit');
            Route::put('/{job}', [AdminController::class, 'updateJob'])->name('update');
            Route::delete('/{job}', [AdminController::class, 'deleteJob'])->name('delete');
        });
        
        // Payment management
        Route::get('/payments', [AdminController::class, 'payments'])->name('payments');
        Route::get('/payments/{payment}', [AdminController::class, 'showPayment'])->name('payments.show');
        Route::post('/payments/{payment}/process', [AdminController::class, 'processPayment'])->name('payments.process');
        Route::post('/payments/{payment}/refund', [AdminController::class, 'refundPayment'])->name('payments.refund');
        
        // Reports and analytics
        Route::get('/reports/earnings', [AdminController::class, 'earningsReport'])->name('reports.earnings');
        Route::get('/reports/users', [AdminController::class, 'usersReport'])->name('reports.users');
        Route::get('/reports/jobs', [AdminController::class, 'jobsReport'])->name('reports.jobs');

        // Skill Assessment Management
        Route::prefix('skill-assessments')->name('skill-assessments.')->group(function () {
            Route::get('/', [AdminController::class, 'skillAssessments'])->name('index');
            Route::get('/create', [AdminController::class, 'createSkillAssessment'])->name('create');
            Route::post('/', [AdminController::class, 'storeSkillAssessment'])->name('store');
            Route::get('/{assessment}/edit', [AdminController::class, 'editSkillAssessment'])->name('edit');
            Route::put('/{assessment}', [AdminController::class, 'updateSkillAssessment'])->name('update');
            Route::delete('/{assessment}', [AdminController::class, 'deleteSkillAssessment'])->name('delete');
            
            // Assessment Results and Feedback
            Route::get('/results', [AdminController::class, 'assessmentResults'])->name('results');
            Route::get('/pending-feedback', [AdminController::class, 'pendingFeedback'])->name('feedback');
            Route::get('/attempts/{attempt}', [AdminController::class, 'showAttempt'])->name('attempts.show');
            Route::get('/attempts/{attempt}/feedback', [AdminController::class, 'createFeedback'])->name('feedback.create');
            Route::post('/attempts/{attempt}/feedback', [AdminController::class, 'provideFeedback'])->name('feedback.store');
        });
    });

    // Skill Assessment Routes
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/skill-assessments', [SkillAssessmentController::class, 'index'])->name('skill-assessments.index');
        Route::get('/skill-assessments/{assessment}', [SkillAssessmentController::class, 'show'])->name('skill-assessments.show');
        Route::post('/skill-assessments/{assessment}/start', [SkillAssessmentController::class, 'start'])->name('skill-assessments.start');
        Route::get('/skill-assessments/attempt/{attempt}', [SkillAssessmentController::class, 'attempt'])->name('skill-assessments.attempt');
        Route::post('/skill-assessments/attempt/{attempt}/submit', [SkillAssessmentController::class, 'submit'])->name('skill-assessments.submit');
        Route::get('/skill-assessments/attempt/{attempt}/results', [SkillAssessmentController::class, 'results'])->name('skill-assessments.results');
        Route::get('/skill-assessments/history', [SkillAssessmentController::class, 'history'])->name('skill-assessments.history');
    });

    // Bid routes
    Route::post('/jobs/{job}/bids', [BidController::class, 'store'])->name('bids.store');
    Route::put('/bids/{bid}', [BidController::class, 'update'])->name('bids.update');
    Route::delete('/bids/{bid}', [BidController::class, 'destroy'])->name('bids.destroy');
    Route::patch('/bids/{bid}/accept', [BidController::class, 'accept'])->name('bids.accept');
    Route::patch('/bids/{bid}/reject', [BidController::class, 'reject'])->name('bids.reject');
});

// Social Login Routes
Route::get('auth/google', [SocialAuthController::class, 'googleRedirect'])->name('auth.google');
Route::get('auth/google/callback', [SocialAuthController::class, 'googleCallback']);

Route::get('auth/linkedin', [SocialAuthController::class, 'linkedinRedirect'])->name('auth.linkedin');
Route::get('auth/linkedin/callback', [SocialAuthController::class, 'linkedinCallback']);

require __DIR__.'/auth.php';
