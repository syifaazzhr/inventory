<?php

namespace App\Models;

use CodeIgniter\Model;

class PemakaianModel extends Model
{
    protected $table            = 'pemakaian';
    protected $primaryKey       = 'pakai_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'nomor_pakai',
        'tgl_keluar',
        'nomor_ws_warna_pakai',
        'nomor_ws_pakai',
        'nomor_art_pakai',
        'nama_penerima',
        'ket_pakai',
        'admin_pakai',
        'total_qty_pakai',
        'lotrol_terima_id'
    ];

    public function formatDate($date)
    {
        return date('d-m-Y', strtotime($date));
    }

    public function generateNomorPakai($tgl_keluar, $jenis_bahan) 
    {
        $prefixJenis = $jenis_bahan === 'Aksesoris' ? 'PB' : 'PH';
        $date = date('ym', strtotime($tgl_keluar));
        $prefix = $prefixJenis . $date . '-';

        $last = $this->like('nomor_pakai', $prefix, 'after')
                    ->orderBy('nomor_pakai', 'DESC')
                    ->first();

        $next = 1;
        if ($last) {
            $next = intval(substr($last['nomor_pakai'], -3)) + 1;
        }

        return $prefix . str_pad($next, 3, '0', STR_PAD_LEFT);
    }

    public function getPemakaian($search = null, $perPage = 10, $page = 1, $startDate = null, $endDate = null, $filterType = null)
    { 
        $query = $this->select('pemakaian.*');

        if ($search) {
            $query->groupStart()
                ->like('pemakaian.nomor_pakai', $search)
                ->orLike('purchase_order.nomor_po', $search)
                ->groupEnd();
        }

        if ($filterType === 'tgl_keluar' && $startDate && $endDate) {
            $query->where('pemakaian.tgl_keluar >=', $startDate)
                ->where('pemakaian.tgl_keluar <=', $endDate);
        } 
 
        $query->orderBy('pemakaian.nomor_pakai', 'ASC'); 

        $pemakaian = $query->paginate($perPage, 'default', $page);

        foreach ($pemakaian as &$rtr) {
            $rtr['tgl_keluar'] = $this->formatDate($rtr['tgl_keluar']);
        }

        return $pemakaian;
    }

    public function getDetailWithStock($pakai_id)
    {
        $db = \Config\Database::connect();
        return $db->table('detail_pemakaian')
            ->select('detail_pemakaian.*, stok_bahan.kode_stok, stok_bahan.nama_barang, satuan.jenis_satuan')
            ->join('stok_bahan', 'stok_bahan.stok_id = detail_pemakaian.stok_id', 'left')
            ->join('satuan', 'satuan.satuan_id = stok_bahan.satuan_id', 'left')
            ->where('detail_pemakaian.pakai_id', $pakai_id)
            ->get()
            ->getResultArray();
    }
}