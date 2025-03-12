<?php

namespace App\Models;

use CodeIgniter\Model;

class Message extends Model
{
    protected $table = 'messages';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'sender_id',
        'receiver_id',
        'job_id',
        'bid_id',
        'subject',
        'content',
        'attachments',
        'read_at',
        'parent_id',
        'is_system_message'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'sender_id' => 'required|integer',
        'receiver_id' => 'required|integer',
        'job_id' => 'permit_empty|integer',
        'bid_id' => 'permit_empty|integer',
        'subject' => 'required|min_length[3]|max_length[255]',
        'content' => 'required|min_length[1]',
        'parent_id' => 'permit_empty|integer',
        'is_system_message' => 'required|in_list[0,1]'
    ];

    protected $validationMessages = [
        'subject' => [
            'required' => 'Message subject is required',
            'min_length' => 'Subject must be at least 3 characters long',
            'max_length' => 'Subject cannot exceed 255 characters'
        ],
        'content' => [
            'required' => 'Message content is required',
            'min_length' => 'Content cannot be empty'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Relationships
    public function sender()
    {
        return $this->belongsTo('App\Models\User', 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo('App\Models\User', 'receiver_id');
    }

    public function job()
    {
        return $this->belongsTo('App\Models\Job');
    }

    public function bid()
    {
        return $this->belongsTo('App\Models\Bid');
    }

    public function parent()
    {
        return $this->belongsTo('App\Models\Message', 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany('App\Models\Message', 'parent_id');
    }

    // Query Methods
    public function getUserMessages(int $userId, array $filters = [])
    {
        $builder = $this->builder();
        $builder->where('receiver_id', $userId);

        if (isset($filters['read'])) {
            if ($filters['read']) {
                $builder->whereNotNull('read_at');
            } else {
                $builder->whereNull('read_at');
            }
        }

        if (isset($filters['job_id'])) {
            $builder->where('job_id', $filters['job_id']);
        }

        if (isset($filters['bid_id'])) {
            $builder->where('bid_id', $filters['bid_id']);
        }

        if (isset($filters['sender_id'])) {
            $builder->where('sender_id', $filters['sender_id']);
        }

        return $builder->orderBy('created_at', 'DESC')
                      ->get()
                      ->getResultArray();
    }

    public function getUnreadCount(int $userId)
    {
        return $this->where('receiver_id', $userId)
                   ->whereNull('read_at')
                   ->countAllResults();
    }

    public function getConversation(int $userId1, int $userId2, int $limit = 50)
    {
        return $this->builder()
                   ->where('(sender_id = ' . $userId1 . ' AND receiver_id = ' . $userId2 . ') OR ' .
                          '(sender_id = ' . $userId2 . ' AND receiver_id = ' . $userId1 . ')')
                   ->orderBy('created_at', 'DESC')
                   ->limit($limit)
                   ->get()
                   ->getResultArray();
    }

    public function getJobMessages(int $jobId)
    {
        return $this->where('job_id', $jobId)
                   ->orderBy('created_at', 'ASC')
                   ->findAll();
    }

    // Helper Methods
    public function markAsRead()
    {
        if (is_null($this->read_at)) {
            return $this->update($this->id, [
                'read_at' => date('Y-m-d H:i:s')
            ]);
        }
        return true;
    }

    public function markAsUnread()
    {
        return $this->update($this->id, [
            'read_at' => null
        ]);
    }

    public function isRead(): bool
    {
        return !is_null($this->read_at);
    }

    public function isUnread(): bool
    {
        return is_null($this->read_at);
    }

    public function isSystemMessage(): bool
    {
        return (bool) $this->is_system_message;
    }

    public function hasAttachments(): bool
    {
        return !empty($this->attachments);
    }

    public function hasParent(): bool
    {
        return !is_null($this->parent_id);
    }

    public function hasReplies(): bool
    {
        return $this->replies()->countAllResults() > 0;
    }
}