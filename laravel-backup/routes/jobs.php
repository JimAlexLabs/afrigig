<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/jobs/api/browse', [JobController::class, 'browse'])->name('api.jobs.browse');
    Route::get('/jobs/api/recommended', [JobController::class, 'recommended'])->name('api.jobs.recommended');
    Route::get('/jobs/api/{job}', [JobController::class, 'show'])->name('api.jobs.show');
    Route::post('/jobs/api', [JobController::class, 'store'])->name('api.jobs.store');
    Route::put('/jobs/api/{job}', [JobController::class, 'update'])->name('api.jobs.update');
    Route::delete('/jobs/api/{job}', [JobController::class, 'destroy'])->name('api.jobs.destroy');
}); 