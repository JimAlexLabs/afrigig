<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAuthFieldsToUsers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'email_verified_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'email'
            ],
            'verification_token' => [
                'type' => 'VARCHAR',
                'constraint' => 64,
                'null' => true,
                'after' => 'email_verified_at'
            ],
            'reset_token' => [
                'type' => 'VARCHAR',
                'constraint' => 64,
                'null' => true,
                'after' => 'verification_token'
            ],
            'reset_token_expires_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'reset_token'
            ],
            'remember_token' => [
                'type' => 'VARCHAR',
                'constraint' => 64,
                'null' => true,
                'after' => 'reset_token_expires_at'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', [
            'email_verified_at',
            'verification_token',
            'reset_token',
            'reset_token_expires_at',
            'remember_token'
        ]);
    }
} 