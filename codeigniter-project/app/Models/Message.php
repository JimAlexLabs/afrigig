<?php

namespace App\Models;

use CodeIgniter\Model;

class Message extends Model
{
    protected $table = 'messages';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'sender_id',
        'receiver_id',
        'job_id',
        'message',
        'read_at',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'sender_id' => 'required|integer|is_not_unique[users.id]',
        'receiver_id' => 'required|integer|is_not_unique[users.id]',
        'job_id' => 'permit_empty|integer|is_not_unique[jobs.id]',
        'message' => 'required|min_length[1]',
    ];

    protected $validationMessages = [
        'sender_id' => [
            'required' => 'Sender ID is required',
            'integer' => 'Sender ID must be an integer',
            'is_not_unique' => 'Sender does not exist',
        ],
        'receiver_id' => [
            'required' => 'Receiver ID is required',
            'integer' => 'Receiver ID must be an integer',
            'is_not_unique' => 'Receiver does not exist',
        ],
        'job_id' => [
            'integer' => 'Job ID must be an integer',
            'is_not_unique' => 'Job does not exist',
        ],
        'message' => [
            'required' => 'Message is required',
            'min_length' => 'Message cannot be empty',
        ],
    ];

    public function getConversation(array $userId1, array $userId2, ?array $jobId = null)
    {
        $this->select('messages.*, sender.name as sender_name, receiver.name as receiver_name')
            ->join('users as sender', 'sender.id = messages.sender_id')
            ->join('users as receiver', 'receiver.id = messages.receiver_id')
            ->where('(sender_id = ' . $userId1['id'] . ' AND receiver_id = ' . $userId2['id'] . ')')
            ->orWhere('(sender_id = ' . $userId2['id'] . ' AND receiver_id = ' . $userId1['id'] . ')');

        if ($jobId !== null) {
            $this->where('job_id', $jobId['id']);
        }

        return $this->orderBy('created_at', 'ASC')->findAll();
    }

    public function getUnreadCount(array $userId)
    {
        return $this->where('receiver_id', $userId['id'])
            ->where('read_at IS NULL')
            ->countAllResults();
    }

    public function markAsRead(array $messageId)
    {
        return $this->update($messageId['id'], [
            'read_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function markAllAsRead(array $userId)
    {
        return $this->where('receiver_id', $userId['id'])
            ->where('read_at IS NULL')
            ->set(['read_at' => date('Y-m-d H:i:s')])
            ->update();
    }

    public function getRecentConversations(array $userId)
    {
        $subquery = $this->db->table('messages m2')
            ->select('MAX(id) as max_id')
            ->where('(sender_id = ' . $userId['id'] . ' OR receiver_id = ' . $userId['id'] . ')')
            ->groupBy('CASE 
                WHEN sender_id = ' . $userId['id'] . ' THEN receiver_id 
                ELSE sender_id 
                END');

        return $this->select('messages.*, sender.name as sender_name, receiver.name as receiver_name')
            ->join('users as sender', 'sender.id = messages.sender_id')
            ->join('users as receiver', 'receiver.id = messages.receiver_id')
            ->whereIn('messages.id', $subquery)
            ->orderBy('messages.created_at', 'DESC')
            ->findAll();
    }
} 