<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailPOModel extends Model
{
    protected $table = 'detail_po';
    protected $primaryKey = 'detpo_id';
    protected $returnType = 'array';
    protected $allowedFields = ['po_id', 'stok_id', 'qty_detpo', 'jumlah_detpo'];
 
    public function getDetailWithStock($po_id)
    {
        return $this->select('detail_po.*, stok_bahan.kode_stok, stok_bahan.nama_barang, satuan.jenis_satuan, satuan.isi_satuan')
                    ->join('stok_bahan', 'stok_bahan.stok_id = detail_po.stok_id', 'left')
                    ->join('satuan', 'satuan.satuan_id = stok_bahan.satuan_id', 'left') // <-- Tambahan JOIN ke tabel satuan
                    ->where('detail_po.po_id', $po_id)
                    ->findAll();
    }
}