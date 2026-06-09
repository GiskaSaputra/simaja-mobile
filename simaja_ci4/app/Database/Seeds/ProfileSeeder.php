<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class ProfileSeeder extends Seeder
{
    public function run()
    {
        // Pastikan user_id 1 ada di tabel users (Myth/Auth)
        $data = [
            'user_id'       => 1, // Asumsi Anda login dengan User ID 1
            'nama_lengkap'  => 'Nazril Andhika Aulia',
            'nim'           => '24030023',
            'kelas'         => '2A',
            'prodi'         => 'Teknik Informatika',
            'jurusan'       => 'Komputer & Bisnis',
            'semester'      => '3 (Tiga)',
            'jenis_kelamin' => 'Perempuan',
            'alamat'        => 'Jl. Flores, Sidanegara, Cilacap',
            'foto'          => 'default.png',
            'created_at'    => Time::now(),
            'updated_at'    => Time::now(),
        ];

        // Cek dulu biar tidak duplikat
        $exist = $this->db->table('profiles')->where('user_id', 1)->get()->getRow();
        
        if (!$exist) {
            $this->db->table('profiles')->insert($data);
        }
    }
}