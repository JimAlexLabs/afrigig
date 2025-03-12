<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMigrationsTable extends Migration
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
            'version' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false
            ],
            'class' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false
            ],
            'group' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false
            ],
            'namespace' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false
            ],
            'time' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false
            ],
            'batch' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false
            ]
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('migrations');
    }

    public function down()
    {
        $this->forge->dropTable('migrations');
    }
} 