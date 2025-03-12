<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
                'unique' => true
            ],
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false
            ],
            'role' => [
                'type' => 'ENUM',
                'constraint' => ['client', 'freelancer'],
                'default' => 'client',
                'null' => false
            ],
            'avatar' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],
            'bio' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'location' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],
            'website' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],
            'social_links' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'skills' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'hourly_rate' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true
            ],
            'availability' => [
                'type' => 'ENUM',
                'constraint' => ['available', 'busy', 'unavailable'],
                'default' => 'available',
                'null' => true
            ],
            'email_verified_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'verification_token' => [
                'type' => 'VARCHAR',
                'constraint' => 64,
                'null' => true
            ],
            'reset_token' => [
                'type' => 'VARCHAR',
                'constraint' => 64,
                'null' => true
            ],
            'reset_token_expires_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'remember_token' => [
                'type' => 'VARCHAR',
                'constraint' => 64,
                'null' => true
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true
            ]
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('email');
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
} 