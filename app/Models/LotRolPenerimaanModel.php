<?php

namespace App\Models;

use CodeIgniter\Model;

class LotRolPenerimaanModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'lotrol_penerimaan';
    protected $primaryKey       = 'lotrol_terima_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'terima_id',
        'det_terima_id',
        'lot_terima',
        'rol_terima',
        'qty_lotrol_terima',
    ];    


    public function getLotRolByTerimaIdAndDetId($terima_id, $det_terima_id)
    {
        return $this->where('terima_id', $terima_id)
                    ->where('det_terima_id', $det_terima_id)
                    ->findAll();
    }

    public function getDetailByTerimaIdAndDetId($terima_id, $det_terima_id)
    {
        $detailPenerimaanModel = new \App\Models\DetailPenerimaanModel();
        $stokBahanModel = new \App\Models\StokBahanModel();

        // Retrieve the detail penerimaan by terima_id and det_terima_id
        $detail = $detailPenerimaanModel
                    ->join('stok_bahan', 'stok_bahan.stok_id = detail_penerimaan.stok_id')
                    ->where('terima_id', $terima_id)
                    ->where('det_terima_id', $det_terima_id)
                    ->first();

        // If no data is found, return an error message
        if (!$detail) {
            return ['error' => 'Data not found'];
        }

        // Return the necessary details in an array
        return [
            'det_terima_id' => $detail['det_terima_id'],
            'nomor_bon' => $detail['nomor_bon'] ?? 'Data not available',
            'nomor_po' => $detail['nomor_po'] ?? 'Data not available',
            'kode_stok' => $detail['kode_stok'] ?? 'Data not available',
            'nama_barang' => $detail['nama_barang'] ?? 'Data not available',
            'qty_det_terima' => $detail['qty_det_terima'] ?? 0
        ];
    }
}