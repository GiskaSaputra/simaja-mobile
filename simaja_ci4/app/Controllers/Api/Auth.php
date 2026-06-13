<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use Myth\Auth\Entities\User;
use Myth\Auth\Models\UserModel;
use CodeIgniter\I18n\Time;

class Auth extends ResourceController
{
    protected $format = 'json';

    // ================= FUNGSI LOGIN =================
    public function login()
    {
        $data = $this->request->getJSON();
        $login = $data->login ?? null; // Bisa username atau email
        $password = $data->password ?? null;

        if (!$login || !$password) {
            return $this->fail('Username/Email dan password wajib diisi', 400);
        }

        // Gunakan UserModel bawaan Myth:Auth
        $userModel = new UserModel();
        
        // Cari user berdasarkan username ATAU email
        $user = $userModel->where('username', $login)
                          ->orWhere('email', $login)
                          ->first();

        if (!$user) {
            return $this->fail('Akun tidak ditemukan.', 404);
        }

        // Cek apakah akun sudah aktif (Berdasarkan klik link di email)
        if ($user->active == 0) {
            return $this->fail('Akun Anda belum diaktivasi. Silakan cek email Anda.', 401);
        }

        // Cek Password menggunakan hash bawaan PHP (sesuai standar Myth:Auth)
        if (!password_verify($password, $user->password_hash)) {
            return $this->fail('Password salah.', 401);
        }

        // Ambil nama role/group dari tabel auth_groups_users (Agar sinkron dengan Web)
        $db = \Config\Database::connect();
        $roleQuery = $db->table('auth_groups_users')
                        ->select('auth_groups.name as role')
                        ->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id')
                        ->where('user_id', $user->id)
                        ->get()->getRow();

        $role = $roleQuery ? $roleQuery->role : 'user';

        return $this->respond([
            'status' => 200,
            'message' => 'Login berhasil',
            'data' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'role' => $role
            ]
        ]);
    }

    // ================= FUNGSI REGISTER =================
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

        // Gunakan Entity Myth:Auth agar password di-hash secara otomatis
        $userEntity = new User([
            'email'    => $email,
            'username' => $username,
            'password' => $password,
            // active tidak di-set manual di sini, biarkan Myth:Auth yang mengatur
        ]);

        // Simpan ke tabel users
        $userModel->save($userEntity);
        $userId = $userModel->getInsertID();

        // --- MASUKKAN KE TABEL ROLE (GROUP) ---
        $db = \Config\Database::connect();
        $group = $db->table('auth_groups')->where('name', 'user')->get()->getRow();
        
        if ($group) {
            $db->table('auth_groups_users')->insert([
                'group_id' => $group->id,
                'user_id'  => $userId
            ]);
        }

        // --- PROSES KIRIM EMAIL AKTIVASI ---
        $config = config('Auth');
        if ($config->requireActivation !== null) {
            $activator = service('activator');
            $user = $userModel->find($userId); // Tarik ulang data user beserta hash-nya
            
            // Eksekusi pengiriman email via SMTP CI4
            if (! $activator->send($user)) {
                return $this->fail('Gagal mengirim email aktivasi. Cek konfigurasi SMTP Anda.', 500);
            }

            return $this->respondCreated([
                'status' => 201,
                'message' => 'Registrasi berhasil! Silakan cek email Anda untuk mengaktifkan akun sebelum login.'
            ]);
        }

        // Jika fitur aktivasi email sedang dimatikan di Config, paksa active = 1
        $userModel->update($userId, ['active' => 1]);

        return $this->respondCreated([
            'status' => 201,
            'message' => 'Registrasi berhasil, silakan login.'
        ]);
    }

    // ================= FUNGSI FORGOT PASSWORD =================
    public function forgot()
    {
        $data = $this->request->getJSON();
        $email = $data->email ?? null;

        if (!$email) return $this->fail('Email wajib diisi', 400);

        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();

        if (!$user) return $this->fail('Email tidak terdaftar di sistem kami.', 404);

        // Jika ingin menghubungkan proses forgot password email sungguhan, letakkan di sini
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

        if (!$token || !$newPassword) return $this->fail('Token dan password baru wajib diisi', 400);

        $userModel = new UserModel();
        
        // Cari user yang punya token tersebut dan token belum expired
        $user = $userModel->where('reset_hash', $token)
                          ->where('reset_expires >=', Time::now()->format('Y-m-d H:i:s'))
                          ->first();

        if (!$user) return $this->fail('Token tidak valid atau sudah kedaluwarsa.', 400);

        // Update menggunakan sistem Hash bawaan Myth:Auth
        $userModel->update($user->id, [
            'password_hash' => \Myth\Auth\Password::hash($newPassword),
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
        return $this->respond([
            'status' => 200,
            'message' => 'Logout berhasil.'
        ]);
    }
}