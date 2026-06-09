<?php

    namespace App\Database\Seeds;

    use CodeIgniter\Database\Seeder;

    class SetAdminRole extends Seeder
    {
        public function run()
        {
            // PENTING: Ganti 'admin' di bawah ini dengan username admin Anda yang sebenarnya di database.
            // Jika username admin Anda adalah 'superadmin', ganti menjadi 'superadmin'.
            $usernameAdmin = 'admin';

            // Menjalankan query update
            $this->db->table('users')
                     ->where('username', $usernameAdmin)
                     ->update(['role' => 'admin']);

            // Menampilkan pesan sukses di terminal (opsional)
            // Menggunakan affectedRows() untuk mengecek apakah ada data yang berubah
            if ($this->db->affectedRows() > 0) {
                echo "Role user '{$usernameAdmin}' berhasil diubah menjadi 'admin'.\n";
            } else {
                echo "User '{$usernameAdmin}' tidak ditemukan atau role sudah 'admin'.\n";
            }
        }
    }