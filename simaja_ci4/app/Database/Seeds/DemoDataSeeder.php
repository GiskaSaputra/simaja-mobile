<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class DemoDataSeeder extends Seeder
{
    public function run()
    {
        // 1. BUAT USER BARU (Login: username / 'password')
        $passwordHash = password_hash('password', PASSWORD_DEFAULT);

        $users = [
            ['id' => 2, 'email' => 'giska@example.com', 'username' => 'giska', 'password_hash' => $passwordHash, 'active' => 1],
            ['id' => 3, 'email' => 'ale@example.com', 'username' => 'ale', 'password_hash' => $passwordHash, 'active' => 1],
            ['id' => 4, 'email' => 'daffa@example.com', 'username' => 'daffa', 'password_hash' => $passwordHash, 'active' => 1],
            ['id' => 5, 'email' => 'akmal@example.com', 'username' => 'akmal', 'password_hash' => $passwordHash, 'active' => 1],
            ['id' => 6, 'email' => 'ilham@example.com', 'username' => 'ilham', 'password_hash' => $passwordHash, 'active' => 1],
            ['id' => 7, 'email' => 'bima@example.com', 'username' => 'bima', 'password_hash' => $passwordHash, 'active' => 1],
        ];

        // Insert User dengan Pengecekan Username & Email
        foreach ($users as $user) {
            // Cek apakah username atau email atau ID sudah ada
            $exists = $this->db->table('users')
                               ->groupStart()
                                   ->where('username', $user['username'])
                                   ->orWhere('email', $user['email'])
                                   ->orWhere('id', $user['id'])
                               ->groupEnd()
                               ->countAllResults();

            if ($exists == 0) {
                $this->db->table('users')->insert($user);
            }
        }

        // 2. BUAT PROFIL PESERTA
        $profiles = [
            [
                'user_id' => 2, 
                'nama_lengkap' => 'Giska Anindya', 
                'nim' => '24030024', 
                'kelas' => '2A',
                'prodi' => 'Teknik Informatika',
                'jurusan' => 'Komputer & Bisnis',
                'semester' => '3'
            ],
            [
                'user_id' => 3, 
                'nama_lengkap' => 'Ale Handoko', 
                'nim' => '24030025', 
                'kelas' => '2B',
                'prodi' => 'Teknik Informatika',
                'jurusan' => 'Komputer & Bisnis',
                'semester' => '3'
            ],
            [
                'user_id' => 4, 
                'nama_lengkap' => 'Daffa Pratama', 
                'nim' => '24030026', 
                'kelas' => '2A',
                'prodi' => 'Teknik Mesin',
                'jurusan' => 'Teknik',
                'semester' => '3'
            ],
            [
                'user_id' => 5, 
                'nama_lengkap' => 'Akmal Saputra', 
                'nim' => '24030027', 
                'kelas' => '1A',
                'prodi' => 'Rekayasa Keamanan Siber',
                'jurusan' => 'Komputer & Bisnis',
                'semester' => '1'
            ],
            [
                'user_id' => 6, 
                'nama_lengkap' => 'Ilham Maulana', 
                'nim' => '24030028', 
                'kelas' => '3C',
                'prodi' => 'Teknik Elektro',
                'jurusan' => 'Teknik',
                'semester' => '5'
            ],
            [
                'user_id' => 7, 
                'nama_lengkap' => 'Bima Aryo', 
                'nim' => '24030029', 
                'kelas' => '2A',
                'prodi' => 'Teknologi Rekayasa Multimedia',
                'jurusan' => 'Komputer & Bisnis',
                'semester' => '3'
            ],
        ];

        foreach ($profiles as $p) {
            
            if ($this->db->table('profiles')->where('user_id', $p['user_id'])->countAllResults() == 0) {
                
                $userExists = $this->db->table('users')->where('id', $p['user_id'])->countAllResults();
                if ($userExists > 0) {
                    $this->db->table('profiles')->insert($p);
                }
            }
        }

        // 3. BERIKAN NILAI ACAK (Agar masuk Peringkat)
        $pertemuanList = $this->db->table('pertemuan')->limit(5)->get()->getResultArray();

        if (!empty($pertemuanList)) {
            foreach ($users as $u) {
                // Pastikan user benar-benar ada di DB sebelum insert nilai
                $userInDb = $this->db->table('users')->where('username', $u['username'])->get()->getRowArray();
                
                if ($userInDb) {
                    $userIdReal = $userInDb['id']; // Gunakan ID asli dari DB (jaga-jaga jika ID berbeda)

                    foreach ($pertemuanList as $p) {
                        $skor = rand(60, 100);
                        
                        $exists = $this->db->table('nilai')
                                        ->where('user_id', $userIdReal)
                                        ->where('pertemuan_id', $p['id'])
                                        ->countAllResults();
                        
                        if ($exists == 0) {
                            $this->db->table('nilai')->insert([
                                'user_id'      => $userIdReal,
                                'pertemuan_id' => $p['id'],
                                'skor'         => $skor,
                                'created_at'   => Time::now()
                            ]);
                        }
                    }
                }
            }
        }
    }
}