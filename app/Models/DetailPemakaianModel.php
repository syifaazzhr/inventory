<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailPemakaianModel extends Model
{
    protected $table = 'detail_pemakaian';
    protected $primaryKey = 'det_pakai_id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'pakai_id', 
        'stok_id', 
        'qty_det_pakai'
    ];

    public function getDetailWithStock($pakai_id)
    {
        return $this->select('detail_pemakaian.*, stok_bahan.kode_stok, stok_bahan.nama_barang')
                    ->join('stok_bahan', 'stok_bahan.stok_id = detail_pemakaian.stok_id', 'left')
                    ->where('detail_pemakaian.pakai_id', $pakai_id)
                    ->findAll();
    } 

    public function getDetailWithDetTerima($pakai_id, $det_pakai_id)
    {
        return $this->db->table('detail_pemakaian')
            ->select('detail_pemakaian.*, detail_penerimaan.det_terima_id')
            ->join('pemakaian', 'pemakaian.pakai_id = detail_pemakaian.pakai_id', 'left')
            ->join('detail_penerimaan', 'detail_penerimaan.stok_id = detail_pemakaian.stok_id AND detail_penerimaan.terima_id = pemakaian.terima_id', 'left')
            ->where('detail_pemakaian.pakai_id', $pakai_id)
            ->where('detail_pemakaian.det_pakai_id', $det_pakai_id)
            ->get()
            ->getRowArray();
    }
}