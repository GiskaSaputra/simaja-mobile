<?php namespace App\Models;
use CodeIgniter\Model;

class SoalModel extends Model {
    protected $table = 'soal';
    protected $primaryKey = 'id';
    protected $allowedFields = ['pertemuan_id', 'pertanyaan'];

    // Ambil soal beserta jawabannya
    public function getSoalByPertemuan($pertemuanId) {
        return $this->where('pertemuan_id', $pertemuanId)->findAll();
    }
}