<?php

namespace App\Controllers\Pemakaian;

use App\Models\PemakaianModel;
use App\Models\DetailPemakaianModel;
use App\Models\StokBahanModel;
use CodeIgniter\Controller;
use App\Models\PurchaseOrderModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PemakaianController extends Controller
{
    protected $db;
    protected $pemakaianModel;
    protected $detailPemakaianModel;
    protected $stokBahanModel;
    protected $purchaseOrderModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect(); 
        $this->pemakaianModel = new PemakaianModel();
        $this->stokBahanModel = new StokBahanModel();
        $this->detailPemakaianModel = new DetailPemakaianModel();
        $this->purchaseOrderModel = new PurchaseOrderModel();
    }

    public function index()
    {
        $pemakaianModel = new PemakaianModel();
        $search = $this->request->getVar('search');
        $perPage = 10; 
        $page = $this->request->getVar('page') ?: 1;
        $startDate = $this->request->getVar('start_date');
        $endDate = $this->request->getVar('end_date');
        $filterType = $this->request->getVar('filter_type'); 
        $month = $this->request->getVar('month');

        if ($month && preg_match('/^\d{4}-\d{2}$/', $month)) {
            $startOfMonth = $month . '-01';
            $endOfMonth = date('Y-m-t', strtotime($startOfMonth));
            $pemakaianModel = $pemakaianModel
                ->where('tgl_keluar >=', $startOfMonth)
                ->where('tgl_keluar <=', $endOfMonth);
        } elseif ($filterType === 'tgl_keluar' && $startDate && $endDate) {
            $pemakaianModel = $pemakaianModel->where('tgl_keluar >=', $startDate)
                                                ->where('tgl_keluar <=', $endDate);
        } else {
            $startDate = '';
            $endDate = '';
        }

        if ($search) {
            $pemakaianModel = $pemakaianModel->like('nomor_pakai', $search);
        }

        $orderBy = $this->request->getVar('order_by') ?? 'tgl_keluar';
        $order = $this->request->getVar('order') ?? 'asc';

        $allowedColumns = ['nomor_pakai', 'tgl_keluar', 'nomor_ws_warna_pakai', 'nomor_ws_pakai', 'nomor_art_pakai', 'nama_penerima', 'ket_pakai', 'admin_pakai', 'total_qty_pakai'];

        if (in_array($orderBy, $allowedColumns)) {
            $pemakaianModel->orderBy($orderBy, $order);
        } else {
            $pemakaianModel->orderBy('tgl_keluar', 'asc');
        }

        $data = [
            'pemakaian' => $pemakaianModel->getPemakaian($search, $perPage, $page, $startDate, $endDate, $filterType),
            'pager' => $pemakaianModel->pager,
            'search' => $search,
            'orderBy' => $orderBy,
            'order' => $order,
            'start_date' => $startDate, 
            'end_date' => $endDate,     
            'filter_type' => $filterType, 
        ];

        return view('pemakaian/index', $data);
    }

    public function show($id)
    {
        $pemakaianModel = new PemakaianModel();
        $purchaseOrderModel = new PurchaseOrderModel();

        $pemakaian = $pemakaianModel->find($id);
        if (!$pemakaian) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Retur Pembelian tidak ditemukan.');
        }

        $detail_pemakaian = $pemakaianModel->getDetailWithStock($id);
        $pemakaian_id = $pemakaian['pakai_id']; 
        $det_pakai_id = $detail_pemakaian[0]['det_pakai_id']; 

        return view('pemakaian/show', [
            'pemakaian' => $pemakaian,
            'detail_pemakaian' => $detail_pemakaian,
        ]);
    }

    public function create()
    {
        $stokModel = new \App\Models\StokBahanModel();
        $stok_list = $this->stokBahanModel->findAll();

        $jenis = $this->request->getGet('jenis') ?? 'Aksesoris';
        $tgl_keluar = date('Y-m-d'); 
        $nomor_pakai = $this->pemakaianModel->generateNomorPakai($tgl_keluar, $jenis);

        // Passing data to the view
        return view('pemakaian/create', [
            'nomor_pakai' => $nomor_pakai,
            'stok_list' => $stok_list
        ]);
    }

    public function store()
    {
        // dd($this->request->getPost());

        $rules = [
            'tgl_keluar' => 'permit_empty|valid_date[Y-m-d]',
            'nomor_ws_warna_pakai' => 'permit_empty|string',
            'nomor_ws_pakai' => 'permit_empty|string',
            'nomor_art_pakai' => 'permit_empty|string',
            'nama_penerima' => 'permit_empty|string',
            'ket_pakai' => 'permit_empty|string',
            'admin_pakai' => 'required|string',
            'kode_stok' => 'required',
            'total_kuantitas' => 'required'
        ];

        if (!$this->validate($rules)) {
            dd($this->validator->getErrors());
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $this->db->transStart();

        $tgl_keluar = $this->request->getPost('tgl_keluar');
        $jenis = $this->request->getPost('jenis') ?? 'Aksesoris';
        $nomor_pakai = $this->pemakaianModel->generateNomorPakai($tgl_keluar, $jenis);
        $totalQtyPakai = array_sum($this->request->getPost('total_kuantitas'));

        $this->pemakaianModel->insert([
            'nomor_pakai' => $nomor_pakai,
            'tgl_keluar' => $tgl_keluar,
            'nomor_ws_warna_pakai' => $this->request->getPost('nomor_ws_warna_pakai'),
            'nomor_ws_pakai' => $this->request->getPost('nomor_ws_pakai'),
            'nomor_art_pakai' => $this->request->getPost('nomor_art_pakai'),
            'nama_penerima' => $this->request->getPost('nama_penerima'),
            'ket_pakai' => $this->request->getPost('ket_pakai'),
            'admin_pakai' => auth()->user()->username,
            'total_qty_pakai' => $totalQtyPakai
        ]);

        $pemakaian_id = $this->pemakaianModel->getInsertID();
        $detailPemakaianModel = new DetailPemakaianModel();
        $kodeStokList = $this->request->getPost('kode_stok');
        $totalKuantitasList = $this->request->getPost('total_kuantitas') ?? [];

        foreach ($kodeStokList as $index => $stok_id) {
            $detailPemakaianData = [
                'pakai_id' => $pemakaian_id,
                'stok_id' => (int) $stok_id,
                'qty_det_pakai' => $totalKuantitasList[$index]
            ];
            $detailPemakaianModel->insert($detailPemakaianData);
        }               

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return redirect()->back()->with('msg', 'Gagal menyimpan data!')->with('error', true);
        }

        $totalData = $this->pemakaianModel->countAll();
        $dataPerPage = 10;
        $lastPage = ceil($totalData / $dataPerPage);

        return redirect()->to(base_url('admin/pemakaian?page=' . $lastPage))->with('msg', 'Data berhasil disimpan!');
    }

    public function edit($id) 
    {
        $pemakaianModel = new PemakaianModel();
        $stokModel = new StokBahanModel();
        $detailPemakaianModel = new DetailPemakaianModel();

        $pemakaian = $pemakaianModel->find($id);
        if (!$pemakaian) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Retur Pembelian tidak ditemukan.');
        }

        $stok_list = $stokModel->findAll();
        $detail_pemakaian = $detailPemakaianModel->where('pakai_id', $id)->findAll();

        foreach ($detail_pemakaian as &$detail) {
            if (isset($detail['stok_id']) && $detail['stok_id']) {
                $stok = $stokModel->find($detail['stok_id']);
                if ($stok) {
                    $detail['kode_stok'] = $stok['kode_stok'];
                    $detail['nama_barang'] = $stok['nama_barang'];
                } else {
                    $detail['kode_stok'] = 'Kode Stok Tidak Ditemukan';
                    $detail['nama_barang'] = 'Nama Barang Tidak Ditemukan';
                }
            }
        }  
        
        $totalKuantitasPemakaian = array_sum(array_column($detail_pemakaian, 'qty_det_pakai'));

        return view('pemakaian/edit', [
            'nomor_pakai' => $pemakaian['nomor_pakai'],
            'detail_pemakaian' => $detail_pemakaian,
            'pemakaian' => $pemakaian,
            'stok_list' => $stok_list,
            'total_kuantitas_pemakaian' => $totalKuantitasPemakaian,
            'admin_pakai' => auth()->user()->username
        ]);
    }

    public function update($id)
    {
        // dd($this->request->getPost());

        $rules = [
            'tgl_keluar' => 'permit_empty|valid_date[Y-m-d]',
            'nomor_ws_warna_pakai' => 'permit_empty',
            'nomor_ws_pakai' => 'permit_empty',
            'nomor_art_pakai' => 'permit_empty',
            'nama_penerima' => 'permit_empty',
            'kode_stok' => 'required',
            'kode_stok.*' => 'required|integer',
            'ket_pakai' => 'permit_empty|string',
            'admin_pakai' => 'required|string',
            'total_kuantitas' => 'required'
        ];

        if (!$this->validate($rules)) {
            dd($this->validator->getErrors());
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $this->db->transStart();

        $pemakaianModel = new PemakaianModel();
        $tanggalKeluar = $this->request->getPost('tgl_keluar');
        $totalQtyPakai = array_sum($this->request->getPost('total_kuantitas'));
        $existing = $this->pemakaianModel->find($id);
        if (!$existing) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Data pemakaian tidak ditemukan.');
        }
        $nomorPakai = $existing['nomor_pakai'];

        $pemakaianData = [
            'nomor_pakai' => $nomorPakai,
            'tgl_keluar' => $this->request->getPost('tgl_keluar'),
            'nomor_ws_warna_pakai' => $this->request->getPost('nomor_ws_warna_pakai'),
            'nomor_ws_pakai' => $this->request->getPost('nomor_ws_pakai'),
            'nomor_art_pakai' => $this->request->getPost('nomor_art_pakai'),
            'nama_penerima' => $this->request->getPost('nama_penerima'),
            'ket_pakai' => $this->request->getPost('ket_pakai'),
            'admin_pakai' => auth()->user()->username,
            'total_qty_pakai' => $totalQtyPakai    
        ];

        $pemakaianModel->update($id, $pemakaianData);
        $detailPemakaianModel = new DetailPemakaianModel();
        $stokIdList = $this->request->getPost('kode_stok');
        $totalKuantitasList = $this->request->getPost('total_kuantitas');      
        $detailPemakaianModel->where('pakai_id', $id)->delete();
        $kodeStokList = $this->request->getPost('kode_stok') ?? [];
        if (empty($kodeStokList)) {
            return redirect()->back()->with('msg', 'Kode Stok tidak boleh kosong!')->with('error', true);
        }

        foreach ($kodeStokList as $index => $stok_id) {
            $detailPemakaianData = [
                'pakai_id' => $id,
                'stok_id' => (int) $stok_id,
                'qty_det_pakai' => $totalKuantitasList[$index]
            ];
            $detailPemakaianModel->insert($detailPemakaianData);
        }

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return redirect()->back()->with('msg', 'Gagal memperbarui data!')->with('error', true);
        }

        return redirect()->to(base_url('admin/pemakaian/' . $id))->with('msg', 'Data berhasil diperbarui!');
    }

    public function delete($id)
    {
        $pemakaian = $this->pemakaianModel->find($id);
        if (!$pemakaian) {
            return redirect()->to('/admin/pemakaian')->with('msg', 'Data tidak ditemukan!')->with('error', true);
        }

        // Delete the retur data
        $this->pemakaianModel->delete($id);

        // Optionally delete associated detail retur
        $this->detailPemakaianModel->where('pakai_id', $id)->delete();

        return redirect()->to('/admin/pemakaian')->with('msg', 'Data berhasil dihapus!');
    }

    public function downloadExcel()
    {
        $pemakaianModel = new PemakaianModel();
        $detailPemakaianModel = new DetailPemakaianModel();
        $pemakaian = $pemakaianModel->findAll();
        $pemakaianDetails = [];

        foreach ($pemakaian as $pemakaian_bahan) {
            $details = $detailPemakaianModel->where('pakai_id', $pemakaian_bahan['pakai_id'])->findAll();
            $pemakaianDetails[$pemakaian_bahan['pakai_id']] = $details;
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Nomor Retur')
            ->setCellValue('B1', 'Tanggal Retur')
            ->setCellValue('F1', 'Keterangan')
            ->setCellValue('G1', 'Admin')
            ->setCellValue('H1', 'Total Kuantitas');

        $row = 2;
        foreach ($pemakaian as $pemakaian_bahan) {
            $supplier = $this->supplierModel->find($pemakaian_bahan['supplier_id']);

            $sheet->setCellValue('A' . $row, $pemakaian_bahan['nomor_pakai']);
            $sheet->setCellValue('B' . $row, $pemakaian_bahan['tgl_keluar']);
            $sheet->setCellValue('F' . $row, $pemakaian_bahan['ket_pakai']);
            $sheet->setCellValue('G' . $row, $pemakaian_bahan['admin_pakai']);
            $sheet->setCellValue('H' . $row, $pemakaian_bahan['total_qty_pakai']); 
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="pemakaian_bahan.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    } 
 
    public function printAll()
    {
        $data['pemakaian'] = $this->pemakaianModel->findAll(); // Atau tambahkan filter tertentu
        return view('admin/pemakaian/lot_rol/print_all', $data);
    }

    public function printOne($id)
    {
        $pemakaian = $this->pemakaianModel->find($id);
        if (!$pemakaian) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Data Pemakaian tidak ditemukan.");
        }

        // Ambil detail pemakaian
        $detail_pemakaian = $this->detailPemakaianModel
            ->select('detail_pemakaian.*, stok_bahan.kode_stok, stok_bahan.nama_barang, satuan.jenis_satuan')
            ->join('stok_bahan', 'stok_bahan.stok_id = detail_pemakaian.stok_id', 'left')
            ->join('satuan', 'satuan.satuan_id = stok_bahan.satuan_id', 'left')
            ->where('detail_pemakaian.pakai_id', $id)
            ->findAll();

        $detail = $detail_pemakaian[0] ?? null;
        $det_pakai_id = $detail['det_pakai_id'] ?? null;

        // Ambil data lotrol pemakaian
        $lotrolPemakaianModel = new \App\Models\LotRolPemakaianModel();
        $lotrolPenerimaanModel = new \App\Models\LotRolPenerimaanModel();

        $lotrol_pemakaian = []; 
        if ($det_pakai_id) {
            $lotrol_pemakaian = $this->db->table('lotrol_pemakaian lp')
                ->select('lp.*, lpn.lot_terima, lpn.rol_terima, po.nomor_po')
                ->join('lotrol_penerimaan lpn', 'lpn.lotrol_terima_id = lp.lotrol_terima_id', 'left')
                ->join('detail_penerimaan dp', 'dp.det_terima_id = lpn.det_terima_id', 'left')
                ->join('penerimaan_pembelian pp', 'pp.terima_id = dp.terima_id', 'left')
                ->join('purchase_order po', 'po.po_id = pp.po_id', 'left')
                ->where('lp.det_pakai_id', $det_pakai_id)
                ->get()
                ->getResultArray();
        }

        // Ambil data penerimaan berdasarkan stok_id dan filter status transaksi
        $stok_id = $detail['stok_id'] ?? null;
        $lotrol_penerimaan = [];
        if ($stok_id) {
            $lotrol_penerimaan = $this->db->query("
                SELECT 
                lp.lot_terima AS lot,
                lp.rol_terima AS rol,
                po.nomor_po AS nomor_po,
                (lp.qty_lotrol_terima 
                    - COALESCE((SELECT SUM(lr.qty_lotrol_retur) FROM lotrol_retur lr 
                                WHERE lr.lot_terima_asal = lp.lot_terima 
                                AND lr.rol_terima_asal = lp.rol_terima), 0)
                    - COALESCE((SELECT SUM(lpem.qty_lotrol_pakai) FROM lotrol_pemakaian lpem 
                                WHERE lpem.lot_pakai = lp.lot_terima 
                                AND lpem.rol_pakai = lp.rol_terima), 0)
                ) AS sisa_kuantitas
            FROM lotrol_penerimaan lp
            JOIN detail_penerimaan dp ON dp.det_terima_id = lp.det_terima_id
            JOIN penerimaan_pembelian pp ON pp.terima_id = dp.terima_id
            LEFT JOIN purchase_order po ON po.po_id = pp.po_id
            WHERE dp.stok_id = ?
            HAVING sisa_kuantitas > 0
            ORDER BY  
                pp.nomor_bon ASC, 
                lp.lot_terima ASC, 
                CAST(SUBSTRING(lp.rol_terima, 2) AS UNSIGNED) ASC
            ", [$stok_id])->getResultArray();
        }

        return view('pemakaian/print', [
            'pemakaian' => $pemakaian,
            'detail_pemakaian' => $detail_pemakaian,
            'lotrol_pemakaian' => $lotrol_pemakaian,
            'lotrol_penerimaan' => $lotrol_penerimaan, // Menambahkan data penerimaan yang difilter
        ]);
    }

    public function search_stok()
    { 
        $keyword = $this->request->getGet('q');
        $stokModel = new \App\Models\StokBahanModel();

        $result = $stokModel->like('kode_stok', $keyword)
                            ->orLike('nama_barang', $keyword)
                            ->findAll(10);

        return $this->response->setJSON($result);
    }
}