<?php

namespace App\Models;

use CodeIgniter\Model;

class AbsensiModel extends Model
{
    protected $table            = 'absensi';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields    = ['user_id', 'jadwal_id', 'status'];
    protected $useTimestamps    = true;

    // Cek apakah user sudah absen di jadwal
    public function cekSudahAbsen($userId, $jadwalId)
    {
        return $this->where(['user_id' => $userId, 'jadwal_id' => $jadwalId])->first();
    }
}