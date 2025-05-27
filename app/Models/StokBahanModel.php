<?php

namespace App\Models;

use CodeIgniter\Model;

class StokBahanModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'stok_bahan';
    protected $primaryKey       = 'stok_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'kode_stok',
        'grup_id',
        'bahan_id',
        'warna_id', 
        'satuan_id',
        'harga_beli',
        'harga_per_pcs',
        'last_received',
        'supplier_id',
        'nama_barang'
    ];

    public function generateKode($kode_grup, $kode_bahan)
    {
        $latestData = $this
                ->select('stok_bahan.kode_stok')
                ->where('grup.kode_grup', $kode_grup) 
                ->where('bahan.kode_bahan', $kode_bahan)
                ->join('grup', 'grup.grup_id = stok_bahan.grup_id', 'left')
                ->join('bahan', 'bahan.bahan_id = stok_bahan.bahan_id', 'left')
                ->orderBy('stok_bahan.kode_stok', 'DESC') 
                ->first();

        if (!$latestData) {
            return strtoupper($kode_grup . $kode_bahan . '0001');
        } else {
            $lastKode = $latestData['kode_stok'];
            $lastNumber = (int) substr($latestData['kode_stok'], -4);
            $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        }

        return "{$kode_grup}{$kode_bahan}{$nextNumber}";
    }

    public function getStokBahan($search = null, $perPage = 10, $page = 1)
    {
        $query = $this->select('stok_bahan.*, grup.nama_grup, bahan.nama_bahan, warna.jenis_warna, satuan.jenis_satuan, supplier.kode_supplier, supplier.nama_supplier')
            ->join('grup', 'grup.grup_id = stok_bahan.grup_id', 'left')
            ->join('bahan', 'bahan.bahan_id = stok_bahan.bahan_id', 'left')
            ->join('warna', 'warna.warna_id = stok_bahan.warna_id', 'left')
            ->join('satuan', 'satuan.satuan_id = stok_bahan.satuan_id', 'left')
            ->join('unit_satuan', 'unit_satuan.unit_id = satuan.unit_id', 'left') 
            ->join('supplier', 'supplier.supplier_id = stok_bahan.supplier_id', 'left');

        if ($search) {
            $query->groupStart()
                ->like('stok_bahan.kode_stok', $search)
                ->orLike('grup.nama_grup', $search)
                ->orLike('bahan.nama_bahan', $search)
                ->orLike('warna.jenis_warna', $search)
                ->orLike('satuan.jenis_satuan', $search)
                ->orLike('supplier.nama_supplier', $search)
                ->orLike('stok_bahan.nama_barang', $search)
                ->groupEnd();
        }
        
        return $query->paginate($perPage, 'default', $page);
    }

    public function getStokWithPenerimaan($search = null)
    {
        $builder = $this->db->table('stok_bahan sb')
            ->select("
                sb.stok_id,
                sb.kode_stok,
                sb.nama_barang,
                g.nama_grup,
                b.nama_bahan,
                w.jenis_warna,
                s.nama_supplier,
                sb.harga_beli,
                satuan.jenis_satuan,

                (
                    /* Total beli = penerimaan terbaru + semua qty dari penerimaan lama */
                    COALESCE((
                        SELECT SUM(dp.qty_det_terima)
                        FROM detail_penerimaan dp
                        JOIN penerimaan_pembelian pp ON pp.terima_id = dp.terima_id
                        WHERE dp.stok_id = sb.stok_id
                        AND CAST(SUBSTRING_INDEX(pp.nomor_bon, '-', -1) AS UNSIGNED) = (
                            SELECT MAX(CAST(SUBSTRING_INDEX(pp2.nomor_bon, '-', -1) AS UNSIGNED))
                            FROM penerimaan_pembelian pp2
                            JOIN detail_penerimaan dp2 ON dp2.terima_id = pp2.terima_id
                            WHERE dp2.stok_id = sb.stok_id
                        )
                    ), 0)

                    +

                    COALESCE((
                        SELECT SUM(lp.qty_lotrol_terima)
                        FROM lotrol_penerimaan lp
                        JOIN detail_penerimaan dp ON lp.det_terima_id = dp.det_terima_id
                        JOIN penerimaan_pembelian pp ON dp.terima_id = pp.terima_id
                        WHERE dp.stok_id = sb.stok_id
                        AND CAST(SUBSTRING_INDEX(pp.nomor_bon, '-', -1) AS UNSIGNED) < (
                            SELECT MAX(CAST(SUBSTRING_INDEX(pp2.nomor_bon, '-', -1) AS UNSIGNED))
                            FROM detail_penerimaan dp2
                            JOIN penerimaan_pembelian pp2 ON dp2.terima_id = pp2.terima_id
                            WHERE dp2.stok_id = sb.stok_id
                        )
                    ), 0)
                ) AS beli,

                /* Retur dari penerimaan terbaru */
                (
                    SELECT SUM(lr.qty_lotrol_retur) 
                    FROM lotrol_retur lr 
                    JOIN lotrol_penerimaan l ON lr.lot_retur = l.lot_terima AND lr.rol_retur = l.rol_terima 
                    JOIN detail_penerimaan dp4 ON l.det_terima_id = dp4.det_terima_id 
                    JOIN penerimaan_pembelian r4 ON r4.terima_id = dp4.terima_id 
                    WHERE dp4.stok_id = sb.stok_id 
                    AND CAST(SUBSTRING_INDEX(r4.nomor_bon, '-', -1) AS UNSIGNED) = 
                        (SELECT MAX(CAST(SUBSTRING_INDEX(r5.nomor_bon, '-', -1) AS UNSIGNED)) 
                            FROM detail_penerimaan dp5 
                            JOIN penerimaan_pembelian r5 ON r5.terima_id = dp5.terima_id 
                            WHERE dp5.stok_id = sb.stok_id)
                ) AS retur,

                /* Pemakaian dari penerimaan terbaru */
                (
                    SELECT SUM(lp.qty_lotrol_pakai) 
                    FROM lotrol_pemakaian lp 
                    JOIN lotrol_penerimaan l ON lp.lot_pakai = l.lot_terima AND lp.rol_pakai = l.rol_terima 
                    JOIN detail_penerimaan dp6 ON l.det_terima_id = dp6.det_terima_id 
                    JOIN penerimaan_pembelian r6 ON r6.terima_id = dp6.terima_id 
                    WHERE dp6.stok_id = sb.stok_id 
                    AND CAST(SUBSTRING_INDEX(r6.nomor_bon, '-', -1) AS UNSIGNED) = 
                        (SELECT MAX(CAST(SUBSTRING_INDEX(r7.nomor_bon, '-', -1) AS UNSIGNED)) 
                            FROM detail_penerimaan dp7 
                            JOIN penerimaan_pembelian r7 ON r7.terima_id = dp7.terima_id 
                            WHERE dp7.stok_id = sb.stok_id)
                ) AS pakai,

                COALESCE(MAX(r.tgl_terima), sb.last_received) AS last_received
            ")
            ->join('grup g', 'g.grup_id = sb.grup_id', 'left')
            ->join('bahan b', 'b.bahan_id = sb.bahan_id', 'left')
            ->join('warna w', 'w.warna_id = sb.warna_id', 'left')
            ->join('supplier s', 's.supplier_id = sb.supplier_id', 'left')
            ->join('satuan', 'satuan.satuan_id = sb.satuan_id', 'left')
            ->join('detail_penerimaan dp', 'dp.stok_id = sb.stok_id', 'left')
            ->join('penerimaan_pembelian r', 'r.terima_id = dp.terima_id', 'left')
            ->groupBy('sb.stok_id');

        if ($search) {
            $builder->groupStart()
                ->like('sb.nama_barang', $search)
                ->orLike('sb.kode_stok', $search)
                ->groupEnd();
        }

        return $builder->get()->getResultArray();
    }

    public function getDetailStokRows($search = null)
    {
        $builder = $this->db->table('stok_bahan sb')
            ->select('
                sb.stok_id,
                sb.kode_stok,
                sb.nama_barang,
                sb.harga_beli,
                satuan.jenis_satuan,
                po.nomor_po,
                r.tgl_terima,
                l.lot_terima,
                l.rol_terima,
                \'\' AS awal,
                COALESCE(l.qty_lotrol_terima, 0) AS beli,

                (
                    SELECT SUM(lr.qty_lotrol_retur)
                    FROM lotrol_retur lr
                    WHERE lr.lot_terima_asal = l.lot_terima 
                    AND lr.rol_terima_asal = l.rol_terima
                ) AS retur,

                (
                    SELECT SUM(lp.qty_lotrol_pakai)
                    FROM lotrol_pemakaian lp
                    WHERE lp.lot_terima_asal = l.lot_terima 
                    AND lp.rol_terima_asal = l.rol_terima
                ) AS pakai
            ')
            ->join('detail_penerimaan dp', 'dp.stok_id = sb.stok_id', 'left')
            ->join('penerimaan_pembelian r', 'r.terima_id = dp.terima_id', 'left')
            ->join('purchase_order po', 'po.po_id = r.po_id', 'left')
            ->join('lotrol_penerimaan l', 'l.det_terima_id = dp.det_terima_id', 'left')
            ->join('satuan', 'satuan.satuan_id = sb.satuan_id', 'left')
            ->where('po.nomor_po IS NOT NULL');

        if ($search) {
            $builder->groupStart()
                ->like('sb.kode_stok', $search)
                ->orLike('sb.nama_barang', $search)
                ->orLike('po.nomor_po', $search)
                ->orLike('r.tgl_terima', $search)
                ->orLike('l.lot_terima', $search)
                ->orLike('l.rol_terima', $search)
                ->groupEnd();
        }

        return $builder->get()->getResultArray();
    }

    public function getStokBySupplier($supplier_id)
    {
        return $this->where('supplier_id', $supplier_id)->findAll();
    }

    public function getStokByPO($po_id)
    {
        return $this->db->table('detail_po')
            ->select('stok_bahan.stok_id, stok_bahan.kode_stok, stok_bahan.nama_barang, stok_bahan.harga_beli, detail_po.qty_detpo')
            ->join('stok_bahan', 'stok_bahan.stok_id = detail_po.stok_id', 'left')
            ->where('detail_po.po_id', $po_id)
            ->groupBy('stok_bahan.stok_id')
            ->get()
            ->getResultArray();
    }   

    public function getBarangHampirHabis()
    {
        $builder = $this->db->table('stok_bahan sb');
        $builder->select('
            sb.stok_id,
            sb.kode_stok,
            sb.nama_barang,
            COALESCE(SUM(lp.qty_lotrol_terima), 0) AS qty_diterima,
            COALESCE(SUM(lpem.qty_lotrol_pakai), 0) AS qty_pemakaian,
            COALESCE(SUM(lrr.qty_lotrol_retur), 0) AS qty_retur,
            (COALESCE(SUM(lp.qty_lotrol_terima), 0) - COALESCE(SUM(lpem.qty_lotrol_pakai), 0) - COALESCE(SUM(lrr.qty_lotrol_retur), 0)) AS sisa
        '); 
        $builder->join('detail_penerimaan dp', 'dp.stok_id = sb.stok_id', 'left');
        $builder->join('lotrol_penerimaan lp', 'lp.det_terima_id = dp.det_terima_id', 'left');
        $builder->join('lotrol_pemakaian lpem', 'lpem.rol_terima_asal = lp.rol_terima AND lpem.lot_terima_asal = lp.lot_terima', 'left');
        $builder->join('lotrol_retur lrr', 'lrr.rol_terima_asal = lp.rol_terima AND lrr.lot_terima_asal = lp.lot_terima', 'left');
        $builder->groupBy('sb.stok_id, sb.kode_stok, sb.nama_barang');
        $builder->having('sisa <=', 50); // stok hampir habis
        $builder->having('qty_diterima >', 0); // hanya yang punya penerimaan (beli > 0)
        $builder->orderBy('sb.kode_stok', 'ASC');

        $query = $builder->get();
        return $query->getResultArray();
    }
}