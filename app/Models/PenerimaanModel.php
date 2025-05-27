<?php

namespace App\Models;

use CodeIgniter\Model;

class PenerimaanModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'penerimaan_pembelian';
    protected $primaryKey       = 'terima_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nomor_bon',
        'tgl_terima',
        'supplier_id',
        'po_id',
        'nomor_faktur',
        'surat_jalan', 
        'tgl_kirim',
        'tgl_jatuh_tempo',
        'admin_terima',
        'total_qty_terima',
        'total_beli_terima',
        'ppn_terima',
        'total_beli_terima_ppn',
    ];

    public function formatDate($date)
    {
        return date('d-m-Y', strtotime($date));
    }

    public function generateNomorBon($tanggalTerima)
    {
        $date = date('ym', strtotime($tanggalTerima));
        $lastBon = $this->like('nomor_bon', "BM$date-", 'after')
                        ->orderBy('nomor_bon', 'DESC')
                        ->first();

        $nextNumber = 1;
        if ($lastBon) {
            $lastNumber = intval(substr($lastBon['nomor_bon'], -3));
            $nextNumber = $lastNumber + 1;
        }

        return 'BM' . $date . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    public function getPenerimaan($search = null, $perPage = 10, $page = 1, $startDate = null, $endDate = null, $filterType = null)
    { 
        $query = $this->select('penerimaan_pembelian.*, supplier.kode_supplier, supplier.nama_supplier, purchase_order.nomor_po')
            ->join('supplier', 'supplier.supplier_id = penerimaan_pembelian.supplier_id', 'left')
            ->join('purchase_order', 'purchase_order.po_id = penerimaan_pembelian.po_id', 'left');

        if ($search) {
            $query->groupStart()
                ->like('penerimaan_pembelian.nomor_bon', $search)
                ->orLike('supplier.nama_supplier', $search)
                ->groupEnd();
        }

        if ($filterType === 'tgl_terima' && $startDate && $endDate) {
            $query->where('penerimaan_pembelian.tgl_terima >=', $startDate)
                ->where('penerimaan_pembelian.tgl_terima <=', $endDate);
        }

        $query->orderBy('penerimaan_pembelian.nomor_bon', 'ASC');

        $penerimaanPembelian = $query->paginate($perPage, 'default', $page);

        foreach ($penerimaanPembelian as &$penerimaan) {
            $penerimaan['tgl_terima'] = $this->formatDate($penerimaan['tgl_terima']);
            $penerimaan['tgl_kirim'] = $this->formatDate($penerimaan['tgl_kirim']);
            $penerimaan['tgl_jatuh_tempo'] = $this->formatDate($penerimaan['tgl_jatuh_tempo']);
        }

        return $penerimaanPembelian;
    }

    public function getDetailWithStock($terima_id)
    {
        $db = \Config\Database::connect();
        return $db->table('detail_penerimaan')
            ->select('detail_penerimaan.*, stok_bahan.kode_stok, stok_bahan.nama_barang, stok_bahan.harga_beli, satuan.jenis_satuan')
            ->join('stok_bahan', 'stok_bahan.stok_id = detail_penerimaan.stok_id', 'left')
            ->join('satuan', 'satuan.satuan_id = stok_bahan.satuan_id', 'left')
            ->where('detail_penerimaan.terima_id', $terima_id)
            ->get()
            ->getResultArray();
    }

    public function getPOBySupplier($supplierId)
    {
        $purchaseOrderModel = new \App\Models\PurchaseOrderModel();
        // Mengambil PO berdasarkan supplier_id
        return $purchaseOrderModel->where('supplier_id', $supplierId)->findAll();
    }

    public function updateTotals($terima_id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('detail_penerimaan');

        $totals = $builder->select('SUM(qty_det_terima) AS total_qty_terima, SUM(jumlah_det_terima) AS total_beli_terima')
            ->where('terima_id', $terima_id)
            ->get()
            ->getRowArray();

        if ($totals) {
            $this->update($terima_id, [
                'total_qty_terima' => $totals['total_qty_terima'] ?? 0,
                'total_beli_terima' => $totals['total_beli_terima'] ?? 0,
            ]);
        }
    }
}