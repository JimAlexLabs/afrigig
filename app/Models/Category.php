<?php

namespace App\Models;

use CodeIgniter\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'name',
        'slug',
        'description',
        'parent_id',
        'icon',
        'order',
        'is_active'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[255]|is_unique[categories.name,id,{id}]',
        'slug' => 'required|min_length[3]|max_length[255]|is_unique[categories.slug,id,{id}]',
        'description' => 'permit_empty|max_length[1000]',
        'parent_id' => 'permit_empty|integer',
        'icon' => 'permit_empty|max_length[50]',
        'order' => 'permit_empty|integer',
        'is_active' => 'required|in_list[0,1]'
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Category name is required',
            'min_length' => 'Category name must be at least 3 characters long',
            'max_length' => 'Category name cannot exceed 255 characters',
            'is_unique' => 'This category name already exists'
        ],
        'slug' => [
            'required' => 'Category slug is required',
            'min_length' => 'Category slug must be at least 3 characters long',
            'max_length' => 'Category slug cannot exceed 255 characters',
            'is_unique' => 'This category slug already exists'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Relationships
    public function parent()
    {
        return $this->belongsTo('App\Models\Category', 'parent_id');
    }

    public function children()
    {
        return $this->hasMany('App\Models\Category', 'parent_id');
    }

    public function jobs()
    {
        return $this->hasMany('App\Models\Job');
    }

    // Query Methods
    public function getActiveCategories()
    {
        return $this->where('is_active', 1)
                   ->orderBy('order', 'ASC')
                   ->findAll();
    }

    public function getParentCategories()
    {
        return $this->where('parent_id', null)
                   ->where('is_active', 1)
                   ->orderBy('order', 'ASC')
                   ->findAll();
    }

    public function getCategoryWithChildren(int $categoryId)
    {
        $category = $this->find($categoryId);
        if ($category) {
            $category['children'] = $this->where('parent_id', $categoryId)
                                      ->where('is_active', 1)
                                      ->orderBy('order', 'ASC')
                                      ->findAll();
        }
        return $category;
    }

    public function getAllCategoriesHierarchy()
    {
        $parents = $this->getParentCategories();
        foreach ($parents as &$parent) {
            $parent['children'] = $this->where('parent_id', $parent['id'])
                                     ->where('is_active', 1)
                                     ->orderBy('order', 'ASC')
                                     ->findAll();
        }
        return $parents;
    }

    // Helper Methods
    public function generateSlug(string $name)
    {
        $slug = url_title($name, '-', true);
        $count = 0;
        $originalSlug = $slug;
        
        while ($this->where('slug', $slug)->where('id !=', $this->id ?? 0)->countAllResults() > 0) {
            $count++;
            $slug = $originalSlug . '-' . $count;
        }
        
        return $slug;
    }

    public function toggleActive(int $categoryId)
    {
        $category = $this->find($categoryId);
        if ($category) {
            return $this->update($categoryId, [
                'is_active' => !$category['is_active']
            ]);
        }
        return false;
    }

    public function reorder(array $categoryIds)
    {
        foreach ($categoryIds as $order => $id) {
            $this->update($id, ['order' => $order + 1]);
        }
        return true;
    }
}
