<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class _2024_03_12_000003_create_jobs_table extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'description' => [
                'type' => 'TEXT',
            ],
            'client_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'category_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'budget_min' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'budget_max' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'duration' => [
                'type' => 'ENUM',
                'constraint' => ['less_than_1_month', '1_to_3_months', '3_to_6_months', 'more_than_6_months'],
                'default' => 'less_than_1_month',
            ],
            'skills_required' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'experience_level' => [
                'type' => 'ENUM',
                'constraint' => ['entry', 'intermediate', 'expert'],
                'default' => 'intermediate',
            ],
            'project_type' => [
                'type' => 'ENUM',
                'constraint' => ['fixed', 'hourly'],
                'default' => 'fixed',
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['draft', 'open', 'in_progress', 'completed', 'cancelled'],
                'default' => 'draft',
            ],
            'attachments' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'visibility' => [
                'type' => 'ENUM',
                'constraint' => ['public', 'private', 'invite_only'],
                'default' => 'public',
            ],
            'featured_until' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'awarded_bid_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'completed_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('client_id');
        $this->forge->addKey('category_id');
        $this->forge->addKey('awarded_bid_id');
        $this->forge->addForeignKey('client_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('category_id', 'categories', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('jobs');
    }

    public function down()
    {
        $this->forge->dropTable('jobs');
    }
} 