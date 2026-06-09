<?php

namespace App\Models;

use CodeIgniter\Model;

class PertemuanModel extends Model
{
    protected $table            = 'pertemuan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields    = ['materi_id', 'judul_pertemuan', 'isi_materi', 'urutan'];
    protected $useTimestamps    = true;

    // Fungsi ambil pertemuan berdasarkan materi
    public function getByMateri($materiId)
    {
        return $this->where('materi_id', $materiId)
                    ->orderBy('urutan', 'ASC')
                    ->findAll();
    }
}