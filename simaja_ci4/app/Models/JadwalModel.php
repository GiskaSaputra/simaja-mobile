<?php

namespace App\Models;

use CodeIgniter\Model;

class JadwalModel extends Model
{
    protected $table            = 'jadwal';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array'; // Bisa juga 'object'
    protected $allowedFields    = ['judul', 'deskripsi', 'tanggal', 'waktu_mulai', 'waktu_selesai', 'kuota', 'terisi'];

    // Mengaktifkan timestamps otomatis
    protected $useTimestamps = true;
}