<?php

namespace App\Models;

use CodeIgniter\Model;

class WarnaModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'warna';
    protected $primaryKey       = 'warna_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
 
    protected $allowedFields    = [
        'kode_warna',
        'jenis_warna'
    ];
 
    public function getWarna($search = null, $limit = 10, $offset = 0)
    {
        $query = $this->select('*');

        if ($search) {
            $query->like('kode_warna', $search)
                ->orLike('jenis_warna', $search);
        }

        return $query->paginate($limit, 'default', $offset); // Fungsi paginate untuk mengambil data sesuai limit dan offset
    }

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [];    
    protected $validationMessages = [];
    protected $skipValidation   = false;

    // Callbacks
    protected $allowCallbacks   = true;
    protected $beforeInsert     = [];
    protected $afterInsert      = [];
    protected $beforeUpdate     = [];
    protected $afterUpdate      = [];
    protected $beforeFind       = [];
    protected $afterFind        = [];
    protected $beforeDelete     = [];
    protected $afterDelete      = [];
}