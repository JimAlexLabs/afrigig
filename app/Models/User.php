<?php

namespace App\Models;

use CodeIgniter\Model;

class User extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $allowedFields = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'phone',
        'address',
        'bio',
        'company',
        'website',
        'social_links',
        'skills',
        'hourly_rate',
        'availability',
        'email_verified_at',
        'remember_token',
        'google_id',
        'linkedin_id',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[255]',
        'email' => 'required|valid_email|is_unique[users.email,id,{id}]',
        'password' => 'required|min_length[8]',
        'role' => 'required|in_list[admin,freelancer,client]',
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Name is required',
            'min_length' => 'Name must be at least 3 characters long',
            'max_length' => 'Name cannot exceed 255 characters',
        ],
        'email' => [
            'required' => 'Email is required',
            'valid_email' => 'Please enter a valid email address',
            'is_unique' => 'This email is already registered',
        ],
        'password' => [
            'required' => 'Password is required',
            'min_length' => 'Password must be at least 8 characters long',
        ],
        'role' => [
            'required' => 'Role is required',
            'in_list' => 'Invalid role selected',
        ],
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    protected function hashPassword(array $data)
    {
        if (!isset($data['data']['password'])) {
            return $data;
        }

        $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        return $data;
    }

    public function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    public function findByEmail(string $email)
    {
        return $this->where('email', $email)->first();
    }

    public function markEmailAsVerified(int $userId)
    {
        return $this->update($userId, ['email_verified_at' => date('Y-m-d H:i:s')]);
    }

    public function updateRememberToken(int $userId, string $token)
    {
        return $this->update($userId, ['remember_token' => $token]);
    }

    public function updateSocialId(int $userId, string $provider, string $socialId)
    {
        $field = $provider . '_id';
        return $this->update($userId, [$field => $socialId]);
    }

    public function findBySocialId(string $provider, string $socialId)
    {
        $field = $provider . '_id';
        return $this->where($field, $socialId)->first();
    }

    public function getFreelancers()
    {
        return $this->where('role', 'freelancer')->findAll();
    }

    public function getClients()
    {
        return $this->where('role', 'client')->findAll();
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isFreelancer(): bool
    {
        return $this->role === 'freelancer';
    }

    public function isClient(): bool
    {
        return $this->role === 'client';
    }
}
