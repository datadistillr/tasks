<?php

namespace CodeIgniter\Tasks\Database\Migrations;

use CodeIgniter\Database\Migration;

class TaskStorage extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'int',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'type' => [
                'type'       => 'enum',
                'constraint' => ['call', 'command', 'shell', 'event', 'url'],
                'default'    => 'event',
            ],
            'expression' => [
                'type'       => 'varchar',
                'constraint' => 255,
            ],
            'command' => [
                'type' => 'text',
            ],
            'name' => [
                'type'       => 'varchar',
                'constraint' => 255,
            ],
            'start_at' => [
                'type' => 'datetime',
                'null' => true,
            ],
            'end_at' => [
                'type' => 'datetime',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'datetime',
                'null' => false,
            ],
            'updated_at' => [
                'type' => 'datetime',
                'null' => false,
            ],
            'deleted_at' => [
                'type' => 'datetime',
                'null' => true,
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable(config('CodeIgniter\Tasks\Config\Tasks')->databaseTable, true);
    }

    public function down()
    {
        $this->forge->dropTable(config('CodeIgniter\Tasks\Config\Tasks')->databaseTable);
    }
}
