<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class JadwalSeeder extends Seeder
{
    public function run()
    {
        
        $data = [
            [
                'judul'         => 'UI/UX Design',
                'deskripsi'     => 'Introduction UI/UX with Figma',
                'tanggal'       => '2025-05-01',
                'waktu_mulai'   => '16:00:00',
                'waktu_selesai' => '18:00:00',
                'kuota'         => 50,
                'terisi'        => 32,
                'created_at'    => Time::now(),
                'updated_at'    => Time::now(),
            ],
            [
                'judul'         => 'WEB Basic',
                'deskripsi'     => 'Introduction HTML',
                'tanggal'       => '2025-05-02',
                'waktu_mulai'   => '16:00:00',
                'waktu_selesai' => '18:00:00',
                'kuota'         => 50,
                'terisi'        => 29,
                'created_at'    => Time::now(),
                'updated_at'    => Time::now(),
            ],
            [
                'judul'         => 'WEB Advance',
                'deskripsi'     => 'Introduction Framework',
                'tanggal'       => '2025-05-03',
                'waktu_mulai'   => '16:00:00',
                'waktu_selesai' => '18:00:00',
                'kuota'         => 50,
                'terisi'        => 29,
                'created_at'    => Time::now(),
                'updated_at'    => Time::now(),
            ],
            [
                'judul'         => 'DevOps',
                'deskripsi'     => 'Introduction DevOps',
                'tanggal'       => '2025-05-03',
                'waktu_mulai'   => '16:00:00',
                'waktu_selesai' => '18:00:00',
                'kuota'         => 50,
                'terisi'        => 22,
                'created_at'    => Time::now(),
                'updated_at'    => Time::now(),
            ],
            [
                'judul'         => 'Data',
                'deskripsi'     => 'Introduction Data',
                'tanggal'       => '2025-05-04',
                'waktu_mulai'   => '16:00:00',
                'waktu_selesai' => '18:00:00',
                'kuota'         => 50,
                'terisi'        => 29,
                'created_at'    => Time::now(),
                'updated_at'    => Time::now(),
            ],
            [
                'judul'         => 'Mobile',
                'deskripsi'     => 'Introduction Mobile',
                'tanggal'       => '2025-05-04',
                'waktu_mulai'   => '16:00:00',
                'waktu_selesai' => '18:00:00',
                'kuota'         => 50,
                'terisi'        => 48,
                'created_at'    => Time::now(),
                'updated_at'    => Time::now(),
            ],
        ];

        
        $this->db->table('jadwal')->insertBatch($data);
    }
}