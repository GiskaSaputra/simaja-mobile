<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class HomeContent extends Migration
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
            'hero_title' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'default'    => 'Hi, Selamat Datang di SIMAJA',
            ],
            'hero_subtitle' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'default'    => 'Sistem Manajemen Study Jam',
            ],
            'hero_description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'hero_image' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'default'    => 'studyjam.png',
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('home_content');
    }

    public function down()
    {
        $this->forge->dropTable('home_content');
    }
}