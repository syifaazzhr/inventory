<?php

namespace App\Controllers\Retur;

use App\Models\ReturModel; 
use App\Models\DetailReturModel;
use App\Models\SupplierModel;
use App\Models\StokBahanModel;
use CodeIgniter\Controller;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReturController extends Controller
{
    protected $db;
    protected $returModel;
    protected $supplierModel;
    protected $detailReturModel;
    protected $stokBahanModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect(); 
        $this->returModel = new ReturModel();
        $this->stokBahanModel = new StokBahanModel();
        $this->supplierModel = new SupplierModel();
        $this->detailReturModel = new DetailReturModel();
    }

    public function index()
    {
        $returModel = new ReturModel();
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
            $returModel = $returModel
                ->where('tgl_retur >=', $startOfMonth)
                ->where('tgl_retur <=', $endOfMonth);
        } elseif ($filterType === 'tgl_retur' && $startDate && $endDate) {
            $returModel = $returModel->where('tgl_retur >=', $startDate)
                                    ->where('tgl_retur <=', $endDate);
        } else {
            $startDate = '';
            $endDate = '';
        }

        if ($search) {
            $returModel = $returModel->like('nomor_retur', $search);
        }

        $orderBy = $this->request->getVar('order_by') ?? 'tgl_retur';
        $order = $this->request->getVar('order') ?? 'asc';

        $allowedColumns = ['nomor_retur', 'tgl_retur', 'kode_supplier', 'nama_supplier', 'ket_retur', 'admin_retur', 'total_qty_retur', 'total_beli_retur'];
        if (in_array($orderBy, $allowedColumns)) {
            $returModel->orderBy($orderBy, $order);
        } else {
            $returModel->orderBy('tgl_retur', 'asc');
        }

        $data = [
            'retur' => $returModel->getRetur($search, $perPage, $page, $startDate, $endDate, $filterType),
            'pager' => $returModel->pager,
            'search' => $search,
            'orderBy' => $orderBy,
            'order' => $order,
            'start_date' => $startDate, 
            'end_date' => $endDate,     
            'filter_type' => $filterType,
        ];

        return view('retur/index', $data);
    }

    public function show($id)
    {
        // Models for Retur, Supplier, and DetailRetur (if any)
        $returModel = new ReturModel();
        $supplierModel = new SupplierModel();

        // Ambil data retur berdasarkan ID
        $retur = $returModel->find($id);
        if (!$retur) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Retur Pembelian tidak ditemukan.');
        }

        // Ambil data supplier berdasarkan supplier_id dari retur
        $supplier = $supplierModel->find($retur['supplier_id']);

        // Ambil data detail retur beserta stok barang
        $detail_retur = $returModel->getDetailWithStock($id);

        // Ambil retur_id dan det_retur_id dari detail_retur pertama
        $retur_id = $retur['retur_id']; // Ambil retur_id dari data retur
        $det_retur_id = $detail_retur[0]['det_retur_id']; // Ambil det_retur_id dari detail pertama

        // Prepare the data to pass to the view
        return view('retur/show', [
            'retur' => $retur,
            'supplier' => $supplier,
            'detail_retur' => $detail_retur,
        ]);
    }

    public function create()
    {
        $stokModel = new \App\Models\StokBahanModel();
        $supplierModel = new \App\Models\SupplierModel();
        
        // Fetching the stock list and supplier list from the models
        $stok_list = $this->stokBahanModel->findAll();
        $supplier_list = $this->supplierModel->findAll();

        // Generate a nomor_retur or leave it empty if not applicable
        $nomor_retur = $this->returModel->generateNomorRetur(date('Y-m-d'));

        // Passing data to the view
        return view('retur/create', [
            'nomor_retur' => $nomor_retur,
            'stok_list' => $stok_list,
            'supplier_list' => $supplier_list,
        ]);
    }

    public function store()
    {
        // dd($this->request->getPost());
        
        $rules = [
            'tgl_retur' => 'required|valid_date[Y-m-d]',
            'supplier_id' => 'required|integer',
            'kode_stok' => 'required',
            'total_kuantitas' => 'required',
            'jumlah_harga_beli' => 'required'
        ];

        if (!$this->validate($rules)) {
            dd($this->validator->getErrors());
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $this->db->transStart();

        // Generate nomor_retur based on the date
        $tgl_retur = $this->request->getPost('tgl_retur');
        $nomor_retur = $this->returModel->generateNomorRetur($tgl_retur);

        $totalQtyRetur = array_sum($this->request->getPost('total_kuantitas'));

        $jumlahHargaList = $this->request->getPost('jumlah_harga_beli');
        $jumlahHargaList = array_map(function($harga) {
            return (float) preg_replace('/[^\d.]/', '', $harga);
        }, $this->request->getPost('jumlah_harga_beli'));         
        $totalHargaRetur = array_sum($jumlahHargaList);
        $ppn = round($totalHargaRetur * 0.11);  
        $totalSetelahPPN = $totalHargaRetur + $ppn;

        // Save to the retur table
        $this->returModel->insert([
            'nomor_retur' => $nomor_retur,
            'tgl_retur' => $tgl_retur,
            'supplier_id' => $this->request->getPost('supplier_id'),
            'ket_retur' => $this->request->getPost('ket_retur'),
            'total_qty_retur' => $totalQtyRetur,
            'total_beli_retur' => $totalHargaRetur,
            'ppn_retur' => $ppn,
            'total_beli_retur_ppn' => $totalSetelahPPN,
            'admin_retur' => auth()->user()->username
        ]);

        $retur_id = $this->returModel->getInsertID();
        $detailReturModel = new DetailReturModel();
        $kodeStokList = $this->request->getPost('kode_stok');
        $totalKuantitasList = $this->request->getPost('total_kuantitas') ?? [];

        foreach ($kodeStokList as $index => $stok_id) {
            $detailReturData = [
                'retur_id' => $retur_id,
                'stok_id' => (int) $stok_id,
                'qty_det_retur' => $totalKuantitasList[$index],
                'jumlah_det_retur' => $jumlahHargaList[$index] // Sudah dibersihkan dan benar
            ];
            $detailReturModel->insert($detailReturData);
        }        

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return redirect()->back()->with('msg', 'Gagal menyimpan data!')->with('error', true);
        }

        $totalData = $this->returModel->countAll();
        $dataPerPage = 10; // ganti sesuai jumlah data per halaman kamu
        $lastPage = ceil($totalData / $dataPerPage);

        return redirect()->to(base_url('admin/retur?page=' . $lastPage))->with('msg', 'Data berhasil disimpan!');
    }

    public function edit($id) 
    {
        $returModel = new ReturModel();
        $supplierModel = new SupplierModel();
        $stokModel = new StokBahanModel();
        $detailReturModel = new DetailReturModel();

        $retur = $returModel->find($id);
        if (!$retur) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Retur Pembelian tidak ditemukan.');
        }

        $supplierList = $supplierModel->findAll();
        $selectedSupplier = $supplierModel->find($retur['supplier_id']);
        $stok_list = $stokModel->findAll();
        $detail_retur = $detailReturModel->where('retur_id', $id)->findAll();

        foreach ($detail_retur as &$detail) {
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
        
        $totalKuantitasRetur = array_sum(array_column($detail_retur, 'qty_det_retur'));
        $totalHargaBeliRetur = array_sum(array_column($detail_retur, 'jumlah_det_retur'));
        $ppnRetur = $totalHargaBeliRetur * 0.11;
        $totalHargaBeliReturPPN = $totalHargaBeliRetur + $ppnRetur;

        return view('retur/edit', [
            'nomor_retur' => $retur['nomor_retur'],
            'detail_retur' => $detail_retur,
            'retur' => $retur,
            'supplier_list' => $supplierList,
            'selected_supplier' => $selectedSupplier,
            'stok_list' => $stok_list,
            'total_kuantitas_retur' => $totalKuantitasRetur,
            'total_harga_beli_retur' => $totalHargaBeliRetur,
            'ppn_retur' => $ppnRetur,
            'total_harga_beli_retur_ppn' => $totalHargaBeliReturPPN,
            'admin_retur' => auth()->user()->username
        ]);
    }

    public function update($id)
    {
        // dd($this->request->getPost());

        $rules = [
            'tgl_retur' => 'required|valid_date[Y-m-d]',
            'supplier_id' => 'required',
            'kode_stok' => 'required',
            'kode_stok.*' => 'required|integer',
            'ket_retur' => 'required|string',
            'total_kuantitas' => 'required',
            'jumlah_harga_beli' => 'required'
        ];        

        if (!$this->validate($rules)) {
            dd($this->validator->getErrors());
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $this->db->transStart();

        // Fetch updated data from the request
        $returModel = new ReturModel();
        $tanggalRetur = $this->request->getPost('tgl_retur');
        $totalQtyRetur = array_sum($this->request->getPost('total_kuantitas'));
        $nomorRetur = $this->returModel->generateNomorRetur($this->request->getPost('tgl_retur')); // Ambil nomor retur dari input

        $jumlahHargaList = $this->request->getPost('jumlah_harga_beli');
        $jumlahHargaList = array_map(function($harga) {
            return (float) preg_replace('/[^\d.]/', '', $harga);
        }, $this->request->getPost('jumlah_harga_beli'));               
        $totalHargaRetur = array_sum($jumlahHargaList);
        $ppn = round($totalHargaRetur * 0.11);  
        $totalSetelahPPN = $totalHargaRetur + $ppn;

        $returData = [
            'nomor_retur' => $nomorRetur,
            'tgl_retur' => $this->request->getPost('tgl_retur'),
            'supplier_id' => (int) $this->request->getPost('supplier_id'),
            'ket_retur' => $this->request->getPost('ket_retur'),
            'total_qty_retur' => $totalQtyRetur,
            'total_beli_retur' => $totalHargaRetur,
            'ppn_retur' => $ppn,
            'total_beli_retur_ppn' => $totalSetelahPPN,
            'admin_retur' => auth()->user()->username
        ];

        // Update the return data in the retur table
        $returModel->update($id, $returData);

        // Update data detail retur
        $detailReturModel = new DetailReturModel();
        $kodeStokList = $this->request->getPost('kode_stok');
        $totalKuantitasList = $this->request->getPost('total_kuantitas');
        $jumlahHargaList = array_map(function ($harga) {
            return (float) str_replace(',', '', $harga);
        }, $this->request->getPost('jumlah_harga_beli'));        

        // Hapus detail retur lama
        $detailReturModel->where('retur_id', $id)->delete();

        $kodeStokList = $this->request->getPost('kode_stok') ?? [];
        if (empty($kodeStokList)) {
            return redirect()->back()->with('msg', 'Kode Stok tidak boleh kosong!')->with('error', true);
        }

        foreach ($kodeStokList as $index => $stok_id) {
            $detailReturData = [
                'retur_id' => $id,
                'stok_id' => (int) $stok_id,
                'qty_det_retur' => $totalKuantitasList[$index],
                'jumlah_det_retur' => $jumlahHargaList[$index]
            ];
            $detailReturModel->insert($detailReturData);
        }

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return redirect()->back()->with('msg', 'Gagal memperbarui data!')->with('error', true);
        }

        return redirect()->to(base_url('admin/retur/' . $id))->with('msg', 'Data berhasil diperbarui!');
    }

    public function delete($id)
    {
        // Check if the retur exists
        $retur = $this->returModel->find($id);
        if (!$retur) {
            return redirect()->to('/admin/retur')->with('msg', 'Data tidak ditemukan!')->with('error', true);
        }

        // Delete the retur data
        $this->returModel->delete($id);

        // Optionally delete associated detail retur
        $this->detailReturModel->where('retur_id', $id)->delete();

        return redirect()->to('/admin/retur')->with('msg', 'Data berhasil dihapus!');
    }

    public function downloadExcel()
    {
        // Fetch the retur and details
        $returModel = new ReturModel();
        $detailReturModel = new DetailReturModel();

        // Get all retur data
        $retur = $returModel->findAll();
        $returDetails = [];

        foreach ($retur as $retur_pembelian) {
            // Get details for each retur
            $details = $detailReturModel->where('retur_id', $retur_pembelian['retur_id'])->findAll();
            $returDetails[$retur_pembelian['retur_id']] = $details;
        }

        // Create a new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set the headers for Excel
        $sheet->setCellValue('A1', 'Nomor Retur')
            ->setCellValue('B1', 'Tanggal Retur')
            ->setCellValue('C1', 'Kode Supplier')
            ->setCellValue('D1', 'Nama Supplier')
            ->setCellValue('E1', 'Keterangan')
            ->setCellValue('F1', 'Admin')
            ->setCellValue('G1', 'Total Kuantitas')
            ->setCellValue('H1', 'Total Harga Beli');

        $row = 2; 
        foreach ($retur as $retur_pembelian) {
            $supplier = $this->supplierModel->find($retur_pembelian['supplier_id']);

            $sheet->setCellValue('A' . $row, $retur_pembelian['nomor_retur']);
            $sheet->setCellValue('B' . $row, $retur_pembelian['tgl_retur']);
            $sheet->setCellValue('C' . $row, $supplier['kode_supplier']);
            $sheet->setCellValue('D' . $row, $supplier['nama_supplier']);
            $sheet->setCellValue('E' . $row, $retur_pembelian['ket_retur']);
            $sheet->setCellValue('F' . $row, $retur_pembelian['admin_retur']);
            $sheet->setCellValue('G' . $row, $retur_pembelian['total_qty_retur']); 
            $sheet->setCellValue('H' . $row, $retur_pembelian['total_beli_retur']);
            $row++;
        }

        // Write the file to the browser
        $writer = new Xlsx($spreadsheet);

        // Set the proper headers to make the browser treat the file as an Excel download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="retur_pembelian.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

    public function printAll()
    {
        $data['retur'] = $this->returModel->findAll(); // Atau tambahkan filter tertentu
        return view('admin/retur/print_all', $data);
    }

    public function printOne($id)
    {
        $retur = $this->returModel->find($id);
        if (!$retur) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Data Retur tidak ditemukan.");
        }

        $supplier = $this->supplierModel->find($retur['supplier_id']);
        $retur['nama_supplier'] = $supplier['nama_supplier'] ?? '-';
        $retur['alamat_supplier'] = $supplier['alamat_supplier'] ?? '-';

        $detail_retur = $this->detailReturModel
            ->select('detail_retur.*, stok_bahan.kode_stok, stok_bahan.nama_barang, satuan.jenis_satuan')
            ->join('stok_bahan', 'stok_bahan.stok_id = detail_retur.stok_id', 'left')
            ->join('satuan', 'satuan.satuan_id = stok_bahan.satuan_id', 'left')
            ->where('detail_retur.retur_id', $id)
            ->findAll();
            
        $detail = $detail_retur[0] ?? null;
        $det_retur_id = $detail['det_retur_id'] ?? null;

        $lotrolReturModel = new \App\Models\LotRolReturModel();

        $lotrol_retur = [];

        if ($det_retur_id) {
            $lotrol_retur = $lotrolReturModel
                ->where('det_retur_id', $det_retur_id)
                ->findAll();
        }
 
        foreach ($lotrol_retur as &$row) {
        if (empty($row['nomor_po'])) {
            $db = \Config\Database::connect();
            $result = $db->table('lotrol_penerimaan')
                ->join('detail_penerimaan', 'detail_penerimaan.det_terima_id = lotrol_penerimaan.det_terima_id')
                ->join('penerimaan_pembelian', 'penerimaan_pembelian.terima_id = detail_penerimaan.terima_id')
                ->join('purchase_order', 'purchase_order.po_id = penerimaan_pembelian.po_id')
                ->select('purchase_order.nomor_po')
                ->where('lotrol_penerimaan.lot_terima', $row['lot_terima_asal'])
                ->where('lotrol_penerimaan.rol_terima', $row['rol_terima_asal'])
                ->get()
                ->getRowArray();

            $row['nomor_po'] = $result['nomor_po'] ?? '-';
        }
    }
    unset($row);

        return view('retur/print', [
            'retur' => $retur,
            'supplier' => $supplier,
            'detail_retur' => $detail_retur,
            'lotrol_retur' => $lotrol_retur
        ]);
    }

    public function search_supplier()
    {
        $keyword = $this->request->getGet('q');
        $supplierModel = new \App\Models\SupplierModel();

        $results = $supplierModel
            ->like('nama_supplier', $keyword)
            ->orLike('kode_supplier', $keyword)
            ->findAll(10);

        return $this->response->setJSON($results);
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