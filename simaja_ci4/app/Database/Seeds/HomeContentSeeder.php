<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class HomeContentSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'id' => 1, 
            'hero_title' => 'Hi, Selamat Datang di SIMAJA',
            'hero_subtitle' => 'Sistem Manajemen Study Jam',
            'hero_description' => 'Temukan pengalaman belajar baru bersama SIMAJA, sistem manajemen yang memfasilitasi kegiatan Study Jam Protic secara terorganisir dan menyenangkan.',
            'hero_image' => 'studyjam.png',
            'updated_at' => Time::now(),
        ];

        // Cek jika data sudah ada
        $exists = $this->db->table('home_content')->where('id', 1)->countAllResults();
        
        if ($exists == 0) {
            $this->db->table('home_content')->insert($data);
        }
    }
}