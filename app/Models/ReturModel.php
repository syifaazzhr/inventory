<?php

namespace App\Models;

use CodeIgniter\Model;

class ReturModel extends Model
{
    protected $table            = 'retur';
    protected $primaryKey       = 'retur_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'nomor_retur',
        'tgl_retur',
        'supplier_id',
        'ket_retur',
        'admin_retur',
        'total_qty_retur',
        'total_beli_retur',
        'ppn_retur',
        'total_beli_retur_ppn',
    ];

    public function formatDate($date)
    {
        return date('d-m-Y', strtotime($date));
    }

    public function generateNomorRetur($tgl_retur)
    {
        $date = date('ym', strtotime($tgl_retur));
        $last = $this->like('nomor_retur', "RT$date-", 'after')
                    ->orderBy('nomor_retur', 'DESC')
                    ->first();

        $next = 1;
        if ($last) {
            $next = intval(substr($last['nomor_retur'], -3)) + 1;
        }

        return 'RT' . $date . '-' . str_pad($next, 3, '0', STR_PAD_LEFT);
    }

    public function getRetur($search = null, $perPage = 10, $page = 1, $startDate = null, $endDate = null, $filterType = null)
    { 
        $query = $this->select('retur.*, supplier.kode_supplier, supplier.nama_supplier')
            ->join('supplier', 'supplier.supplier_id = retur.supplier_id', 'left');

        if ($search) {
            $query->groupStart()
                ->like('retur.nomor_retur', $search)
                ->orLike('supplier.nama_supplier', $search)
                ->groupEnd();
        }

        if ($filterType === 'tgl_retur' && $startDate && $endDate) {
            $query->where('retur.tgl_retur >=', $startDate)
                ->where('retur.tgl_retur <=', $endDate);
        } 

        $query->orderBy('retur.nomor_retur', 'ASC'); 

        $retur = $query->paginate($perPage, 'default', $page);

        foreach ($retur as &$rtr) {
            $rtr['tgl_retur'] = $this->formatDate($rtr['tgl_retur']);
        }

        return $retur;
    }

    public function getDetailWithStock($retur_id)
{
    $db = \Config\Database::connect();
    return $db->table('detail_retur')
        ->select('
            detail_retur.*,
            stok_bahan.kode_stok,
            stok_bahan.nama_barang,
            satuan.jenis_satuan
        ')
        ->join('stok_bahan', 'stok_bahan.stok_id = detail_retur.stok_id', 'left')
        ->join('satuan', 'satuan.satuan_id = stok_bahan.satuan_id', 'left')
        ->join('retur', 'retur.retur_id = detail_retur.retur_id', 'left')
        ->where('detail_retur.retur_id', $retur_id)
        ->get()
        ->getResultArray();
}
}