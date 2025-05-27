<?php

namespace App\Models;

use CodeIgniter\Model;

class KetSupplierModel extends Model
{
    protected $table = 'ket_supplier'; // Pastikan nama tabel sesuai dengan database
    protected $primaryKey = 'ket_id'; 
    protected $returnType = 'array';
    protected $allowedFields = [
        'ket_jenis'
    ]; 

    public function getKetSupplier($search = null, $perPage = 10, $page = 1)
    {
        $query = $this->select('*'); // Select all fields from 'grup' table

        if ($search) {
            $query->like('ket_jenis', $search); // Apply search filter
        }

        // Gunakan paginate untuk mengambil data dengan pagination
        return $query->paginate($perPage, 'default', $page);
    }

    // Dates
    protected $useTimestamps    = true;
    protected $dateFormat       = 'datetime';
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $deletedField     = 'deleted_at';

    // Validation
    protected $validationRules  = [];
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