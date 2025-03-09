<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Milestone extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'title',
        'description',
        'amount',
        'due_date',
        'status', // 'pending', 'completed', 'paid'
        'completion_proof',
    ];

    protected $casts = [
        'amount' => 'float',
        'due_date' => 'datetime',
    ];

    // Relationships
    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
} 