<?php

namespace App\Models;

use CodeIgniter\Model;

class LotRolPemakaianModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'lotrol_pemakaian';
    protected $primaryKey       = 'lotrol_pakai_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'pakai_id',
        'det_pakai_id',
        'po_id',
        'po_id_asal',
        'lot_pakai',
        'lot_terima_asal',
        'rol_pakai',
        'rol_terima_asal',
        'qty_lotrol_pakai',
        'lotrol_terima_id',
    ];    
 
    public function getLotRolByPemakaianIdAndDetId($pakai_id, $det_pakai_id)
    {
        return $this->where('pakai_id', $pakai_id)
                    ->where('det_pakai_id', $det_pakai_id)
                    ->first(); 
    }

    public function getDetailByPemakaianIdAndDetId($pakai_id, $det_pakai_id)
    {
        $detailPemakaianModel = new \App\Models\DetailPemakaianModel();
        $stokBahanModel = new \App\Models\StokBahanModel();

        $detail = $detailPemakaianModel
                    ->join('stok_bahan', 'stok_bahan.stok_id = detail_penerimaan.stok_id')
                    ->join('pemakaian', 'pemakaian.pakai_id = detail_pemakaian.pakai_id') 
                    ->where('pakai_id', $pakai_id)
                    ->where('det_pakai_id', $det_pakai_id)
                    ->first();

        if (!$detail) {
            return ['error' => 'Data not found'];
        }

        return [
            'det_pakai_id' => $detail['det_pakai_id'],
            'po_id' => $detail['po_id'] ?? '-',
            'kode_stok' => $detail['kode_stok'] ?? '-',
            'nama_barang' => $detail['nama_barang'] ?? '-',
            'qty_det_pakai' => $detail['qty_det_pakai'] ?? 0
        ];
    }
}