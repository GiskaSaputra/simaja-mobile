<?php namespace App\Models;
use CodeIgniter\Model;

class NilaiModel extends Model {
    protected $table = 'nilai';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'pertemuan_id', 'skor', 'created_at'];
    
    public function getNilai($userId, $pertemuanId) {
        return $this->where(['user_id' => $userId, 'pertemuan_id' => $pertemuanId])->first();
    }
}