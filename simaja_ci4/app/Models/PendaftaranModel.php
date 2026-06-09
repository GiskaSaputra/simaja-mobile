<?php

namespace App\Models;

use CodeIgniter\Model;

class PendaftaranModel extends Model
{
    protected $table            = 'pendaftaran';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['user_id', 'jadwal_id'];
    protected $useTimestamps    = true;

    // Cek apakah user sudah terdaftar di jadwal tertentu
    public function cekTerdaftar($userId, $jadwalId)
    {
        return $this->where(['user_id' => $userId, 'jadwal_id' => $jadwalId])->first();
    }
}