<?php

namespace App\Models;

use CodeIgniter\Model;

class HomeContentModel extends Model
{
    protected $table            = 'home_content';
    protected $primaryKey       = 'id';
    
    protected $allowedFields    = [
        'hero_title', 
        'hero_subtitle', 
        'hero_description', 
        'hero_image', 
        'updated_at' // Penting agar timestamps bekerja
    ];
    
    protected $useTimestamps    = true;
    protected $updatedField     = 'updated_at';
}
