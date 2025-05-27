<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailReturModel extends Model
{
    protected $table = 'detail_retur';
    protected $primaryKey = 'det_retur_id';
    protected $returnType = 'array';
    protected $allowedFields = ['retur_id', 'stok_id', 'qty_det_retur', 'jumlah_det_retur'];

    public function getDetailWithStock($retur_id)
    {
        return $this->select('detail_retur.*, stok_bahan.kode_stok, stok_bahan.nama_barang')
                    ->join('stok_bahan', 'stok_bahan.stok_id = detail_retur.stok_id', 'left')
                    ->where('detail_retur.retur_id', $retur_id)
                    ->findAll();
    } 

    public function getDetailWithDetTerima($retur_id, $det_retur_id)
{
    return $this->db->table('detail_retur')
        ->select('detail_retur.*, detail_penerimaan.det_terima_id')
        ->join('retur', 'retur.retur_id = detail_retur.retur_id', 'left')
        ->join('detail_penerimaan', 'detail_penerimaan.stok_id = detail_retur.stok_id', 'left')
        ->where('detail_retur.retur_id', $retur_id)
        ->where('detail_retur.det_retur_id', $det_retur_id)
        ->get()
        ->getRowArray();
}
}