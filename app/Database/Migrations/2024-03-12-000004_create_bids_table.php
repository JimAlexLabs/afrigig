<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class _2024_03_12_000004_create_bids_table extends Migration
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
            'job_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'freelancer_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'delivery_time' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'proposal' => [
                'type' => 'TEXT',
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'awarded', 'rejected', 'withdrawn', 'completed'],
                'default' => 'pending',
            ],
            'attachments' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'terms_accepted' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'awarded_at' => [
                'type' => 'DATETIME',
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
        $this->forge->addKey('job_id');
        $this->forge->addKey('freelancer_id');
        $this->forge->addForeignKey('job_id', 'jobs', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('freelancer_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('bids');

        // Add foreign key for awarded_bid_id in jobs table
        $this->forge->addForeignKey('awarded_bid_id', 'bids', 'id', 'CASCADE', 'SET NULL', 'jobs');
    }

    public function down()
    {
        $this->forge->dropTable('bids');
    }
} 