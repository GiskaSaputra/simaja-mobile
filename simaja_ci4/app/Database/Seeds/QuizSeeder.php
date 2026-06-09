<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class QuizSeeder extends Seeder
{
    public function run()
    {

        // Ambil ID semua pertemuan
        $pertemuanList = $this->db->table('pertemuan')->select('id, judul_pertemuan')->get()->getResultArray();

        if (empty($pertemuanList)) {
            echo "Tidak ada data pertemuan. Harap jalankan MateriSeeder terlebih dahulu.\n";
            return;
        }

        // Bank Soal Dummy 
        $bankSoal = [
            [
                'tanya' => 'Apa kepanjangan dari HTML?',
                'opsi'  => [
                    ['text' => 'Hyper Text Markup Language', 'benar' => 1],
                    ['text' => 'Hyperlinks and Text Marking Language', 'benar' => 0],
                    ['text' => 'Home Tool Markup Language', 'benar' => 0],
                    ['text' => 'Hyper Tech Modern Language', 'benar' => 0],
                ]
            ],
            [
                'tanya' => 'Tag HTML mana yang digunakan untuk membuat link?',
                'opsi'  => [
                    ['text' => '<a>', 'benar' => 1],
                    ['text' => '<link>', 'benar' => 0],
                    ['text' => '<href>', 'benar' => 0],
                    ['text' => '<url>', 'benar' => 0],
                ]
            ],
            [
                'tanya' => 'Properti CSS untuk mengubah warna teks adalah?',
                'opsi'  => [
                    ['text' => 'color', 'benar' => 1],
                    ['text' => 'text-color', 'benar' => 0],
                    ['text' => 'font-color', 'benar' => 0],
                    ['text' => 'background-color', 'benar' => 0],
                ]
            ],
            [
                'tanya' => 'Manakah yang merupakan framework PHP?',
                'opsi'  => [
                    ['text' => 'CodeIgniter', 'benar' => 1],
                    ['text' => 'React', 'benar' => 0],
                    ['text' => 'Vue', 'benar' => 0],
                    ['text' => 'Django', 'benar' => 0],
                ]
            ],
            [
                'tanya' => 'Tools desain UI yang berbasis web adalah?',
                'opsi'  => [
                    ['text' => 'Figma', 'benar' => 1],
                    ['text' => 'Photoshop', 'benar' => 0],
                    ['text' => 'Sketch', 'benar' => 0],
                    ['text' => 'CorelDraw', 'benar' => 0],
                ]
            ],
        ];

        // Loop ke setiap pertemuan dan masukkan soal
        foreach ($pertemuanList as $index => $p) {
            
            for ($i = 0; $i < 3; $i++) {
                // Pilih soal dari bank soal
                $soalTemplate = $bankSoal[($index + $i) % count($bankSoal)];

                // Insert Soal
                $this->db->table('soal')->insert([
                    'pertemuan_id' => $p['id'],
                    'pertanyaan'   => $soalTemplate['tanya']
                ]);
                
                // Ambil ID soal yang baru dibuat
                $soalId = $this->db->insertID();

                // Siapkan data jawaban
                $dataJawaban = [];
                foreach ($soalTemplate['opsi'] as $jawaban) {
                    $dataJawaban[] = [
                        'soal_id'      => $soalId,
                        'teks_jawaban' => $jawaban['text'],
                        'is_correct'   => $jawaban['benar']
                    ];
                }

                // Insert Batch Jawaban
                $this->db->table('jawaban')->insertBatch($dataJawaban);
            }
        }
    }
}