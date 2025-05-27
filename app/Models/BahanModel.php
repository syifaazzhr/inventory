<?php

namespace App\Models;

use CodeIgniter\Model;

class BahanModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'bahan';
    protected $primaryKey       = 'bahan_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'grup_id',  
        'kode_bahan',
        'nama_bahan',
    ];

    public function getBahan($search = null, $perPage = 10, $page = 1)
    {
        $query = $this->select('bahan.*, grup.nama_grup as nama_grup')
            ->join('grup', 'grup.grup_id = bahan.grup_id', 'left');

        if ($search) {
            $query->groupStart()
                ->like('grup.nama_grup', $search)
                ->orLike('bahan.kode_bahan', $search)
                ->orLike('bahan.nama_bahan', $search)
                ->groupEnd();
        }

        // Gunakan paginate untuk mengambil data dengan pagination
        return $query->paginate($perPage, 'default', $page);
    }

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}