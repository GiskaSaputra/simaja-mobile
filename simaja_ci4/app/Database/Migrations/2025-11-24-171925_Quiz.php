<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Quiz extends Migration
{
    public function up()
    {
        // 1. TABEL SOAL
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'pertemuan_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'pertanyaan' => ['type' => 'TEXT'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('soal');

        // 2. TABEL JAWABAN (Opsi Pilihan Ganda)
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'soal_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'teks_jawaban' => ['type' => 'VARCHAR', 'constraint' => 255],
            'is_correct' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0], // 1 = Benar, 0 = Salah
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('jawaban');

        // 3. TABEL NILAI (Hasil User)
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'pertemuan_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'skor' => ['type' => 'INT', 'constraint' => 3],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('nilai');
    }

    public function down()
    {
        $this->forge->dropTable('nilai');
        $this->forge->dropTable('jawaban');
        $this->forge->dropTable('soal');
    }
}