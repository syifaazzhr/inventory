<?php

namespace App\Controllers\Pemakaian;

use App\Models\LotRolPemakaianModel;
use App\Models\StokBahanModel;
use App\Models\PurchaseOrderModel;
use App\Models\PemakaianModel;
use CodeIgniter\Controller;

class LotRolPemakaianController extends Controller
{
    protected $db;
    protected $lotRolPemakaianModel;
    protected $stokBahanModel;
    protected $purchaseOrderModel;
    protected $pemakaianModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect(); 
        $this->lotRolPemakaianModel = new LotRolPemakaianModel();
        $this->stokBahanModel = new StokBahanModel();
        $this->purchaseOrderModel = new PurchaseOrderModel();
        $this->pemakaianModel = new PemakaianModel();
    }

    public function index()
    {
        $search = $this->request->getVar('search');
        $lotRolData = $this->lotRolPemakaianModel->getLotRol($search);

        return view('pemakaian/lot_rol/index', [
            'lot_rol_data' => $lotRolData,
            'search' => $search
        ]);
    }

    public function show($pakai_id, $det_pakai_id)
    {
        $pemakaianModel = new \App\Models\PemakaianModel();
        $detailPemakaianModel = new \App\Models\DetailPemakaianModel();
        $stokBahanModel = new \App\Models\StokBahanModel();
        $purchaseOrderModel = new \App\Models\PurchaseOrderModel();
        $lotRolPenerimaanModel = new \App\Models\LotRolPenerimaanModel();
        $lotRolPemakaianModel = new \App\Models\LotRolPemakaianModel();
        $lotRolReturModel = new \App\Models\LotRolReturModel();

        $pemakaian = $pemakaianModel->find($pakai_id);
        $detailPemakaian = $detailPemakaianModel->getDetailWithStock($pakai_id);
        $detailItem = null;
        foreach ($detailPemakaian as $item) {
            if ($item['det_pakai_id'] == $det_pakai_id) {
                $detailItem = $item;
                break;
            }
        }

        if (!$detailItem) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Detail pemakaian bahan tidak ditemukan.');
        }

        $stok_id = $detailItem['stok_id'] ?? null;
        $totalPermintaanPemakaian = (float) ($detailItem['qty_det_pakai'] ?? 0);

       $hasilFIFO = $this->db->query("
            SELECT 
            lp.lot_terima AS lot,
            lp.rol_terima AS rol,
            po.nomor_po AS nomor_po_asal,
            pp.nomor_bon,
            (lp.qty_lotrol_terima 
                - COALESCE((
                    SELECT SUM(lpem.qty_lotrol_pakai) 
                    FROM lotrol_pemakaian lpem 
                    WHERE lpem.lot_terima_asal = lp.lot_terima 
                    AND lpem.rol_terima_asal = lp.rol_terima
                ), 0)
                - COALESCE((
                    SELECT SUM(lr.qty_lotrol_retur) 
                    FROM lotrol_retur lr 
                    WHERE lr.lot_terima_asal = lp.lot_terima 
                    AND lr.rol_terima_asal = lp.rol_terima
                ), 0)
            ) AS sisa_qty
        FROM lotrol_penerimaan lp
        JOIN detail_penerimaan dp ON dp.det_terima_id = lp.det_terima_id
        JOIN penerimaan_pembelian pp ON pp.terima_id = dp.terima_id
        LEFT JOIN purchase_order po ON po.po_id = pp.po_id
        WHERE dp.stok_id = ?
        AND (
            (lp.qty_lotrol_terima 
                - COALESCE((
                    SELECT SUM(lpem.qty_lotrol_pakai) 
                    FROM lotrol_pemakaian lpem 
                    WHERE lpem.lot_terima_asal = lp.lot_terima 
                    AND lpem.rol_terima_asal = lp.rol_terima
                ), 0)
                - COALESCE((
                    SELECT SUM(lr.qty_lotrol_retur) 
                    FROM lotrol_retur lr 
                    WHERE lr.lot_terima_asal = lp.lot_terima 
                    AND lr.rol_terima_asal = lp.rol_terima
                ), 0)
            ) > 0
            OR pp.nomor_bon = (
                SELECT MAX(pp2.nomor_bon)
                FROM penerimaan_pembelian pp2
                JOIN detail_penerimaan dp2 ON dp2.terima_id = pp2.terima_id
                WHERE dp2.stok_id = ?
            )
        )
        AND NOT EXISTS (
            SELECT 1
            FROM lotrol_retur lr
            WHERE lr.lot_terima_asal = lp.lot_terima 
            AND lr.rol_terima_asal = lp.rol_terima
            GROUP BY lr.lot_terima_asal, lr.rol_terima_asal
            HAVING SUM(lr.qty_lotrol_retur) = lp.qty_lotrol_terima
        )
        ORDER BY 
            pp.nomor_bon ASC, 
            lp.lot_terima ASC, 
            CAST(SUBSTRING(lp.rol_terima, 2) AS UNSIGNED) ASC
        ", [$stok_id, $stok_id])->getResultArray();

        $alokasiFIFO = [];
        foreach ($hasilFIFO as $row) {
            if ($totalPermintaanPemakaian <= 0) break;

            $ambil = min($totalPermintaanPemakaian, $row['sisa_qty']);
            $alokasiFIFO[] = [
                'lot' => $row['lot'],
                'rol' => $row['rol'],
                'nomor_po_asal' => $row['nomor_po_asal'],
                'qty_alokasi' => $ambil,
            ];

            $totalPermintaanPemakaian -= $ambil;
        }

        $latestBon = $this->db->query("
            SELECT MAX(CAST(SUBSTRING_INDEX(pp.nomor_bon, '-', -1) AS UNSIGNED)) AS last_bon
            FROM penerimaan_pembelian pp
            JOIN detail_penerimaan dp ON dp.terima_id = pp.terima_id
            WHERE dp.stok_id = ?
        ", [$stok_id])->getRowArray();    
        
        $lastBonNumber = $latestBon['last_bon'] ?? 0;    
        $lotrol_penerimaan_all = $lotRolPenerimaanModel->findAll();
        $lotrol_pemakaian_all = $lotRolPemakaianModel->findAll();
        $lotrol_retur_all = $lotRolReturModel->findAll();

        $lotrol_penerimaan_filtered = array_filter($lotrol_penerimaan_all, function ($lot) use ($detailPemakaianModel, $stok_id, $lotrol_pemakaian_all, $lotrol_retur_all) {
            if (!isset($lot['lot_terima']) || !isset($lot['rol_terima']) || !isset($lot['det_terima_id'])) {
                return false; 
            }

            $detail = $detailPemakaianModel
                ->db
                ->table('detail_penerimaan')
                ->where('det_terima_id', $lot['det_terima_id'])
                ->where('stok_id', $stok_id)
                ->get()
                ->getRowArray();

            if (!$detail) return false;

            foreach ($lotrol_retur_all as $pemakaian) {
                if (isset($pemakaian['lot_terima'], $pemakaian['rol_terima']) &&
                    $pemakaian['lot_terima'] == $lot['lot_terima'] &&
                    $pemakaian['rol_terima'] == $lot['rol_terima']) {
                    return false;
                }
            }

            foreach ($lotrol_pemakaian_all as $pemakaian) {
                if (isset($pemakaian['lot_pakai'], $pemakaian['rol_pakai']) &&
                    $pemakaian['lot_pakai'] == $lot['lot_terima'] &&
                    $pemakaian['rol_pakai'] == $lot['rol_terima']) {
                    return false;
                }
            }

            return true;
        });

        foreach ($lotrol_penerimaan_filtered as &$lotrol) {
            $lotrol['nomor_po'] = $this->getNomorPoByDetTerimaId($lotrol['det_terima_id']);
        }        

        foreach ($detailPemakaian as $item) {
            if ($item['det_pakai_id'] == $det_pakai_id) {
                $detailItem = $item;
                break;
            }
        }

        $lotrol_pemakaian = $lotRolPemakaianModel
            ->where('pakai_id', $pakai_id)
            ->where('det_pakai_id', $det_pakai_id)
            ->findAll();

            $nomor_po = '-';
            if ($detailItem && isset($detailItem['po_id'])) {
                $po = $purchaseOrderModel->find($detailItem['po_id']);
                $nomor_po = $detailItem['nomor_po'] ?? '-';
            }   
            
            helper('text');

            $lotrol_penerimaan_filtered = $this->db->query("
                SELECT 
                lp.lot_terima AS lot_asal,
                lp.rol_terima AS rol_asal,
                po.nomor_po,
                po.po_id,
                pp.nomor_bon,
                lp.qty_lotrol_terima AS qty_diterima,
                COALESCE((
                    SELECT SUM(lpem.qty_lotrol_pakai) 
                    FROM lotrol_pemakaian lpem 
                    WHERE lpem.lot_terima_asal = lp.lot_terima 
                    AND lpem.rol_terima_asal = lp.rol_terima
                ), 0) AS qty_pemakaian,
                COALESCE((
                    SELECT SUM(lr.qty_lotrol_retur) 
                    FROM lotrol_retur lr 
                    WHERE lr.lot_terima_asal = lp.lot_terima 
                    AND lr.rol_terima_asal = lp.rol_terima
                ), 0) AS qty_retur,
                (lp.qty_lotrol_terima 
                    - COALESCE((
                        SELECT SUM(lpem.qty_lotrol_pakai) 
                        FROM lotrol_pemakaian lpem 
                        WHERE lpem.lot_terima_asal = lp.lot_terima 
                        AND lpem.rol_terima_asal = lp.rol_terima
                    ), 0)
                    - COALESCE((
                        SELECT SUM(lr.qty_lotrol_retur) 
                        FROM lotrol_retur lr 
                        WHERE lr.lot_terima_asal = lp.lot_terima 
                        AND lr.rol_terima_asal = lp.rol_terima
                    ), 0)
                ) AS sisa_kuantitas,
                CASE 
                    WHEN (COALESCE((
                        SELECT SUM(lpem.qty_lotrol_pakai) 
                        FROM lotrol_pemakaian lpem 
                        WHERE lpem.lot_terima_asal = lp.lot_terima 
                        AND lpem.rol_terima_asal = lp.rol_terima
                    ), 0) + COALESCE((
                        SELECT SUM(lr.qty_lotrol_retur) 
                        FROM lotrol_retur lr 
                        WHERE lr.lot_terima_asal = lp.lot_terima 
                        AND lr.rol_terima_asal = lp.rol_terima
                    ), 0)) >= lp.qty_lotrol_terima 
                    THEN 'Sudah Dipakai & Diretur (Penuh)'
                    WHEN COALESCE((
                        SELECT SUM(lpem.qty_lotrol_pakai) 
                        FROM lotrol_pemakaian lpem 
                        WHERE lpem.lot_terima_asal = lp.lot_terima 
                        AND lpem.rol_terima_asal = lp.rol_terima
                    ), 0) > 0 
                    AND COALESCE((
                        SELECT SUM(lr.qty_lotrol_retur) 
                        FROM lotrol_retur lr 
                        WHERE lr.lot_terima_asal = lp.lot_terima 
                        AND lr.rol_terima_asal = lp.rol_terima
                    ), 0) > 0 
                    THEN 'Sudah Dipakai & Diretur (Sebagian)'
                    WHEN COALESCE((
                        SELECT SUM(lpem.qty_lotrol_pakai) 
                        FROM lotrol_pemakaian lpem 
                        WHERE lpem.lot_terima_asal = lp.lot_terima 
                        AND lpem.rol_terima_asal = lp.rol_terima
                    ), 0) > 0 
                    THEN CASE 
                            WHEN COALESCE((
                                SELECT SUM(lpem.qty_lotrol_pakai) 
                                FROM lotrol_pemakaian lpem 
                                WHERE lpem.lot_terima_asal = lp.lot_terima 
                                AND lpem.rol_terima_asal = lp.rol_terima
                            ), 0) = lp.qty_lotrol_terima 
                            THEN 'Sudah Dipakai (Penuh)'
                            ELSE 'Sudah Dipakai (Sebagian)'
                        END
                    WHEN COALESCE((
                        SELECT SUM(lr.qty_lotrol_retur) 
                        FROM lotrol_retur lr 
                        WHERE lr.lot_terima_asal = lp.lot_terima 
                        AND lr.rol_terima_asal = lp.rol_terima
                    ), 0) > 0 
                    THEN CASE 
                            WHEN COALESCE((
                                SELECT SUM(lr.qty_lotrol_retur) 
                                FROM lotrol_retur lr 
                                WHERE lr.lot_terima_asal = lp.lot_terima 
                                AND lr.rol_terima_asal = lp.rol_terima
                            ), 0) = lp.qty_lotrol_terima 
                            THEN 'Sudah Diretur (Penuh)'
                            ELSE 'Sudah Diretur (Sebagian)'
                        END
                    ELSE 'Belum Dipakai/Diretur'
                END AS status_transaksi
            FROM lotrol_penerimaan lp
            JOIN detail_penerimaan dp ON dp.det_terima_id = lp.det_terima_id
            JOIN penerimaan_pembelian pp ON pp.terima_id = dp.terima_id
            LEFT JOIN purchase_order po ON po.po_id = pp.po_id
            WHERE dp.stok_id = ?
            AND (
                (
                    COALESCE((
                        SELECT SUM(lpem.qty_lotrol_pakai) 
                        FROM lotrol_pemakaian lpem 
                        WHERE lpem.lot_terima_asal = lp.lot_terima 
                        AND lpem.rol_terima_asal = lp.rol_terima
                    ), 0) = 0
                    AND COALESCE((
                        SELECT SUM(lr.qty_lotrol_retur) 
                        FROM lotrol_retur lr 
                        WHERE lr.lot_terima_asal = lp.lot_terima 
                        AND lr.rol_terima_asal = lp.rol_terima
                    ), 0) = 0
                    AND (lp.qty_lotrol_terima 
                        - COALESCE((
                            SELECT SUM(lpem.qty_lotrol_pakai) 
                            FROM lotrol_pemakaian lpem 
                            WHERE lpem.lot_terima_asal = lp.lot_terima 
                            AND lpem.rol_terima_asal = lp.rol_terima
                        ), 0)
                        - COALESCE((
                            SELECT SUM(lr.qty_lotrol_retur) 
                            FROM lotrol_retur lr 
                            WHERE lr.lot_terima_asal = lp.lot_terima 
                            AND lr.rol_terima_asal = lp.rol_terima
                        ), 0)
                    ) > 0
                )
                OR pp.nomor_bon = (
                    SELECT MAX(pp2.nomor_bon)
                    FROM penerimaan_pembelian pp2
                    JOIN detail_penerimaan dp2 ON dp2.terima_id = pp2.terima_id
                    WHERE dp2.stok_id = ?
                )
            )
            AND NOT EXISTS (
                SELECT 1
                FROM lotrol_retur lr
                WHERE lr.lot_terima_asal = lp.lot_terima 
                AND lr.rol_terima_asal = lp.rol_terima
                GROUP BY lr.lot_terima_asal, lr.rol_terima_asal
                HAVING SUM(lr.qty_lotrol_retur) = lp.qty_lotrol_terima
            )
            ORDER BY pp.nomor_bon ASC, lp.rol_terima ASC
            ", [$stok_id, $stok_id])->getResultArray();
                     
        return view('pemakaian/lot_rol/show', [
            'lotrol_penerimaan' => $lotrol_penerimaan_filtered, 
            'lotrol_pemakaian' => $lotrol_pemakaian,
            'pemakaian' => $pemakaian,
            'detail_pemakaian' => $detailItem,
            'pakai_id' => $pakai_id,
            'det_pakai_id' => $det_pakai_id,
            'nomor_po' => $nomor_po,
            'hasilFIFO' => $alokasiFIFO,
        ]);        
    }

    public function edit($id)
    {
        $lotRol = $this->lotRolPemakaianModel->find($id);
        if (!$lotRol) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Lot & Roll tidak ditemukan.');
        }

        $pemakaianList = $this->pemakaianModel->findAll();
        $stokList = $this->stokBahanModel->findAll();
        $pakai_id = $lotRol['pakai_id'];
        $det_pakai_id = $lotRol['det_pakai_id'];
        $pemakaian = $this->pemakaianModel->find($pakai_id);
        if (!$pemakaian || !isset($pemakaian['nomor_pakai'])) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Nomor pemakaian tidak ditemukan.');
        }

        return view('pemakaian/lot_rol/edit', [
            'lot_rol' => $lotRol,
            'pemakaian_list' => $pemakaianList,
            'stok_list' => $stokList,
            'pakai_id' => $pakai_id,  
            'det_pakai_id' => $det_pakai_id,
            'lotrol_id' => $id,
            'pemakaian' => $pemakaian,  
            'detail' => $detail,
            'lotrol_id' => 0,
            'po_id' => $detail_pemakaian['po_id'] ?? null,
        ]);
    }

    public function update($id = null)
    {
        // dd($this->request->getPost());

        $rules = [
            'pakai_id' => 'required|integer',
            'det_pakai_id' => 'required|integer',
            'po_id' => 'required',
            'lot_pakai' => 'required',
            'rol_pakai' => 'required',
            'qty_lotrol_pakai' => 'required'
        ];

        if (!$this->validate($rules)) {
            dd($this->validator->getErrors());
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $this->db->transStart();

        $pakai_id = (int) $this->request->getPost('pakai_id');
        $det_pakai_id = (int) $this->request->getPost('det_pakai_id');
        $qty_lotrol_pakai = $this->request->getPost('qty_lotrol_pakai');

        $stokData = $this->db->table('detail_pemakaian')
            ->select('stok_id')
            ->where('det_pakai_id', $det_pakai_id)
            ->get()
            ->getRowArray();

        $stok_id = $stokData['stok_id'] ?? null;
        if (!$stok_id) {
            throw new \RuntimeException('stok_id tidak ditemukan. Periksa tabel detail_pemakaian.');
        }

        $latestPenerimaan = $this->db->query("
            SELECT pp.po_id, lp.lot_terima, lp.rol_terima 
            FROM penerimaan_pembelian pp
            JOIN detail_penerimaan dp ON dp.terima_id = pp.terima_id
            JOIN lotrol_penerimaan lp ON lp.det_terima_id = dp.det_terima_id
            WHERE dp.stok_id = ?
            ORDER BY CAST(SUBSTRING(pp.nomor_bon, LOCATE('-', pp.nomor_bon) + 1) AS UNSIGNED) DESC
            LIMIT 1
        ", [$stok_id])->getRowArray();

        if (!$latestPenerimaan) {
            throw new \RuntimeException('Tidak ada data penerimaan terbaru untuk stok_id: ' . $stok_id);
        }

        $latestPoId = $latestPenerimaan['po_id'];
        $latestLot = $latestPenerimaan['lot_terima'];
        $latestRol = $latestPenerimaan['rol_terima'];

        $this->lotRolPemakaianModel
            ->where('pakai_id', $pakai_id)
            ->where('det_pakai_id', $det_pakai_id)
            ->delete();

        foreach ($qty_lotrol_pakai as $index => $qtyStr) {
        $qty = floatval($qtyStr);
        if ($qty <= 0.00) continue;

        $lotPakai = is_array($this->request->getPost('lot_pakai')) 
                    ? $this->request->getPost('lot_pakai')[$index] 
                    : $this->request->getPost('lot_pakai'); 

        $rolPakai = is_array($this->request->getPost('rol_pakai')) 
                    ? $this->request->getPost('rol_pakai')[$index] 
                    : $this->request->getPost('rol_pakai');

        // ✅ Cari lotrol_terima_id dari lot_terima & rol_terima
        $lotrolPenerimaan = $this->db->table('lotrol_penerimaan')
            ->select('lotrol_terima_id')
            ->where('lot_terima', $lotPakai)
            ->where('rol_terima', $rolPakai)
            ->get()
            ->getRowArray();

        $lotrolTerimaId = $lotrolPenerimaan['lotrol_terima_id'] ?? null;

        // Mendapatkan PO asal berdasarkan lotrol_terima
        $poAsal = $this->db->table('lotrol_penerimaan')
            ->select('po.po_id, po.nomor_po')
            ->join('penerimaan_pembelian pp', 'pp.terima_id = lotrol_penerimaan.terima_id')
            ->join('purchase_order po', 'po.po_id = pp.po_id')
            ->where('lotrol_penerimaan.lot_terima', $lotPakai)  // lot_terima yang digunakan untuk pemakaian
            ->where('lotrol_penerimaan.rol_terima', $rolPakai)  // rol_terima yang digunakan untuk pemakaian
            ->get()
            ->getRowArray();

        // $po_id_asal = $poAsal['po_id'] ?? null;
        // $nomor_po_asal = $poAsal['nomor_po'] ?? '-';

        $data = [
            'pakai_id'         => $pakai_id,
            'det_pakai_id'     => $det_pakai_id,
            'po_id'            => $latestPoId, 
            // 'po_id_asal'       => $po_id_asal,
            'lot_pakai'        => $latestLot, 
            'lot_terima_asal'  => $lotPakai,    
            'rol_pakai'        => $latestRol,  
            'rol_terima_asal'  => $rolPakai,
            'qty_lotrol_pakai' => $qty,
            'lotrol_terima_id' => $lotrolTerimaId,
        ];

        $this->lotRolPemakaianModel->insert($data);
    }

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return redirect()->back()->with('msg', 'Gagal memperbarui data!')->with('error', true);
        }

        return redirect()->to(base_url('admin/pemakaian/lot_rol/' . $pakai_id . '/' . $det_pakai_id))
            ->with('msg', 'Data berhasil diperbarui!');
    }

    public function edit_by_pemakaian($pakai_id, $det_pakai_id)
    {
        $pemakaianModel = new \App\Models\PemakaianModel();
        $detailPemakaianModel = new \App\Models\DetailPemakaianModel();
        $stokModel = new \App\Models\StokBahanModel();

        $pemakaian = $pemakaianModel->find($pakai_id);
        $detail_pemakaian = $detailPemakaianModel->find($det_pakai_id);
        if (!$pemakaian || !$detail_pemakaian) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Data pemakaian tidak ditemukan.');
        }

        $stok_detail = $stokModel->find($detail_pemakaian['stok_id']);
        $stok_id = $detail_pemakaian['stok_id'];

        $lotrol_penerimaan_filtered = $this->db->query("
            SELECT 
                lp.lot_terima AS lot_asal,
                lp.rol_terima AS rol_asal,
                po.nomor_po,
                po.po_id,
                pp.nomor_bon,
                lp.qty_lotrol_terima AS qty_diterima,
                COALESCE((SELECT SUM(lpem.qty_lotrol_pakai) 
                        FROM lotrol_pemakaian lpem 
                        WHERE lpem.lot_terima_asal = lp.lot_terima 
                            AND lpem.rol_terima_asal = lp.rol_terima), 0) AS qty_pemakaian,
                COALESCE((SELECT SUM(lr.qty_lotrol_retur) 
                        FROM lotrol_retur lr 
                        WHERE lr.lot_terima_asal = lp.lot_terima 
                            AND lr.rol_terima_asal = lp.rol_terima), 0) AS qty_retur,
                (lp.qty_lotrol_terima 
                    - COALESCE((SELECT SUM(lpem.qty_lotrol_pakai) 
                                FROM lotrol_pemakaian lpem 
                                WHERE lpem.lot_terima_asal = lp.lot_terima 
                                AND lpem.rol_terima_asal = lp.rol_terima), 0)
                    - COALESCE((SELECT SUM(lr.qty_lotrol_retur) 
                                FROM lotrol_retur lr 
                                WHERE lr.lot_terima_asal = lp.lot_terima 
                                AND lr.rol_terima_asal = lp.rol_terima), 0)
                ) AS sisa_kuantitas,
                CASE 
                    WHEN COALESCE((SELECT SUM(lpem.qty_lotrol_pakai) 
                                FROM lotrol_pemakaian lpem 
                                WHERE lpem.lot_terima_asal = lp.lot_terima 
                                    AND lpem.rol_terima_asal = lp.rol_terima), 0) > 0 
                    AND COALESCE((SELECT SUM(lr.qty_lotrol_retur) 
                                FROM lotrol_retur lr 
                                WHERE lr.lot_terima_asal = lp.lot_terima 
                                    AND lr.rol_terima_asal = lp.rol_terima), 0) > 0 
                    THEN 'Sudah Dipakai & Diretur (Sebagian)'
                    WHEN COALESCE((SELECT SUM(lpem.qty_lotrol_pakai) 
                                FROM lotrol_pemakaian lpem 
                                WHERE lpem.lot_terima_asal = lp.lot_terima 
                                    AND lpem.rol_terima_asal = lp.rol_terima), 0) > 0 
                    THEN CASE 
                        WHEN COALESCE((SELECT SUM(lpem.qty_lotrol_pakai) 
                                    FROM lotrol_pemakaian lpem 
                                    WHERE lpem.lot_terima_asal = lp.lot_terima 
                                        AND lpem.rol_terima_asal = lp.rol_terima), 0) = lp.qty_lotrol_terima 
                        THEN 'Sudah Dipakai (Penuh)'
                        ELSE 'Sudah Dipakai (Sebagian)'
                    END
                    WHEN COALESCE((SELECT SUM(lr.qty_lotrol_retur) 
                                FROM lotrol_retur lr 
                                WHERE lr.lot_terima_asal = lp.lot_terima 
                                    AND lr.rol_terima_asal = lp.rol_terima), 0) > 0 
                    THEN CASE 
                        WHEN COALESCE((SELECT SUM(lr.qty_lotrol_retur) 
                                    FROM lotrol_retur lr 
                                    WHERE lr.lot_terima_asal = lp.lot_terima 
                                        AND lr.rol_terima_asal = lp.rol_terima), 0) = lp.qty_lotrol_terima 
                        THEN 'Sudah Diretur (Penuh)'
                        ELSE 'Sudah Diretur (Sebagian)'
                    END
                    ELSE 'Belum Dipakai/Diretur'
                END AS status_transaksi
            FROM lotrol_penerimaan lp
            JOIN detail_penerimaan dp ON dp.det_terima_id = lp.det_terima_id
            JOIN penerimaan_pembelian pp ON pp.terima_id = dp.terima_id
            LEFT JOIN purchase_order po ON po.po_id = pp.po_id
            WHERE dp.stok_id = ?
            HAVING sisa_kuantitas > 0 
            AND status_transaksi IN (
                    'Belum Dipakai/Diretur', 
                    'Sudah Dipakai (Sebagian)', 
                    'Sudah Dipakai (Penuh)', 
                    'Sudah Diretur (Sebagian)'
            )
            ORDER BY pp.nomor_bon ASC, lp.rol_terima ASC
        ", [$stok_id])->getResultArray();

        return view('pemakaian/lot_rol/edit', [
            'lotrol_penerimaan' => $lotrol_penerimaan_filtered,
            'pakai_id' => $pakai_id,
            'det_pakai_id' => $det_pakai_id,
            'stok_detail' => $stok_detail,
            'detail_pemakaian' => $detail_pemakaian,
            'pemakaian' => $pemakaian
        ]);
    }

    private function getNomorPoByDetTerimaId($det_terima_id)
    {
        $builder = $this->db->table('detail_penerimaan');
        $builder->select('purchase_order.nomor_po');
        $builder->join('penerimaan_pembelian', 'penerimaan_pembelian.terima_id = detail_penerimaan.terima_id', 'left');
        $builder->join('purchase_order', 'purchase_order.po_id = penerimaan_pembelian.po_id', 'left');
        $builder->where('detail_penerimaan.det_terima_id', $det_terima_id);
        $result = $builder->get()->getRowArray();

        return $result['nomor_po'] ?? '-';
    }

    private function getPoIdByDetTerimaId($det_terima_id)
    {
        $builder = $this->db->table('detail_penerimaan');
        $builder->select('purchase_order.po_id');
        $builder->join('penerimaan_pembelian', 'penerimaan_pembelian.terima_id = detail_penerimaan.terima_id', 'left');
        $builder->join('purchase_order', 'purchase_order.po_id = penerimaan_pembelian.po_id', 'left');
        $builder->where('detail_penerimaan.det_terima_id', $det_terima_id);
        $result = $builder->get()->getRowArray();

        return $result['po_id'] ?? null;
    }
}