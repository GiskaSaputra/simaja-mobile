<?php

namespace App\Models;

use CodeIgniter\Model;

class ProfileModel extends Model
{
    protected $table            = 'profiles';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields    = [
        'user_id', 'nama_lengkap', 'nim', 'kelas', 'prodi', 
        'jurusan', 'semester', 'jenis_kelamin', 'alamat', 'foto'
    ];
    protected $useTimestamps    = true;

    // Fungsi untuk mengambil profil berdasarkan user_id login
    public function getProfile($userId)
    {
        return $this->where('user_id', $userId)->first();
    }
}