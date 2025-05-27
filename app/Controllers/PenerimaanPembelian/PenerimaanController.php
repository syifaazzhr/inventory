<?php

namespace App\Controllers\PenerimaanPembelian;

use App\Models\PenerimaanModel;
use App\Models\DetailPenerimaanModel;
use App\Models\SupplierModel;
use App\Models\StokBahanModel;
use CodeIgniter\Controller;
use App\Models\PurchaseOrderModel;
use App\Models\LotRolPenerimaanModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PenerimaanController extends Controller
{
    protected $db;
    protected $penerimaanModel;
    protected $supplierModel;
    protected $detailPenerimaanModel;
    protected $lotRolPenerimaanModel;
    protected $purchaseOrderModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect(); 
        $this->penerimaanModel = new PenerimaanModel(); 
        $this->supplierModel = new SupplierModel();
        $this->detailPenerimaanModel = new DetailPenerimaanModel();
        $this->stokBahanModel = new StokBahanModel();
        $this->lotRolPenerimaanModel = new LotRolPenerimaanModel();
        $this->purchaseOrderModel = new PurchaseOrderModel();
    }

    public function index()
    {
        $penerimaanModel = new PenerimaanModel();
        $search = $this->request->getVar('search');
        $startDate = $this->request->getVar('start_date');
        $endDate = $this->request->getVar('end_date');
        $filterType = $this->request->getVar('filter_type');
        $perPage = 10;
        $page = $this->request->getVar('page') ?: 1;
        $month = $this->request->getVar('month');

        if ($month && preg_match('/^\d{4}-\d{2}$/', $month)) {
            $startOfMonth = $month . '-01';
            $endOfMonth = date('Y-m-t', strtotime($startOfMonth));
            $penerimaanModel = $penerimaanModel
                ->where('tgl_terima >=', $startOfMonth)
                ->where('tgl_terima <=', $endOfMonth);
        } elseif ($filterType === 'tgl_terima' && $startDate && $endDate) {
            $penerimaanModel = $penerimaanModel->where('tgl_terima >=', $startDate)
                                                ->where('tgl_terima <=', $endDate);
        } else {
            $startDate = '';
            $endDate = '';
        }

        if ($search) {
            $penerimaanModel = $penerimaanModel->like('nomor_bon', $search)
                                                ->orLike('nomor_po', $search);
        }

        $orderBy = $this->request->getVar('order_by') ?? 'tgl_terima';
        $order = $this->request->getVar('order') ?? 'asc';

        $allowedColumns = ['nomor_bon', 'tgl_terima', 'nomor_faktur', 'surat_jalan', 'tgl_kirim', 'tgl_jatuh_tempo', 'admin_terima', 'total_qty_terima', 'total_beli_terima', 'ppn_terima', 'total_beli_terima_ppn'];

        if (in_array($orderBy, $allowedColumns)) {
            $penerimaanModel->orderBy($orderBy, $order);
        } else {
            $penerimaanModel->orderBy('tgl_terima', 'asc');
        }

        $data = [
            'penerimaan_pembelian' => $penerimaanModel->getPenerimaan($search, $perPage, $page, $startDate, $endDate, $filterType),
            'pager' => $penerimaanModel->pager,
            'search' => $search,
            'orderBy' => $orderBy,
            'order' => $order,
            'start_date' => $startDate, 
            'end_date' => $endDate,     
            'filter_type' => $filterType
        ];

        return view('penerimaan_pembelian/index', $data);
    }

    public function show($id)
    {
        $penerimaanModel = new PenerimaanModel();
        $supplierModel = new SupplierModel();
        $purchaseOrderModel = new PurchaseOrderModel();

        $penerimaan = $penerimaanModel->find($id);
        if (!$penerimaan) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Penerimaan Pembelian tidak ditemukan.');
        }

        $supplier = $supplierModel->find($penerimaan['supplier_id']);
        $detail_penerimaan = $penerimaanModel->getDetailWithStock($id);
        $purchase_order = $purchaseOrderModel->find($penerimaan['po_id']);
        $terima_id = $penerimaan['terima_id']; 
        $det_terima_id = $detail_penerimaan[0]['det_terima_id'];
        $lotRolPenerimaanData = $this->lotRolPenerimaanModel->getLotRolByTerimaIdAndDetId($terima_id, $det_terima_id);

        return view('penerimaan_pembelian/show', [
            'penerimaan' => $penerimaan,
            'supplier' => $supplier,
            'detail_penerimaan' => $detail_penerimaan,
            'purchase_order' => $purchase_order,
            'lot_rol' => $lotRolPenerimaanData 
        ]);
    }

    public function create()
    {
        $stokModel = new \App\Models\StokBahanModel();
        $supplierModel = new \App\Models\SupplierModel();
        $poModel = new \App\Models\PurchaseOrderModel();

        $stok_list = $stokModel->findAll();
        $supplier_list = $supplierModel->findAll();
        $tanggalHariIni = date('Y-m-d');
        $nomor_bon = $this->penerimaanModel->generateNomorBon($tanggalHariIni);

        $po_list = [];
        foreach ($supplier_list as $supplier) {
            $po_list[$supplier['supplier_id']] = $poModel->where('supplier_id', $supplier['supplier_id'])->findAll();
        }

        return view('penerimaan_pembelian/create', [
            'nomor_bon' => $nomor_bon,
            'stok_list' => $stok_list,
            'supplier_list' => $supplier_list,
            'po_list' => $po_list,
        ]);
    }

    public function store()
    {
        // dd($this->request->getPost());

        $rules = [
            'tgl_terima' => 'permit_empty|valid_date[Y-m-d]',
            'supplier_id' => 'required|integer',
            'po_id' => 'required|integer',
            'nomor_faktur' => 'permit_empty|string',
            'surat_jalan' => 'permit_empty|string',
            'tgl_kirim' => 'permit_empty|valid_date[Y-m-d]',
            'tgl_jatuh_tempo' => 'permit_empty|valid_date[Y-m-d]',
            'stok_id' => 'required',
            'total_kuantitas' => 'required',
            'jumlah_harga_beli' => 'required',
        ];        

        if (!$this->validate($rules)) {
            dd($this->validator->getErrors());
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $this->db->transStart();
 
        $penerimaanModel = new PenerimaanModel();
        $tanggalTerima = $this->request->getPost('tgl_terima'); // Ambil tanggal penerimaan dari request
        $nomor_bon = $this->request->getPost('nomor_bon');

        $totalQtyTerima = array_sum($this->request->getPost('total_kuantitas'));
        $jumlahHargaList = $this->request->getPost('jumlah_harga_beli');
        $jumlahHargaList = array_map(function($harga) {
            $harga = str_replace(',', '', $harga);
            return (float) $harga;
        }, $jumlahHargaList);          
        $totalHargaTerima = array_sum($jumlahHargaList);
        $ppn = round($totalHargaTerima * 0.11);  
        $totalSetelahPPN = $totalHargaTerima + $ppn;

        $penerimaanData = [
            'nomor_bon' => $nomor_bon,
            'tgl_terima' => $this->request->getPost('tgl_terima'),
            'supplier_id' => (int) $this->request->getPost('supplier_id'),
            'po_id' => (int) $this->request->getPost('po_id'),
            'nomor_faktur' => $this->request->getPost('nomor_faktur'),
            'surat_jalan' => $this->request->getPost('surat_jalan'),
            'tgl_kirim' => $this->request->getPost('tgl_kirim'),
            'tgl_jatuh_tempo' => $this->request->getPost('tgl_jatuh_tempo'),
            'total_qty_terima' => $totalQtyTerima,
            'total_beli_terima' => $totalHargaTerima,
            'ppn_terima' => $ppn,
            'total_beli_terima_ppn' => $totalSetelahPPN,
            'admin_terima' => auth()->user()->username
        ]; 

        $terima_id = $this->penerimaanModel->insert($penerimaanData, true);
        $kodeStokList = $this->request->getPost('stok_id');

        // Simpan ke tabel detail_penerimaan
        $detailPenerimaanModel = new DetailPenerimaanModel();
        $kodeStokList = $this->request->getPost('stok_id');
        $totalKuantitasList = $this->request->getPost('total_kuantitas') ?? [];
        $jumlahHargaList = $this->request->getPost('jumlah_harga_beli') ?? [];

        foreach ($kodeStokList as $index => $stok_id) {
            $qty = floatval(str_replace(',', '.', $totalKuantitasList[$index]));
            $jumlah = (float) str_replace(',', '', $jumlahHargaList[$index]);
        
            $detailPenerimaanData = [
                'terima_id' => $terima_id,
                'stok_id' => (int) $stok_id,
                'qty_det_terima' => $qty,
                'jumlah_det_terima' => $jumlah // pastikan nilai numeric
            ];
            $detailPenerimaanModel->insert($detailPenerimaanData);
        }        

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return redirect()->back()->with('msg', 'Gagal menyimpan data!')->with('error', true);
        }

        $totalData = $penerimaanModel->countAll();
        $dataPerPage = 10; // ganti sesuai jumlah data per halaman kamu
        $lastPage = ceil($totalData / $dataPerPage);

        return redirect()->to(base_url('admin/penerimaan_pembelian?page=' . $lastPage))->with('msg', 'Data berhasil disimpan!');
    } 

    public function edit($id)
    {
        $penerimaanModel = new PenerimaanModel();
        $supplierModel = new SupplierModel();
        $stokModel = new StokBahanModel();
        $poModel = new PurchaseOrderModel();
        $detailPenerimaanModel = new DetailPenerimaanModel();

        $penerimaan = $penerimaanModel->find($id);
        if (!$penerimaan) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Penerimaan Pembelian tidak ditemukan.');
        }

        $supplierList = $supplierModel->findAll();
        $selectedSupplier = $supplierModel->find($penerimaan['supplier_id']);

        $stok_list = $this->stokBahanModel->getStokByPO($penerimaan['po_id']);

        $detail_penerimaan = $detailPenerimaanModel->where('terima_id', $id)->findAll();

        foreach ($detail_penerimaan as &$detail) {
            if (isset($detail['stok_id']) && $detail['stok_id']) {
                $stok = $stokModel->find($detail['stok_id']);  // Ambil stok berdasarkan stok_id
                if ($stok) {
                    $detail['kode_stok'] = $stok['kode_stok'];  // Menambahkan kode_stok ke dalam detail penerimaan
                    $detail['nama_barang'] = $stok['nama_barang'];  // Menambahkan nama barang ke dalam detail penerimaan
                } else {
                    $detail['kode_stok'] = 'Kode Stok Tidak Ditemukan';  // Jika stok_id tidak ada
                    $detail['nama_barang'] = 'Nama Barang Tidak Ditemukan';  // Jika stok_id tidak ada
                }
            }
        }    

        $po_list = $poModel->findAll();
        $totalKuantitasPenerimaan = array_sum(array_column($detail_penerimaan, 'qty_det_terima'));
        $totalHargaBeliPenerimaan = array_sum(array_column($detail_penerimaan, 'jumlah_det_terima'));
        $ppnPenerimaan = $totalHargaBeliPenerimaan * 0.11;
        $totalHargaBeliPenerimaanPPN = $totalHargaBeliPenerimaan + $ppnPenerimaan;

        return view('penerimaan_pembelian/edit', [
            'nomor_bon' => $penerimaan['nomor_bon'],
            'detail_penerimaan' => $detail_penerimaan,  // Pastikan detail_penerimaan berisi kode_stok dan nama_barang
            'stok_list' => $stok_list,
            'penerimaan' => $penerimaan,
            'selected_po_id' => $penerimaan['po_id'],
            'supplier_list' => $supplierList,
            'selected_supplier' => $selectedSupplier,
            'po_list' => $po_list,
            'total_kuantitas_penerimaan' => $totalKuantitasPenerimaan,
            'total_harga_beli_penerimaan' => $totalHargaBeliPenerimaan,
            'ppn_penerimaan' => $ppnPenerimaan,
            'total_harga_beli_penerimaan_ppn' => $totalHargaBeliPenerimaanPPN,
            'admin_terima' => auth()->user()->username
        ]);
    }

    public function update($id)
    {
        // Validasi inputan form
        $rules = [
            'tgl_terima' => 'permit_empty|valid_date[Y-m-d]',
            'supplier_id' => 'required|integer',
            'po_id' => 'required|integer',
            'nomor_faktur' => 'permit_empty|string',
            'surat_jalan' => 'permit_empty|string',
            'tgl_kirim' => 'permit_empty|valid_date[Y-m-d]',
            'tgl_jatuh_tempo' => 'permit_empty|valid_date[Y-m-d]',
            'stok_id' => 'required', 
            'stok_id.*' => 'required|integer', 
            'total_kuantitas' => 'required',
            'jumlah_harga_beli' => 'required'
        ];

        if (!$this->validate($rules)) {
            dd($this->validator->getErrors()); // Memeriksa kesalahan validasi
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $this->db->transStart();

        // Update data penerimaan
        $penerimaanModel = new PenerimaanModel();
        $tanggalTerima = $this->request->getPost('tgl_terima');
        $totalQtyTerima = array_sum($this->request->getPost('total_kuantitas'));
        $nomor_bon = $this->request->getPost('nomor_bon'); 

        $jumlahHargaList = $this->request->getPost('jumlah_harga_beli');
        $jumlahHargaList = array_map(function($harga) {
            $harga = str_replace(',', '', $harga); // hapus koma ribuan
            return (float) $harga;
        }, $jumlahHargaList);               

        $totalHargaTerima = round(array_sum($jumlahHargaList)); 
        $ppn = round($totalHargaTerima * 0.11);  
        $totalSetelahPPN = round($totalHargaTerima + $ppn);

        $penerimaanData = [
            'nomor_bon' => $nomor_bon,
            'tgl_terima' => $this->request->getPost('tgl_terima'),
            'supplier_id' => (int) $this->request->getPost('supplier_id'),
            'po_id' => (int) $this->request->getPost('po_id'),
            'nomor_faktur' => $this->request->getPost('nomor_faktur'),
            'surat_jalan' => $this->request->getPost('surat_jalan'),
            'tgl_kirim' => $this->request->getPost('tgl_kirim'),
            'tgl_jatuh_tempo' => $this->request->getPost('tgl_jatuh_tempo'),
            'total_qty_terima' => $totalQtyTerima,
            'total_beli_terima' => $totalHargaTerima,
            'ppn_terima' => $ppn,
            'total_beli_terima_ppn' => $totalSetelahPPN,
            'admin_terima' => auth()->user()->username
        ];

        // Update data penerimaan di tabel penerimaan
        $penerimaanModel->update($id, $penerimaanData);

        // Update data detail penerimaan
        $detailPenerimaanModel = new DetailPenerimaanModel();
        $kodeStokList = $this->request->getPost('stok_id');
        $totalKuantitasList = $this->request->getPost('total_kuantitas') ?? [];
        $jumlahHargaList = $this->request->getPost('jumlah_harga_beli') ?? [];

        // Hapus detail penerimaan lama
        $detailPenerimaanModel->where('terima_id', $id)->delete();

        $kodeStokList = $this->request->getPost('stok_id') ?? [];
        if (empty($kodeStokList)) {
            return redirect()->back()->with('msg', 'Kode Stok tidak boleh kosong!')->with('error', true);
        }

        foreach ($kodeStokList as $index => $stok_id) {
            $qty = floatval(str_replace(',', '.', $totalKuantitasList[$index]));
            $jumlah = (float) str_replace(',', '', $jumlahHargaList[$index]);
        
            $detailPenerimaanData = [
                'terima_id' => $id,
                'stok_id' => (int) $stok_id,
                'qty_det_terima' => $qty,
                'jumlah_det_terima' => $jumlah // fix penulisan angka
            ];
            $detailPenerimaanModel->insert($detailPenerimaanData);
        }        

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return redirect()->back()->with('msg', 'Gagal memperbarui data!')->with('error', true);
        }

        return redirect()->to(base_url('admin/penerimaan_pembelian/' . $id))->with('msg', 'Data berhasil diperbarui!');
    }

    public function delete($id)
    {
        // Check if the retur exists
        $penerimaan = $this->penerimaanModel->find($id);
        if (!$penerimaan) {
            return redirect()->to('/admin/penerimaan_pembelian')->with('msg', 'Data tidak ditemukan!')->with('error', true);
        }
 
        // Delete the retur data
        $this->penerimaanModel->delete($id);

        // Optionally delete associated detail retur
        $this->detailPenerimaanModel->where('terima_id', $id)->delete();

        return redirect()->to('/admin/penerimaan_pembelian')->with('msg', 'Data berhasil dihapus!');
    }

    public function downloadExcel()
    {
        // Fetch the penerimaan and details
        $penerimaanModel = new PenerimaanModel();
        $detailPenerimaanModel = new DetailPenerimaanModel();

        // Get all penerimaan data
        $penerimaan = $penerimaanModel->findAll();
        $penerimaanDetails = [];

        foreach ($penerimaan as $penerimaan_pembelian) {
            // Get details for each penerimaan
            $details = $detailPenerimaanModel->where('terima_id', $penerimaan_pembelian['terima_id'])->findAll();
            $penerimaanDetails[$penerimaan_pembelian['terima_id']] = $details;
        }

        // Create a new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set the headers for Excel
        $sheet->setCellValue('A1', 'Nomor PO')
            ->setCellValue('B1', 'Tanggal Terima')
            ->setCellValue('C1', 'Kode Supplier')
            ->setCellValue('D1', 'Nama Supplier')
            ->setCellValue('E1', 'Nomor PO')
            ->setCellValue('F1', 'Nomor Faktur') 
            ->setCellValue('G1', 'Surat Jalan')
            ->setCellValue('H1', 'Tanggal Kirim')
            ->setCellValue('I1', 'Tanggal Jatuh Tempo')
            ->setCellValue('J1', 'Total Kuantitas')
            ->setCellValue('K1', 'Total Harga Beli');

        $row = 2;
        foreach ($penerimaan as $penerimaan_pembelian) {
            $supplier = $this->supplierModel->find($penerimaan_pembelian['supplier_id']);
            $purchase_order = $this->purchaseOrderModel->find($penerimaan_pembelian['po_id']);

            $sheet->setCellValue('A' . $row, $penerimaan_pembelian['nomor_bon']);
            $sheet->setCellValue('B' . $row, $penerimaan_pembelian['tgl_terima']);
            $sheet->setCellValue('C' . $row, $supplier['kode_supplier']); // Supplier code
            $sheet->setCellValue('D' . $row, $supplier['nama_supplier']); // Supplier name
            $sheet->setCellValue('E' . $row, $purchase_order['nomor_po']); // Purchase order number (Keterangan)
            $sheet->setCellValue('F' . $row, $penerimaan_pembelian['nomor_faktur']);
            $sheet->setCellValue('G' . $row, $penerimaan_pembelian['surat_jalan']);
            $sheet->setCellValue('H' . $row, $penerimaan_pembelian['tgl_kirim']);
            $sheet->setCellValue('I' . $row, $penerimaan_pembelian['tgl_jatuh_tempo']);
            $sheet->setCellValue('J' . $row, $penerimaan_pembelian['total_qty_terima']); // Total Quantity
            $sheet->setCellValue('K' . $row, $penerimaan_pembelian['total_beli_terima']); // Total Price per item
            $row++;
        }

        // Write the file to the browser
        $writer = new Xlsx($spreadsheet);

        // Set the proper headers to make the browser treat the file as an Excel download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="penerimaan_pembelian.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

    public function printAll()
    {
        $data['penerimaan_pembelian'] = $this->penerimaanModel->findAll(); // Atau tambahkan filter tertentu
        return view('admin/penerimaan_pembelian/print_all', $data);
    }

    public function printOne($id)
    {
        $penerimaan = $this->penerimaanModel->find($id);
        if (!$penerimaan) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Data Penerimaan Pembelian tidak ditemukan.");
        }

        $supplier = $this->supplierModel->find($penerimaan['supplier_id']);
        $purchase_order = $this->purchaseOrderModel->find($penerimaan['po_id']);

        $detail_penerimaan = $this->detailPenerimaanModel
            ->select('detail_penerimaan.*, stok_bahan.kode_stok, stok_bahan.nama_barang, satuan.jenis_satuan')
            ->join('stok_bahan', 'stok_bahan.stok_id = detail_penerimaan.stok_id', 'left')
            ->join('satuan', 'satuan.satuan_id = stok_bahan.satuan_id', 'left')
            ->where('detail_penerimaan.terima_id', $id)
            ->findAll();

        $det_terima_id = $detail_penerimaan[0]['det_terima_id'] ?? null;

        $lotrol_penerimaan = $this->db->table('lotrol_penerimaan')
            ->select('lotrol_penerimaan.lot_terima, lotrol_penerimaan.rol_terima, lotrol_penerimaan.qty_lotrol_terima, detail_penerimaan.stok_id, detail_penerimaan.qty_det_terima, stok_bahan.kode_stok, stok_bahan.nama_barang')
            ->join('detail_penerimaan', 'lotrol_penerimaan.det_terima_id = detail_penerimaan.det_terima_id')
            ->join('stok_bahan', 'stok_bahan.stok_id = detail_penerimaan.stok_id')
            ->where('detail_penerimaan.terima_id', $id)
            ->get()->getResultArray();

        if (empty($lotrol_penerimaan)) {
            $lotrol_penerimaan = [];
        }

        return view('penerimaan_pembelian/print', [
            'penerimaan' => $penerimaan,
            'supplier' => $supplier,
            'purchase_order' => $purchase_order,
            'detail_penerimaan' => $detail_penerimaan,
            'lotrol_penerimaan' => $lotrol_penerimaan
        ]);
    }

    public function stokByPO($po_id)
    {
        $stokList = $this->stokBahanModel->getStokByPO($po_id);
        return $this->response->setJSON($stokList);
    }

    public function search_supplier()
    {
        $term = $this->request->getGet('q');
        $supplierModel = new SupplierModel();

        $results = $supplierModel
            ->like('nama_supplier', $term)
            ->findAll(10);

        $data = array_map(function ($row) {
            return [
                'id' => $row['supplier_id'],
                'nama_supplier' => $row['nama_supplier'],
                'kode_supplier' => $row['kode_supplier']
            ];
        }, $results);

        return $this->response->setJSON($data);
    }

    public function poBySupplier($supplier_id)
    {
        $poModel = new PurchaseOrderModel();

        $poList = $poModel->where('supplier_id', $supplier_id)->findAll();

        $data = array_map(function ($po) {
            return [
                'po_id' => $po['po_id'],
                'nomor_po' => $po['nomor_po']
            ];
        }, $poList);

        return $this->response->setJSON($data);
    }

    public function search_stok()
    {
        $keyword = $this->request->getGet('q');
        $stokModel = new \App\Models\StokBahanModel();

        $stokList = $stokModel->like('kode_stok', $keyword)
                            ->orLike('nama_barang', $keyword)
                            ->findAll(10);

        return $this->response->setJSON($stokList);
    }
}