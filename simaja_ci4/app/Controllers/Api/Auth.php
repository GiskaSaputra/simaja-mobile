<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel; // Pastikan kamu punya UserModel
use CodeIgniter\I18n\Time;

class Auth extends ResourceController
{
    protected $format = 'json';

    public function login()
    {
        $data = $this->request->getJSON();
        $login = $data->login ?? null; // Bisa username atau email
        $password = $data->password ?? null;

        if (!$login || !$password) {
            return $this->fail('Username/Email dan password wajib diisi', 400);
        }

        $userModel = new UserModel();
        // Cari user berdasarkan username ATAU email
        $user = $userModel->where('username', $login)
                          ->orWhere('email', $login)
                          ->first();

        if (!$user) {
            return $this->fail('Akun tidak ditemukan.', 404);
        }

        // Cek Password (karena kita pakai password_hash di seeder)
        if (!password_verify($password, $user['password_hash'])) {
            return $this->fail('Password salah.', 401);
        }

        return $this->respond([
            'status' => 200,
            'message' => 'Login berhasil',
            'data' => [
                'id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email'],
                'role' => $user['role']
            ]
        ]);
    }

    public function register()
    {
        $data = $this->request->getJSON();
        
        $username = $data->username ?? null;
        $email = $data->email ?? null;
        $password = $data->password ?? null;

        if (!$username || !$email || !$password) {
            return $this->fail('Semua kolom wajib diisi', 400);
        }

        $userModel = new UserModel();

        // Cek duplikasi email atau username
        if ($userModel->where('email', $email)->first()) {
            return $this->fail('Email sudah terdaftar', 409);
        }
        if ($userModel->where('username', $username)->first()) {
            return $this->fail('Username sudah dipakai', 409);
        }

        // Simpan user baru
        $userModel->save([
            'username' => $username,
            'email' => $email,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'role' => 'user',
            'active' => 1,
            'created_at' => Time::now()
        ]);

        return $this->respondCreated([
            'status' => 201,
            'message' => 'Registrasi berhasil, silakan login.'
        ]);
    }

    public function forgot()
    {
        $data = $this->request->getJSON();
        $email = $data->email ?? null;

        if (!$email) {
            return $this->fail('Email wajib diisi', 400);
        }

        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();

        if (!$user) {
            return $this->fail('Email tidak terdaftar di sistem kami.', 404);
        }

        // Catatan: Di aplikasi nyata (Production), di sini adalah tempat untuk
        // menaruh kodingan SMTP Email untuk mengirim link reset sungguhan.
        // Untuk saat ini, kita kembalikan status sukses ke aplikasi Flutter.

        return $this->respond([
            'status' => 200,
            'message' => 'Instruksi reset password telah dikirim ke email Anda.'
        ]);
    }

    // ================= FUNGSI RESET PASSWORD =================
    public function reset()
    {
        $data = $this->request->getJSON();
        $token = $data->token ?? null;
        $newPassword = $data->password ?? null;

        if (!$token || !$newPassword) {
            return $this->fail('Token dan password baru wajib diisi', 400);
        }

        $userModel = new UserModel();
        
        // Cari user yang punya token tersebut dan token belum expired
        $user = $userModel->where('reset_hash', $token)
                          ->where('reset_expires >=', Time::now()->format('Y-m-d H:i:s'))
                          ->first();

        if (!$user) {
            return $this->fail('Token tidak valid atau sudah kedaluwarsa.', 400);
        }

        // Update password baru dan hanguskan token-nya
        $userModel->update($user['id'], [
            'password_hash' => password_hash($newPassword, PASSWORD_DEFAULT),
            'reset_hash' => null,
            'reset_expires' => null
        ]);

        return $this->respond([
            'status' => 200,
            'message' => 'Password berhasil direset. Silakan login menggunakan password baru.'
        ]);
    }

    // ================= FUNGSI LOGOUT =================
    public function logout()
    {
        // Pada API Mobile yang menggunakan JWT/Token, proses logout biasanya 
        // hanya menghapus sesi di penyimpanan lokal aplikasi (SharedPreferences).
        // Kita sediakan endpoint ini sebagai formalitas API yang baik.
        
        return $this->respond([
            'status' => 200,
            'message' => 'Logout berhasil.'
        ]);
    }


}