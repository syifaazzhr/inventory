<?php

namespace App\Models;

use CodeIgniter\Model; 

class LotRolReturModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'lotrol_retur';
    protected $primaryKey       = 'lotrol_retur_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'retur_id',
        'det_retur_id',
        'po_id',
        'lot_retur',
        'lot_terima_asal',
        'rol_retur',
        'rol_terima_asal',
        'qty_lotrol_retur',
        'lotrol_terima_id',
    ];    

    public function getLotRolByReturIdAndDetId($retur_id, $det_retur_id)
    {
        return $this->where('retur_id', $retur_id)
                    ->where('det_retur_id', $det_retur_id)
                    ->first(); 
    }

    public function getDetailByReturIdAndDetId($retur_id, $det_retur_id)
    {
        $detailReturModel = new \App\Models\DetailReturModel();
        $stokBahanModel = new \App\Models\StokBahanModel();

        $detail = $detailReturModel
            ->join('stok_bahan', 'stok_bahan.stok_id = detail_retur.stok_id')
            ->join('retur', 'retur.retur_id = detail_retur.retur_id') 
            ->where('detail_retur.retur_id', $retur_id)
            ->where('detail_retur.det_retur_id', $det_retur_id)
            ->first();

        if (!$detail) {
            return ['error' => 'Data not found'];
        }

        return [
            'det_retur_id' => $detail['det_retur_id'],
            'po_id' => $detail['po_id'] ?? '-',
            'kode_stok' => $detail['kode_stok'] ?? '-',
            'nama_barang' => $detail['nama_barang'] ?? '-',
            'qty_det_retur' => $detail['qty_det_retur'] ?? 0
        ];
    }
}