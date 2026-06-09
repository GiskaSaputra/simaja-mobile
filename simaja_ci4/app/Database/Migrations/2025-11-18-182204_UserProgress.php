<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UserProgress extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'materi_id' => [ 
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'pertemuan_id' => [ 
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'is_completed' => [ 
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1, 
            ],
            'completed_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        // Tambahkan unique key agar satu user hanya bisa menyelesaikan satu pertemuan sekali
        $this->forge->addUniqueKey(['user_id', 'pertemuan_id']);
        $this->forge->createTable('user_progress');
    }

    public function down()
    {
        $this->forge->dropTable('user_progress');
    }
}