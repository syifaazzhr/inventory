<?php

namespace App\Models;

use CodeIgniter\Model;

class SatuanModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'satuan';
    protected $primaryKey       = 'satuan_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'kode_satuan',
        'jenis_satuan',
        'isi_satuan',
        'unit_id'
    ];

    public function getSatuan($search = null, $perPage = 10, $page = 1)
    {
        $query = $this->select("satuan.*, unit_satuan.jenis_unit as jenis_unit")
        ->join('unit_satuan', 'unit_satuan.unit_id = satuan.unit_id', 'left');

        if ($search) {
            $query->groupStart()
                ->like('satuan.kode_satuan', $search)
                ->orLike('satuan.jenis_satuan', $search)
                ->orLike('satuan.isi_satuan', $search)
                ->orLike('unit_satuan.unit_id', $search)
                ->groupEnd();
        }

        return $query->paginate($perPage, 'default', $page);
    }
}