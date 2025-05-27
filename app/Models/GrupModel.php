<?php

namespace App\Models;

use CodeIgniter\Model;

class GrupModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'grup'; 
    protected $primaryKey       = 'grup_id';
    protected $useAutoIncrement = true;
    protected $returnType        = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'kode_grup',
        'nama_grup'
    ];

    public function getGrup($search = null, $perPage = 10, $page = 1)
    {
        $query = $this->select('*'); 

        if ($search) {
            $query->like('nama_grup', $search); 
        }

        // Gunakan paginate untuk mengambil data dengan pagination
        return $query->paginate($perPage, 'default', $page);
    }
}