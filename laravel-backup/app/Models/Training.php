<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'category',
        'level', // 'beginner', 'intermediate', 'advanced'
        'instructor_id',
        'price',
        'duration_weeks',
        'curriculum',
        'requirements',
        'is_active',
    ];

    protected $casts = [
        'curriculum' => 'array',
        'requirements' => 'array',
        'price' => 'float',
        'duration_weeks' => 'integer',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withTimestamps()
            ->withPivot(['progress', 'completed']);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
} 