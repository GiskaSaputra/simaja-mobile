<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array'; // Wajib array agar bisa dibaca controller API kita
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    
    // Semua kolom di tabel users yang diizinkan untuk diisi/diubah
    protected $allowedFields    = [
        'email', 
        'username', 
        'password_hash', 
        'reset_hash', 
        'reset_at', 
        'reset_expires', 
        'activate_hash', 
        'status', 
        'status_message', 
        'active', 
        'force_pass_reset', 
        'role'
    ];

    // Pengaturan Waktu Otomatis
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}