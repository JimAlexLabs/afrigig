<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'freelancer_id',
        'client_id',
        'job_id',
        'rating',
        'feedback',
        'private_feedback',
        'communication_rating',
        'quality_rating',
        'expertise_rating',
        'would_hire_again',
        'is_public'
    ];

    protected $casts = [
        'rating' => 'float',
        'communication_rating' => 'integer',
        'quality_rating' => 'integer',
        'expertise_rating' => 'integer',
        'would_hire_again' => 'boolean',
        'is_public' => 'boolean'
    ];

    public function freelancer()
    {
        return $this->belongsTo(User::class, 'freelancer_id');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function job()
    {
        return $this->belongsTo(Job::class);
    }
} 