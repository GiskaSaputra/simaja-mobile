<?php namespace App\Models;
use CodeIgniter\Model;

class JawabanModel extends Model {
    protected $table = 'jawaban';
    protected $primaryKey = 'id';
    protected $allowedFields = ['soal_id', 'teks_jawaban', 'is_correct'];
}