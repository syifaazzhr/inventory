<?php

namespace App\Controllers\Retur;

use App\Models\LotRolReturModel;
use App\Models\StokBahanModel;
use App\Models\PurchaseOrderModel;
use App\Models\ReturModel;
use CodeIgniter\Controller;

class LotRolReturController extends Controller
{
    protected $db;
    protected $lotRolReturModel;
    protected $stokBahanModel;
    protected $purchaseOrderModel;
    protected $returModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect(); 
        $this->lotRolReturModel = new LotRolReturModel();
        $this->stokBahanModel = new StokBahanModel();
        $this->purchaseOrderModel = new PurchaseOrderModel();
        $this->returModel = new ReturModel();
    }

    public function index()
    {
        $search = $this->request->getVar('search');
        $lotRolData = $this->lotRolReturModel->getLotRol($search);

        return view('retur/lot_rol/index', [
            'lot_rol_data' => $lotRolData,
            'search' => $search
        ]);
    }

    // public function show($retur_id, $det_retur_id)
    // {
    //     $returModel = new \App\Models\ReturModel();
    //     $detailReturModel = new \App\Models\DetailReturModel();
    //     $stokBahanModel = new \App\Models\StokBahanModel();
    //     $purchaseOrderModel = new \App\Models\PurchaseOrderModel();
    //     $lotRolPenerimaanModel = new \App\Models\LotRolPenerimaanModel();
    //     $lotRolPemakaianModel = new \App\Models\LotRolPemakaianModel();
    //     $lotRolReturModel = new \App\Models\LotRolReturModel();

    //     $retur = $returModel->find($retur_id);
    //     $detailRetur = $detailReturModel->getDetailWithStock($retur_id);
    //     $detailItem = null;
    //     foreach ($detailRetur as $item) {
    //         if ($item['det_retur_id'] == $det_retur_id) {
    //             $detailItem = $item;
    //             break;
    //         }
    //     }

    //     if (!$detailItem) {
    //         throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Detail retur tidak ditemukan.');
    //     }
 
    //     $stok_id = $detailItem['stok_id'] ?? null;
    //     $totalPermintaanRetur = (float) ($detailItem['qty_det_retur'] ?? 0);

    //     $hasilFIFO = $this->db->query("
    //         SELECT 
    //             lp.lot_terima AS lot,
    //             lp.rol_terima AS rol,
    //             po.nomor_po AS nomor_po_asal,
    //             pp.nomor_bon,
    //             (lp.qty_lotrol_terima 
    //                 - IFNULL((SELECT SUM(qty_lotrol_pakai) FROM lotrol_pemakaian 
    //                         WHERE lot_pakai = lp.lot_terima AND rol_pakai = lp.rol_terima), 0)
    //                 - IFNULL((SELECT SUM(qty_lotrol_retur) FROM lotrol_retur 
    //                         WHERE lot_retur = lp.lot_terima AND rol_retur = lp.rol_terima), 0)
    //             ) AS sisa_qty
    //         FROM lotrol_penerimaan lp
    //         JOIN detail_penerimaan dp ON dp.det_terima_id = lp.det_terima_id
    //         JOIN penerimaan_pembelian pp ON pp.terima_id = dp.terima_id
    //         LEFT JOIN purchase_order po ON po.po_id = pp.po_id
    //         WHERE dp.stok_id = ?
    //         HAVING sisa_qty > 0
    //         ORDER BY 
    //             pp.nomor_bon ASC, 
    //             lp.lot_terima ASC, 
    //             CAST(SUBSTRING(lp.rol_terima, 2) AS UNSIGNED) ASC
    //     ", [$stok_id])->getResultArray();
        

    //     $alokasiFIFO = [];
    //     foreach ($hasilFIFO as $row) {
    //         if ($totalPermintaanRetur <= 0) break;

    //         $ambil = min($totalPermintaanRetur, $row['sisa_qty']);
    //         $alokasiFIFO[] = [
    //             'lot' => $row['lot'],
    //             'rol' => $row['rol'],
    //             'nomor_po_asal' => $row['nomor_po_asal'],
    //             'qty_alokasi' => $ambil,
    //         ];

    //         $totalPermintaanRetur -= $ambil;
    //     }

    //     $latestBon = $this->db->query("
    //         SELECT MAX(CAST(SUBSTRING_INDEX(pp.nomor_bon, '-', -1) AS UNSIGNED)) AS last_bon
    //         FROM penerimaan_pembelian pp
    //         JOIN detail_penerimaan dp ON dp.terima_id = pp.terima_id
    //         WHERE dp.stok_id = ?
    //     ", [$stok_id])->getRowArray();    

    //     $lastBonNumber = $latestBon['last_bon'] ?? 0;
    //     $lotrol_penerimaan_all = $lotRolPenerimaanModel->findAll();
    //     $lotrol_pemakaian_all = $lotRolPemakaianModel->findAll();
    //     $lotrol_retur_all = $lotRolReturModel->findAll();

    //     $lotrol_penerimaan_filtered = array_filter($lotrol_penerimaan_all, function ($lot) use ($detailReturModel, $stok_id, $lotrol_pemakaian_all, $lotrol_retur_all) {
    //         if (!isset($lot['lot_terima']) || !isset($lot['rol_terima']) || !isset($lot['det_terima_id'])) {
    //             return false; 
    //         }

    //         $detail = $detailReturModel
    //             ->db
    //             ->table('detail_penerimaan')
    //             ->where('det_terima_id', $lot['det_terima_id'])
    //             ->where('stok_id', $stok_id)
    //             ->get()
    //             ->getRowArray();

    //         if (!$detail) return false;

    //         foreach ($lotrol_pemakaian_all as $retur) {
    //             if (isset($retur['lot_terima'], $retur['rol_terima']) &&
    //                 $retur['lot_terima'] == $lot['lot_terima'] &&
    //                 $retur['rol_terima'] == $lot['rol_terima']) {
    //                 return false;
    //             }
    //         }

    //         foreach ($lotrol_retur_all as $retur) {
    //             if (isset($retur['lot_retur'], $retur['rol_retur']) &&
    //                 $retur['lot_retur'] == $lot['lot_terima'] &&
    //                 $retur['rol_retur'] == $lot['rol_terima']) {
    //                 return false;
    //             }
    //         }

    //         return true;
    //     }); 

    //     foreach ($lotrol_penerimaan_filtered as &$lotrol) {
    //         $lotrol['nomor_po'] = $this->getNomorPoByDetTerimaId($lotrol['det_terima_id']);
    //     }        

    //     foreach ($detailRetur as $item) {
    //         if ($item['det_retur_id'] == $det_retur_id) {
    //             $detailItem = $item;
    //             break;
    //         }
    //     }

    //     $lotrol_retur = $lotRolReturModel
    //         ->where('retur_id', $retur_id)
    //         ->where('det_retur_id', $det_retur_id)
    //         ->findAll();

    //         $nomor_po = '-';
    //         if ($detailItem && isset($detailItem['po_id'])) {
    //             $po = $purchaseOrderModel->find($detailItem['po_id']);
    //             $nomor_po = $detailItem['nomor_po'] ?? '-';
    //         }   
            
    //         helper('text');

    //         $lotrol_penerimaan_filtered = $this->db->query("
    //         SELECT 
    //             lp.*, dp.stok_id,
    //             po.nomor_po,
    //             (lp.qty_lotrol_terima 
    //                 - IFNULL((SELECT SUM(qty_lotrol_pakai) FROM lotrol_pemakaian 
    //                         WHERE lot_pakai = lp.lot_terima AND rol_pakai = lp.rol_terima), 0) 
    //                 - IFNULL((SELECT SUM(qty_lotrol_retur) FROM lotrol_retur 
    //                         WHERE lot_retur = lp.lot_terima AND rol_retur = lp.rol_terima), 0)
    //             ) AS sisa_kuantitas
    //         FROM lotrol_penerimaan lp
    //         JOIN detail_penerimaan dp ON dp.det_terima_id = lp.det_terima_id
    //         JOIN penerimaan_pembelian pp ON pp.terima_id = dp.terima_id
    //         LEFT JOIN purchase_order po ON po.po_id = pp.po_id
    //         WHERE dp.stok_id = ? 
    //         AND (
    //             lp.qty_lotrol_terima 
    //             - IFNULL((SELECT SUM(qty_lotrol_pakai) FROM lotrol_pemakaian 
    //                     WHERE lot_pakai = lp.lot_terima AND rol_pakai = lp.rol_terima), 0)
    //             - IFNULL((SELECT SUM(qty_lotrol_retur) FROM lotrol_retur 
    //                     WHERE lot_retur = lp.lot_terima AND rol_retur = lp.rol_terima), 0)
    //         ) > 0
    //     ", [$stok_id])->getResultArray();  
        
    //     $lotrol_all_status = $this->db->query("
    //         SELECT 
    //         lp.*, dp.stok_id, po.nomor_po,
    //         (lp.qty_lotrol_terima 
    //             - COALESCE((
    //                 SELECT SUM(qty_lotrol_pakai) 
    //                 FROM lotrol_pemakaian 
    //                 WHERE lot_terima_asal = lp.lot_terima AND rol_terima_asal = lp.rol_terima
    //             ), 0) 
    //             - COALESCE((
    //                 SELECT SUM(qty_lotrol_retur) 
    //                 FROM lotrol_retur 
    //                 WHERE lot_terima_asal = lp.lot_terima AND rol_terima_asal = lp.rol_terima
    //             ), 0)
    //         ) AS sisa_kuantitas,
    //         CASE 
    //             WHEN COALESCE((
    //                 SELECT SUM(qty_lotrol_pakai) 
    //                 FROM lotrol_pemakaian 
    //                 WHERE lot_terima_asal = lp.lot_terima AND rol_terima_asal = lp.rol_terima
    //             ), 0) > 0 
    //             OR COALESCE((
    //                 SELECT SUM(qty_lotrol_retur) 
    //                 FROM lotrol_retur 
    //                 WHERE lot_terima_asal = lp.lot_terima AND rol_terima_asal = lp.rol_terima
    //             ), 0) > 0 
    //             THEN 'Sudah Dipakai/Diretur'
    //             ELSE 'Belum Dipakai/Diretur'
    //         END AS status_transaksi
    //     FROM lotrol_penerimaan lp
    //     JOIN detail_penerimaan dp ON dp.det_terima_id = lp.det_terima_id
    //     JOIN penerimaan_pembelian pp ON pp.terima_id = dp.terima_id
    //     LEFT JOIN purchase_order po ON po.po_id = pp.po_id
    //     WHERE dp.stok_id = ?
    //     ORDER BY status_transaksi ASC, lp.lot_terima ASC;
    //     ", [$stok_id])->getResultArray();

    //     return view('retur/lot_rol/show', [
    //         'lotrol_penerimaan' => $lotrol_penerimaan_filtered, 
    //         'lotrol_all_status' => $lotrol_all_status,
    //         'lotrol_retur' => $lotrol_retur,
    //         'retur' => $retur,
    //         'detail_retur' => $detailItem,
    //         'retur_id' => $retur_id,
    //         'det_retur_id' => $det_retur_id,
    //         'nomor_po' => $nomor_po,
    //         'hasilFIFO' => $alokasiFIFO,
    //     ]);        
    // }

    public function show($retur_id, $det_retur_id)
    {
        $returModel = new \App\Models\ReturModel();
        $detailReturModel = new \App\Models\DetailReturModel();
        $stokBahanModel = new \App\Models\StokBahanModel();
        $purchaseOrderModel = new \App\Models\PurchaseOrderModel();
        $lotRolPenerimaanModel = new \App\Models\LotRolPenerimaanModel();
        $lotRolPemakaianModel = new \App\Models\LotRolPemakaianModel();
        $lotRolReturModel = new \App\Models\LotRolReturModel();

        $retur = $returModel->find($retur_id);
        $detailRetur = $detailReturModel->getDetailWithStock($retur_id);
        $detailItem = null;
        foreach ($detailRetur as $item) {
            if ($item['det_retur_id'] == $det_retur_id) {
                $detailItem = $item;
                break;
            }
        }

        if (!$detailItem) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Detail retur tidak ditemukan.');
        }

        $stok_id = $detailItem['stok_id'] ?? null;
        $totalPermintaanRetur = (float) ($detailItem['qty_det_retur'] ?? 0);

        // Fetch the lotrol data (including remaining quantities and return data)
        $lotrol_data = $this->db->query("
            SELECT 
                lp.lot_terima AS lot_asal,
                lp.rol_terima AS rol_asal,
                po.nomor_po,
                po.po_id,
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
                    WHEN (COALESCE((SELECT SUM(lpem.qty_lotrol_pakai) FROM lotrol_pemakaian lpem WHERE lpem.lot_terima_asal = lp.lot_terima AND lpem.rol_terima_asal = lp.rol_terima), 0) 
                    + COALESCE((SELECT SUM(lr.qty_lotrol_retur) FROM lotrol_retur lr WHERE lr.lot_terima_asal = lp.lot_terima AND lr.rol_terima_asal = lp.rol_terima), 0)) >= lp.qty_lotrol_terima 
                    THEN 'Sudah Dipakai & Diretur (Penuh)'
                    
                    WHEN COALESCE((SELECT SUM(lpem.qty_lotrol_pakai) FROM lotrol_pemakaian lpem WHERE lpem.lot_terima_asal = lp.lot_terima AND lpem.rol_terima_asal = lp.rol_terima), 0) > 0 
                    AND COALESCE((SELECT SUM(lr.qty_lotrol_retur) FROM lotrol_retur lr WHERE lr.lot_terima_asal = lp.lot_terima AND lr.rol_terima_asal = lp.rol_terima), 0) > 0 
                    THEN 'Sudah Dipakai & Diretur (Sebagian)'
                    
                    ELSE 'Belum Dipakai/Diretur'
                END AS status_transaksi
            FROM lotrol_penerimaan lp
            JOIN detail_penerimaan dp ON dp.det_terima_id = lp.det_terima_id
            JOIN penerimaan_pembelian pp ON pp.terima_id = dp.terima_id
            LEFT JOIN purchase_order po ON po.po_id = pp.po_id
            WHERE dp.stok_id = ?
            ORDER BY pp.nomor_bon ASC, lp.rol_terima ASC
        ", [$stok_id])->getResultArray();

        // Fetch the returned data (lotrol_retur)
        $lotrol_retur = $lotRolReturModel
            ->where('retur_id', $retur_id)
            ->where('det_retur_id', $det_retur_id)
            ->findAll();

        // Fetch the status and remaining quantities for unreturned items
        $lotrol_all_status = $this->db->query("
            SELECT 
                lp.*, dp.stok_id, po.nomor_po,
                (lp.qty_lotrol_terima 
                    - COALESCE((
                        SELECT SUM(qty_lotrol_pakai) 
                        FROM lotrol_pemakaian 
                        WHERE lot_terima_asal = lp.lot_terima AND rol_terima_asal = lp.rol_terima
                    ), 0) 
                    - COALESCE((
                        SELECT SUM(qty_lotrol_retur) 
                        FROM lotrol_retur 
                        WHERE lot_terima_asal = lp.lot_terima AND rol_terima_asal = lp.rol_terima
                    ), 0)
                ) AS sisa_kuantitas,
                CASE 
                    WHEN COALESCE((SELECT SUM(qty_lotrol_pakai) FROM lotrol_pemakaian WHERE lot_terima_asal = lp.lot_terima AND rol_terima_asal = lp.rol_terima), 0) > 0 
                    OR COALESCE((SELECT SUM(qty_lotrol_retur) FROM lotrol_retur WHERE lot_terima_asal = lp.lot_terima AND rol_terima_asal = lp.rol_terima), 0) > 0 
                    THEN 'Sudah Dipakai/Diretur'
                    ELSE 'Belum Dipakai/Diretur'
                END AS status_transaksi
            FROM lotrol_penerimaan lp
            JOIN detail_penerimaan dp ON dp.det_terima_id = lp.det_terima_id
            JOIN penerimaan_pembelian pp ON pp.terima_id = dp.terima_id
            LEFT JOIN purchase_order po ON po.po_id = pp.po_id
            WHERE dp.stok_id = ?
            ORDER BY status_transaksi ASC, lp.lot_terima ASC
        ", [$stok_id])->getResultArray();

        $lotrol_penerimaan = $lotRolPenerimaanModel->findAll();

        return view('retur/lot_rol/show', [
            'lotrol_data' => $lotrol_data,
            'lotrol_retur' => $lotrol_retur,
            'retur' => $retur,
            'detail_retur' => $detailItem,
            'retur_id' => $retur_id,
            'det_retur_id' => $det_retur_id,
            'lotrol_all_status' => $lotrol_all_status,
            'lotrol_penerimaan' => $lotrol_penerimaan
        ]);
    }

    public function edit($id)
    {
        $lotRol = $this->lotRolReturModel->find($id);
        if (!$lotRol) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Lot & Roll tidak ditemukan.');
        }

        $returList = $this->returModel->findAll();
        $stokList = $this->stokBahanModel->findAll();
        $retur_id = $lotRol['retur_id'];
        $det_retur_id = $lotRol['det_retur_id'];
        $retur = $this->returModel->find($retur_id);
        if (!$retur || !isset($retur['nomor_retur'])) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Nomor retur tidak ditemukan.');
        }

        return view('retur/lot_rol/edit', [
            'lot_rol' => $lotRol,
            'retur_list' => $returList,
            'stok_list' => $stokList,
            'retur_id' => $retur_id,
            'det_retur_id' => $det_retur_id,
            'lotrol_id' => $id,
            'retur' => $retur, 
            'detail' => $detail,
            'lotrol_id' => 0,
            'po_id' => $detail_retur['po_id'] ?? null,
        ]);
    }

    public function update($id = null)
    {
        // dd($this->request->getPost());

        $rules = [
            'retur_id' => 'required|integer',
            'det_retur_id' => 'required|integer',
            'po_id' => 'required',
            'lot_retur' => 'required',
            'rol_retur' => 'required',
            'qty_lotrol_retur' => 'required'
        ];

        if (!$this->validate($rules)) {
            dd($this->validator->getErrors());
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $this->db->transStart();

        $retur_id = (int) $this->request->getPost('retur_id');
        $det_retur_id = (int) $this->request->getPost('det_retur_id');
        $qty_lotrol_retur = $this->request->getPost('qty_lotrol_retur'); 

        $stokData = $this->db->table('detail_retur')
            ->select('stok_id')
            ->where('det_retur_id', $det_retur_id)
            ->get()
            ->getRowArray();

        $stok_id = $stokData['stok_id'] ?? null;
        if (!$stok_id) {
            throw new \RuntimeException('stok_id tidak ditemukan. Periksa tabel detail_retur.');
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

        $this->lotRolReturModel
            ->where('retur_id', $retur_id)
            ->where('det_retur_id', $det_retur_id)
            ->delete();

        foreach ($qty_lotrol_retur as $index => $qtyStr) {
            $qty = floatval($qtyStr);
            if ($qty <= 0.00) continue;

            $poRetur = is_array($this->request->getPost('po_id')) 
                        ? $this->request->getPost('po_id')[$index] 
                        : $this->request->getPost('po_id');

            $lotRetur = is_array($this->request->getPost('lot_retur')) 
                        ? $this->request->getPost('lot_retur')[$index] 
                        : $this->request->getPost('lot_retur'); 

            $rolRetur = is_array($this->request->getPost('rol_retur')) 
                        ? $this->request->getPost('rol_retur')[$index] 
                        : $this->request->getPost('rol_retur');

            $lotrolPenerimaan = $this->db->table('lotrol_penerimaan')
                ->select('lotrol_terima_id')
                ->where('lot_terima', $lotRetur)
                ->where('rol_terima', $rolRetur)
                ->get()
                ->getRowArray();

            $lotrolTerimaId = $lotrolPenerimaan['lotrol_terima_id'] ?? null;

            $data = [
                'retur_id'        => $retur_id,
                'det_retur_id'    => $det_retur_id,
                'po_id'           => $latestPoId, 
                'lot_retur'       => $latestLot, 
                'lot_terima_asal' => $lotRetur,    
                'rol_retur'       => $latestRol,  
                'rol_terima_asal' => $rolRetur,
                'qty_lotrol_retur'=> $qty,
                'lotrol_terima_id'=> $lotrolTerimaId 
            ];

            $this->lotRolReturModel->insert($data);
        }

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return redirect()->back()->with('msg', 'Gagal memperbarui data!')->with('error', true);
        }

        return redirect()->to(base_url('admin/retur/lot_rol/' . $retur_id . '/' . $det_retur_id))
            ->with('msg', 'Data berhasil diperbarui!');
    }

    public function edit_by_retur($retur_id, $det_retur_id)
    {
        $returModel = new \App\Models\ReturModel();
        $detailReturModel = new \App\Models\DetailReturModel();
        $stokModel = new \App\Models\StokBahanModel();

        $retur = $returModel->find($retur_id);
        $detail_retur = $detailReturModel->find($det_retur_id);
        if (!$retur || !$detail_retur) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Data retur tidak ditemukan.');
        }

        $stok_detail = $stokModel->find($detail_retur['stok_id']);

        $lotrol_penerimaan_filtered = $this->db->query("
            SELECT 
                lp.lot_terima AS lot_asal,
                lp.rol_terima AS rol_asal,
                po.nomor_po,
                po.po_id,
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
                    ), 0)) >= lp.qty_lotrol_terima THEN 'Sudah Dipakai & Diretur (Penuh)'
                    
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
                    ), 0) > 0 THEN 'Sudah Dipakai & Diretur (Sebagian)'
                    
                    WHEN COALESCE((
                        SELECT SUM(lpem.qty_lotrol_pakai) 
                        FROM lotrol_pemakaian lpem 
                        WHERE lpem.lot_terima_asal = lp.lot_terima 
                        AND lpem.rol_terima_asal = lp.rol_terima
                    ), 0) > 0 THEN 
                        CASE 
                            WHEN COALESCE((
                                SELECT SUM(lpem.qty_lotrol_pakai) 
                                FROM lotrol_pemakaian lpem 
                                WHERE lpem.lot_terima_asal = lp.lot_terima 
                                AND lpem.rol_terima_asal = lp.rol_terima
                            ), 0) = lp.qty_lotrol_terima THEN 'Sudah Dipakai (Penuh)'
                            ELSE 'Sudah Dipakai (Sebagian)'
                        END

                    WHEN COALESCE((
                        SELECT SUM(lr.qty_lotrol_retur) 
                        FROM lotrol_retur lr 
                        WHERE lr.lot_terima_asal = lp.lot_terima 
                        AND lr.rol_terima_asal = lp.rol_terima
                    ), 0) > 0 THEN 
                        CASE 
                            WHEN COALESCE((
                                SELECT SUM(lr.qty_lotrol_retur) 
                                FROM lotrol_retur lr 
                                WHERE lr.lot_terima_asal = lp.lot_terima 
                                AND lr.rol_terima_asal = lp.rol_terima
                            ), 0) = lp.qty_lotrol_terima THEN 'Sudah Diretur (Penuh)'
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
                    AND (
                        status_transaksi = 'Sudah Dipakai (Sebagian)' OR
                        status_transaksi = 'Sudah Diretur (Sebagian)' OR
                        status_transaksi = 'Belum Dipakai/Diretur'
                    )
        ", [$detail_retur['stok_id']])->getResultArray();

        return view('retur/lot_rol/edit', [
            'lotrol_penerimaan' => $lotrol_penerimaan_filtered,
            'retur_id' => $retur_id,
            'det_retur_id' => $det_retur_id,
            'stok_detail' => $stok_detail,
            'detail_retur' => $detail_retur,
            'retur' => $retur
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