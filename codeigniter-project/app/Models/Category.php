<?php

namespace App\Models;

use CodeIgniter\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'name',
        'slug',
        'description',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[255]',
        'slug' => 'required|min_length[3]|max_length[255]|is_unique[categories.slug,id,{id}]',
        'description' => 'permit_empty',
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Name is required',
            'min_length' => 'Name must be at least 3 characters long',
            'max_length' => 'Name cannot exceed 255 characters',
        ],
        'slug' => [
            'required' => 'Slug is required',
            'min_length' => 'Slug must be at least 3 characters long',
            'max_length' => 'Slug cannot exceed 255 characters',
            'is_unique' => 'This slug is already in use',
        ],
    ];

    protected $beforeInsert = ['createSlug'];
    protected $beforeUpdate = ['createSlug'];

    protected function createSlug(array $data)
    {
        if (! isset($data['data']['slug']) && isset($data['data']['name'])) {
            $data['data']['slug'] = url_title($data['data']['name'], '-', true);
        }

        return $data;
    }

    public function findBySlug(string $slug)
    {
        return $this->where('slug', $slug)->first();
    }

    public function getWithJobCount()
    {
        $db = \Config\Database::connect();
        $subquery = $db->table('jobs')
            ->select('category_id, COUNT(*) as job_count')
            ->groupBy('category_id');

        return $this->select('categories.*, COALESCE(job_count, 0) as job_count')
            ->join("({$subquery->getCompiledSelect()}) as job_counts", 'categories.id = job_counts.category_id', 'left')
            ->findAll();
    }
} 