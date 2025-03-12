<?php

namespace App\Models;

use CodeIgniter\Model;

class User extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'bio',
        'skills',
        'email_verified_at',
        'remember_token',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'name' => 'required|min_length[3]|max_length[255]',
        'email' => 'required|valid_email|is_unique[users.email,id,{id}]',
        'password' => 'required|min_length[8]',
        'role' => 'required|in_list[freelancer,client]',
    ];
    protected $validationMessages   = [
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
            'in_list' => 'Role must be either freelancer or client',
        ],
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['hashPassword'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = ['hashPassword'];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    protected function hashPassword(array $data)
    {
        if (! isset($data['data']['password'])) {
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

    public function findBySocialId(string $provider, string $socialId)
    {
        return $this->where('provider', $provider)
            ->where('social_id', $socialId)
            ->first();
    }

    public function markEmailAsVerified(array $userId)
    {
        return $this->update($userId['id'], [
            'email_verified_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function updateRememberToken(array $userId, string $token)
    {
        return $this->update($userId['id'], [
            'remember_token' => $token,
        ]);
    }

    public function getFreelancers()
    {
        return $this->where('role', 'freelancer')
            ->findAll();
    }

    public function getClients()
    {
        return $this->where('role', 'client')
            ->findAll();
    }
}
