<?php

namespace App\Models;

use CodeIgniter\Model;

class PurchaseOrderModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'purchase_order';
    protected $primaryKey       = 'po_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nomor_po',
        'tgl_order',
        'max_tgl_kirim',
        'nomor_ws',
        'nomor_art',
        'supplier_id',
        'ket_po',
        'admin_po',
        'total_qty',
        'jml_total_po',
        'ppn_po',
        'jml_total_po_ppn'
    ];

    public function formatDate($date)
    {
        return date('d-m-Y', strtotime($date));
    }

    public function generateNomorPO($tanggalOrder)
    {
        // Ubah format tanggal yang diterima (YYYY-MM-DD) menjadi (YYMM)
        $date = date('ym', strtotime($tanggalOrder)); // 'ym' → 2 digit tahun + 2 digit bulan

        // Ambil PO terakhir yang memiliki format yang sama (misalnya PO2502-XXX)
        $lastOrder = $this->like('nomor_po', "PO$date-", 'after')
                        ->orderBy('nomor_po', 'DESC')
                        ->first();

        // Tentukan nomor urut PO berikutnya
        $nextNumber = 1;
        if ($lastOrder) {
            $lastNumber = intval(substr($lastOrder['nomor_po'], -3)); // Ambil tiga digit terakhir
            $nextNumber = $lastNumber + 1;
        }

        // Format akhir: POYYMM-XXX 
        return 'PO' . $date . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }     

    public function getPurchaseOrders($search = null, $perPage = 10, $page = 1, $startDate = null, $endDate = null, $filterType = null)
    {
        $query = $this->select('purchase_order.*, supplier.kode_supplier, supplier.nama_supplier')
            ->join('supplier', 'supplier.supplier_id = purchase_order.supplier_id', 'left');
 
        if ($search) {
            $query->groupStart()
                ->like('purchase_order.nomor_po', $search)
                ->orLike('supplier.nama_supplier', $search)
                ->groupEnd();
        }

        if ($filterType === 'tgl_order' && $startDate && $endDate) {
            $query->where('purchase_order.tgl_order >=', $startDate)
                ->where('purchase_order.tgl_order <=', $endDate);
        } else if ($filterType === 'max_tgl_kirim' && $startDate && $endDate) {
            $query->where('purchase_order.max_tgl_kirim >=', $startDate)
                ->where('purchase_order.max_tgl_kirim <=', $endDate);
        }

        $query->orderBy('purchase_order.nomor_po', 'ASC');

        $purchaseOrders = $query->paginate($perPage, 'default', $page);

        foreach ($purchaseOrders as &$po) {
            $po['tgl_order'] = $this->formatDate($po['tgl_order']);
            $po['max_tgl_kirim'] = $this->formatDate($po['max_tgl_kirim']);
        }

        return $purchaseOrders;
    }

    public function getDetailWithStock($po_id)
    {
        $db = \Config\Database::connect();
        return $db->table('detail_po')
            ->select('detail_po.*, stok_bahan.kode_stok, stok_bahan.nama_barang, stok_bahan.harga_beli, satuan.jenis_satuan')
            ->join('stok_bahan', 'stok_bahan.stok_id = detail_po.stok_id', 'left')
            ->join('satuan', 'satuan.satuan_id = stok_bahan.satuan_id', 'left')
            ->where('detail_po.po_id', $po_id)
            ->get()
            ->getResultArray();
    } 

    // Mengupdate total_qty dan jml_total_po berdasarkan detail_po
    public function updateTotals($po_id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('detail_po');

        // Hitung total_qty dan jumlah_total
        $totals = $builder->select('SUM(qty_detpo) AS total_qty, SUM(jumlah_detpo) AS jml_total_po')
            ->where('po_id', $po_id)
            ->get()
            ->getRowArray();

        if ($totals) {
            $this->update($po_id, [
                'total_qty' => $totals['total_qty'] ?? 0,
                'jml_total_po' => $totals['jml_total_po'] ?? 0,
            ]);
        }
    }
}