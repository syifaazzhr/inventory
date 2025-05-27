<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailPenerimaanModel extends Model
{
    protected $table = 'detail_penerimaan';
    protected $primaryKey = 'det_terima_id';
    protected $returnType = 'array';
    protected $allowedFields = ['terima_id', 'stok_id', 'qty_det_terima', 'jumlah_det_terima'];

    public function getDetailWithStock($terima_id)
    {
        return $this->select('detail_penerimaan.*, stok_bahan.kode_stok, stok_bahan.nama_barang')
                    ->join('stok_bahan', 'stok_bahan.stok_id = detail_penerimaan.stok_id', 'left')
                    ->where('detail_penerimaan.terima_id', $terima_id)
                    ->findAll();
    } 
}