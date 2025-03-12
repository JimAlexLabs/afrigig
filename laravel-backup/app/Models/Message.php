<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'job_id',
        'subject',
        'content',
        'attachments',
        'read_at',
        'deleted_by_sender',
        'deleted_by_receiver',
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'attachments' => 'array',
        'deleted_by_sender' => 'boolean',
        'deleted_by_receiver' => 'boolean',
    ];

    /**
     * Get the sender of the message
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Get the recipient of the message
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    /**
     * Get the job associated with the message
     */
    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    /**
     * Mark the message as read
     */
    public function markAsRead(): void
    {
        if (!$this->read_at) {
            $this->update(['read_at' => now()]);
        }
    }

    /**
     * Check if the message is read
     */
    public function isRead(): bool
    {
        return $this->read_at !== null;
    }

    /**
     * Create a system message
     */
    public static function createSystemMessage(int $recipientId, string $subject, string $content, array $metadata = []): self
    {
        return static::create([
            'sender_id' => null,
            'recipient_id' => $recipientId,
            'subject' => $subject,
            'content' => $content,
            'type' => 'system',
            'metadata' => $metadata
        ]);
    }

    /**
     * Scope for unread messages
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope for messages for a specific user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where(function($q) use ($userId) {
            $q->where('sender_id', $userId)
              ->where('deleted_by_sender', false)
              ->orWhere('receiver_id', $userId)
              ->where('deleted_by_receiver', false);
        });
    }

    /**
     * Delete the message for a specific user
     */
    public function deleteForUser($userId)
    {
        if ($this->sender_id === $userId) {
            $this->update(['deleted_by_sender' => true]);
        } elseif ($this->receiver_id === $userId) {
            $this->update(['deleted_by_receiver' => true]);
        }

        // If both users have deleted the message, we can physically delete it
        if ($this->deleted_by_sender && $this->deleted_by_receiver) {
            $this->delete();
        }
    }
} 