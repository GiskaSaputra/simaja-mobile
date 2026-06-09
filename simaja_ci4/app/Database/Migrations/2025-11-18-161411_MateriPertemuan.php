<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MateriPertemuan extends Migration
{
    public function up()
    {
        // 1. TABEL MATERI (Kategori Utama)
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'judul' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'deskripsi' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'gambar' => [ // Opsional: untuk icon/thumbnail
                'type' => 'VARCHAR',
                'constraint' => '255',
                'default' => 'default.png',
            ],
            'total_pertemuan' => [
                'type' => 'INT',
                'default' => 0,
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('materi');

        // 2. TABEL PERTEMUAN (Isi Detail per Materi)
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'materi_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'judul_pertemuan' => [ // Contoh: "Pertemuan 1: Pengenalan Figma"
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'isi_materi' => [ // Bisa link video, teks, atau nama file PDF
                'type' => 'TEXT',
                'null' => true,
            ],
            'urutan' => [ // Pertemuan ke-1, ke-2, dst
                'type' => 'INT',
                'default' => 1,
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('pertemuan');
    }

    public function down()
    {
        $this->forge->dropTable('pertemuan');
        $this->forge->dropTable('materi');
    }
}