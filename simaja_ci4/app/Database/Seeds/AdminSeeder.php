<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Data Admin
        $adminData = [
            'email' => 'admin@simaja.com',
            'username' => 'admin',
            'password_hash' => password_hash('admin123', PASSWORD_DEFAULT), // Password: admin123
            'active' => 1
        ];

        // Cek jika admin sudah ada
        $exists = $this->db->table('users')->where('email', 'admin@simaja.com')->countAllResults();

        if ($exists == 0) {
            $this->db->table('users')->insert($adminData);
            echo "User Admin berhasil dibuat. Email: admin@simaja.com, Pass: admin123\n";
        } else {
            echo "User Admin sudah ada.\n";
        }
    }
}