<?php

namespace App\Models;

use CodeIgniter\Model;

class Job extends Model
{
    protected $table = 'jobs';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $allowedFields = [
        'user_id',
        'category_id',
        'title',
        'description',
        'budget',
        'deadline',
        'status',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'user_id' => 'required|integer|is_not_unique[users.id]',
        'category_id' => 'required|integer|is_not_unique[categories.id]',
        'title' => 'required|min_length[10]|max_length[255]',
        'description' => 'required|min_length[50]',
        'budget' => 'required|numeric|greater_than[0]',
        'deadline' => 'required|valid_date',
        'status' => 'required|in_list[open,in_progress,completed,cancelled]',
    ];

    protected $validationMessages = [
        'user_id' => [
            'required' => 'User ID is required',
            'integer' => 'User ID must be an integer',
            'is_not_unique' => 'User does not exist',
        ],
        'category_id' => [
            'required' => 'Category ID is required',
            'integer' => 'Category ID must be an integer',
            'is_not_unique' => 'Category does not exist',
        ],
        'title' => [
            'required' => 'Title is required',
            'min_length' => 'Title must be at least 10 characters long',
            'max_length' => 'Title cannot exceed 255 characters',
        ],
        'description' => [
            'required' => 'Description is required',
            'min_length' => 'Description must be at least 50 characters long',
        ],
        'budget' => [
            'required' => 'Budget is required',
            'numeric' => 'Budget must be a number',
            'greater_than' => 'Budget must be greater than 0',
        ],
        'deadline' => [
            'required' => 'Deadline is required',
            'valid_date' => 'Please enter a valid date',
        ],
        'status' => [
            'required' => 'Status is required',
            'in_list' => 'Status must be one of: open, in_progress, completed, cancelled',
        ],
    ];

    public function getWithDetails()
    {
        return $this->select('jobs.*, users.name as client_name, categories.name as category_name')
            ->join('users', 'users.id = jobs.user_id')
            ->join('categories', 'categories.id = jobs.category_id')
            ->findAll();
    }

    public function getByUser(array $userId)
    {
        return $this->where('user_id', $userId['id'])->findAll();
    }

    public function getByCategory(array $categoryId)
    {
        return $this->where('category_id', $categoryId['id'])->findAll();
    }

    public function getOpenJobs()
    {
        return $this->where('status', 'open')
            ->where('deadline >=', date('Y-m-d'))
            ->findAll();
    }

    public function searchJobs(string $keyword)
    {
        return $this->like('title', $keyword)
            ->orLike('description', $keyword)
            ->findAll();
    }

    public function updateStatus(array $jobId, string $status)
    {
        return $this->update($jobId['id'], ['status' => $status]);
    }
} 