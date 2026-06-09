<?php

namespace App\Models;

use CodeIgniter\Model;

class UserProgressModel extends Model
{
    protected $table            = 'user_progress';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    
    // PASTIKAN 'materi_id' ADA DI SINI!
    protected $allowedFields    = [
        'user_id', 
        'materi_id', 
        'pertemuan_id', 
        'is_completed', 
        'completed_at'
    ];
    
    protected $returnType       = 'array';
    protected $useTimestamps    = false; 
}