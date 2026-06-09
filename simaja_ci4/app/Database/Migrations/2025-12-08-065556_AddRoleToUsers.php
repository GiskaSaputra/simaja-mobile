<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRoleToUsers extends Migration
{
    public function up()
    {
        // Menambahkan kolom 'role' ke tabel 'users'
        $fields = [
            'role' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'user', // Default semua orang adalah user biasa
                'after'      => 'username' // Letakkan kolom ini setelah kolom username
            ],
        ];

        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        // Menghapus kolom 'role' jika migration di-rollback
        $this->forge->dropColumn('users', 'role');
    }
}