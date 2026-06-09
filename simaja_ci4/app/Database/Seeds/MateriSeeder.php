<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class MateriSeeder extends Seeder
{
    public function run()
    {
        // 1. BERSIHKAN DATA LAMA 
        
        $this->db->query('SET FOREIGN_KEY_CHECKS=0;');
        $this->db->table('materi')->truncate();
        $this->db->table('pertemuan')->truncate();
        $this->db->table('user_progress')->truncate(); // Reset progress user 
        $this->db->query('SET FOREIGN_KEY_CHECKS=1;');

        // 2. INSERT MATERI UTAMA
        $dataMateri = [
            ['id' => 1, 'judul' => 'UI/UX Design', 'deskripsi' => 'Belajar desain antarmuka', 'total_pertemuan' => 6, 'created_at' => Time::now(), 'updated_at' => Time::now()],
            ['id' => 2, 'judul' => 'WEB Basic', 'deskripsi' => 'Dasar HTML & CSS', 'total_pertemuan' => 6, 'created_at' => Time::now(), 'updated_at' => Time::now()],
            ['id' => 3, 'judul' => 'WEB Advance', 'deskripsi' => 'Framework PHP & JS', 'total_pertemuan' => 6, 'created_at' => Time::now(), 'updated_at' => Time::now()],
            ['id' => 4, 'judul' => 'Mobile Dev', 'deskripsi' => 'Flutter Dasar', 'total_pertemuan' => 6, 'created_at' => Time::now(), 'updated_at' => Time::now()], // Sesuaikan nama dengan link Anda
            ['id' => 5, 'judul' => 'Data Science', 'deskripsi' => 'Analisis Data dengan Python', 'total_pertemuan' => 6, 'created_at' => Time::now(), 'updated_at' => Time::now()],
            ['id' => 6, 'judul' => 'DevOps', 'deskripsi' => 'Pengenalan Server & CI/CD', 'total_pertemuan' => 6, 'created_at' => Time::now(), 'updated_at' => Time::now()],
        ];
        $this->db->table('materi')->insertBatch($dataMateri);

        // 3. DEFINISI LINK PERTEMUAN (Youtube & PDF)
        
        $kontenPertemuan = [
            // Materi 1: UI/UX
            1 => [
                1 => 'https://youtu.be/k7cbBb2Ju5E',
                2 => 'uploads/uiux1.pdf',
                3 => 'https://youtu.be/uvpkJnYHOQU',
                4 => 'uploads/uiux2.pdf',
                5 => 'https://youtu.be/oimb79wuO18',
                6 => 'uploads/uiux3.pdf',
            ],
            // Materi 2: WEB Basic
            2 => [
                1 => 'https://youtu.be/0oA1Z6UKM5M',
                2 => 'uploads/web_basic1.pdf',
                3 => 'https://youtu.be/qwKm_7GmgBU',
                4 => 'uploads/web_basic2.pdf',
                5 => 'https://youtu.be/bzlxN3GVWR8',
                6 => 'uploads/web_basic3.pdf',
            ],
            // Materi 3: WEB Advance
            3 => [
                1 => 'https://youtu.be/bMSFTLfbm5E',
                2 => 'uploads/web_advance1.pdf',
                3 => 'https://youtu.be/ddJ94iaM3YQ',
                4 => 'uploads/web_advance2.pdf',
                5 => 'https://youtu.be/0HX4OO__cwQ',
                6 => 'uploads/web_advance3.pdf',
            ],
            // Materi 4: Mobile Dev
            4 => [
                1 => 'https://youtu.be/6dSNbskzlz4',
                2 => 'uploads/mobile1.pdf',
                3 => 'https://youtu.be/DhRIBJ6q8Ks',
                4 => 'uploads/mobile2.pdf',
                5 => 'https://youtu.be/d_ip0wTcKvw',
                6 => 'uploads/mobile3.pdf',
            ],
            // Materi 5: Data Science
            5 => [
                1 => 'https://youtu.be/iA8lLwmtKQM',
                2 => 'uploads/data1.pdf',
                3 => 'https://youtu.be/xETkm9H6aaY',
                4 => 'uploads/data2.pdf',
                5 => 'https://youtu.be/HSAm6s10G7g',
                6 => 'uploads/data3.pdf',
            ],
            // Materi 6: DevOps
            6 => [
                1 => 'https://www.youtube.com/live/jk_reSXv3OA',
                2 => 'uploads/devops1.pdf',
                3 => 'https://www.youtube.com/live/Vb4OgIpD1HI',
                4 => 'uploads/devops2.pdf',
                5 => 'https://www.youtube.com/live/7-CxUKUCfhM',
                6 => 'uploads/devops3.pdf',
            ],
        ];

        // 4. INSERT DETAIL PERTEMUAN
        $dataPertemuan = [];
        
        foreach ($dataMateri as $m) {
            $materiId = $m['id'];
            $totalPertemuan = $m['total_pertemuan'];

            for ($p = 1; $p <= $totalPertemuan; $p++) {
                
                $isiMateri = $kontenPertemuan[$materiId][$p] ?? 'Materi belum tersedia. Silakan hubungi mentor.';
                
                
                $judulPertemuan = "Pertemuan $p: " . $m['judul'];

                $dataPertemuan[] = [
                    'materi_id'       => $materiId,
                    'judul_pertemuan' => $judulPertemuan,
                    'isi_materi'      => $isiMateri,
                    'urutan'          => $p,
                    'created_at'      => Time::now(),
                    'updated_at'      => Time::now(),
                ];
            }
        }

        // Insert Batch 
        $this->db->table('pertemuan')->insertBatch($dataPertemuan);
    }
}