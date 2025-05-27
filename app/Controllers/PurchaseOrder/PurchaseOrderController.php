<?php

namespace App\Controllers\PurchaseOrder;

use App\Models\PurchaseOrderModel;
use App\Models\StokBahanModel;
use App\Models\SupplierModel;
use App\Models\DetailPOModel;
use CodeIgniter\Controller;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PurchaseOrderController extends Controller
{
    protected $db; 
    protected $purchaseOrderModel;
    protected $supplierModel;
    protected $detailPoModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect(); 
        $this->purchaseOrderModel = new PurchaseOrderModel();
        $this->supplierModel = new SupplierModel();
        $this->detailPoModel = new DetailPoModel();
        $this->stokBahanModel = new StokBahanModel();
    }
 
    public function index()
    {
        $purchaseOrderModel = new PurchaseOrderModel();
        $search = $this->request->getVar('search');
        $perPage = 50;
        $page = $this->request->getVar('page') ?: 1;
        $startDate = $this->request->getVar('start_date');
        $endDate = $this->request->getVar('end_date');
        $filterType = $this->request->getVar('filter_type'); 
        $month = $this->request->getVar('month');

        if ($month && preg_match('/^\d{4}-\d{2}$/', $month)) {
            $startOfMonth = $month . '-01';
            $endOfMonth = date('Y-m-t', strtotime($startOfMonth));
            $purchaseOrderModel = $purchaseOrderModel
                ->where('tgl_order >=', $startOfMonth)
                ->where('tgl_order <=', $endOfMonth);
        } elseif ($filterType === 'tgl_order' && $startDate && $endDate) {
            $purchaseOrderModel = $purchaseOrderModel->where('tgl_order >=', $startDate)
                                                    ->where('tgl_order <=', $endDate);
        } elseif ($filterType === 'max_tgl_kirim' && $startDate && $endDate) {
            $purchaseOrderModel = $purchaseOrderModel->where('max_tgl_kirim >=', $startDate)
                                                    ->where('max_tgl_kirim <=', $endDate);
        } else {
            $startDate = '';
            $endDate = '';
        }

        if ($search) {
            $purchaseOrderModel = $purchaseOrderModel->like('nomor_po', $search);
        }

        $orderBy = $this->request->getVar('order_by') ?? 'tgl_order';
        $order = $this->request->getVar('order') ?? 'asc';
        $allowedColumns = ['nomor_po', 'tgl_order', 'max_tgl_kirim', 'nomor_ws', 'nomor_art', 'kode_supplier', 'nama_supplier', 'admin_po', 'total_qty', 'jml_total_po'];

        if (in_array($orderBy, $allowedColumns)) {
            $purchaseOrderModel->orderBy($orderBy, $order);
        } else {
            $purchaseOrderModel->orderBy('tgl_order', 'asc');
        }

        $data = [
            'purchase_orders' => $purchaseOrderModel->getPurchaseOrders($search, $perPage, $page, $startDate, $endDate, $filterType),
            'pager' => $purchaseOrderModel->pager,
            'search' => $search,
            'orderBy' => $orderBy,
            'order' => $order,
            'start_date' => $startDate, 
            'end_date' => $endDate,     
            'filter_type' => $filterType,
        ];

        return view('purchase_order/index', $data);
    }

    public function show($id)
    {
        $purchaseOrderModel = new PurchaseOrderModel();
        $supplierModel = new SupplierModel();
        $detailPOModel = new DetailPOModel();

        $purchase_order = $purchaseOrderModel->find($id);
        if (!$purchase_order) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Purchase Order tidak ditemukan.');
        } 

        $supplier = $supplierModel->find($purchase_order['supplier_id']);
        $detail_po = $purchaseOrderModel->getDetailWithStock($id);

        return view('purchase_order/show', [
            'purchase_order' => $purchase_order,
            'supplier' => $supplier,
            'detail_po' => $detail_po
        ]);
    }

    public function create()
    {
        $stokModel = new \App\Models\StokBahanModel();
        $supplierModel = new \App\Models\SupplierModel();
        $purchaseOrderModel = new \App\Models\PurchaseOrderModel();

        $stok_list = $stokModel->findAll();
        $supplier_list = $supplierModel->findAll();

        $tanggalOrder = date('Y-m-d'); 
        $latestPO = $this->purchaseOrderModel->generateNomorPO($tanggalOrder);

        return view('purchase_order/create', [
            'nomor_po' => $latestPO,
            'stok_list' => $stok_list,
            'supplier_list' => $supplier_list
        ]);
    }

    public function store()
    {
        $rules = [
            'supplier_id' => 'required|integer',
            'tgl_order' => 'permit_empty|valid_date[Y-m-d]',
            'max_tgl_kirim' => 'permit_empty|valid_date[Y-m-d]',
            'nomor_ws' => 'permit_empty|string',
            'nomor_art' => 'permit_empty|string',
            'kode_stok' => 'required',
            'ket_po' => 'permit_empty|string',
            'admin_po' => 'required|string',
            'total_kuantitas' => 'required',
            'jumlah_harga_beli' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $this->db->transStart();

        $purchaseOrderModel = new PurchaseOrderModel();
        $tanggalOrder = $this->request->getPost('tgl_order'); // Ambil tanggal order dari request
        $nomor_po = $purchaseOrderModel->generateNomorPO($tanggalOrder);

        $totalQtyPO = array_sum($this->request->getPost('total_kuantitas'));
        $jumlahHargaList = $this->request->getPost('jumlah_harga_beli');
        $jumlahHargaList = array_map(function($harga) {
            return (float) preg_replace('/[^\d.]/', '', $harga);
        }, $jumlahHargaList);
        $totalHargaPO = array_sum($jumlahHargaList);
        $ppn = round($totalHargaPO * 0.11);  
        $totalSetelahPPN = $totalHargaPO + $ppn;

        $purchaseOrderData = [
            'nomor_po' => $nomor_po,
            'supplier_id' => (int) $this->request->getPost('supplier_id'),
            'tgl_order' => $this->request->getPost('tgl_order'),
            'max_tgl_kirim' => $this->request->getPost('max_tgl_kirim'),
            'nomor_ws' => $this->request->getPost('nomor_ws'),
            'nomor_art' => $this->request->getPost('nomor_art'),
            'ket_po' => $this->request->getPost('ket_po'),
            'total_qty' => $totalQtyPO,
            'jml_total_po' => $totalHargaPO,
            'ppn_po' => $ppn,
            'jml_total_po_ppn' => $totalSetelahPPN,
            'admin_po' => auth()->user()->username
        ];

        $po_id = $purchaseOrderModel->insert($purchaseOrderData, true);

        $detailPOModel = new DetailPOModel();
        $kodeStokList = $this->request->getPost('kode_stok');
        $totalKuantitasList = $this->request->getPost('total_kuantitas') ?? [];
        $jumlahHargaList = $this->request->getPost('jumlah_harga_beli') ?? [];


        foreach ($kodeStokList as $index => $stok_id) {
            $detailPOData = [
                'po_id' => $po_id,
                'stok_id' => (int) $stok_id,
                'qty_detpo' => $totalKuantitasList[$index],
                'jumlah_detpo' => $jumlahHargaList[$index]
            ];
            $detailPOModel->insert($detailPOData);
        }

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return redirect()->back()->withInput()->with('msg', 'Gagal menyimpan data!')->with('error', true);
        }

        $totalData = $purchaseOrderModel->countAll();
        $dataPerPage = 50;
        $lastPage = ceil($totalData / $dataPerPage);
        
        return redirect()->to(base_url('admin/purchase_order?page=' . $lastPage))->with('msg', 'Data berhasil disimpan!');
    }

    public function edit($id)
    {
        $purchaseOrder = $this->purchaseOrderModel->find($id);

        if (!$purchaseOrder) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Purchase Order not found");
        }

        $supplierList = $this->supplierModel->findAll();
        $selectedSupplier = $this->supplierModel->find($purchaseOrder['supplier_id']); 
        $stokList = $this->stokBahanModel->getStokBySupplier($purchaseOrder['supplier_id']);

        $purchaseOrderDetails = $this->detailPoModel
            ->select('detail_po.*, stok_bahan.kode_stok, stok_bahan.nama_barang')
            ->join('stok_bahan', 'stok_bahan.stok_id = detail_po.stok_id', 'left')
            ->where('detail_po.po_id', $id)
            ->findAll();

        $totalKuantitasPO = array_sum(array_column($purchaseOrderDetails, 'qty_detpo'));
        $totalHargaBeliPO = array_sum(array_column($purchaseOrderDetails, 'jumlah_detpo'));
        $ppnPO = $totalHargaBeliPO * 0.11;
        $totalHargaBeliPOPPN = $totalHargaBeliPO + $ppnPO;

        return view('purchase_order/edit', [
            'purchase_order' => $purchaseOrder,
            'supplier_list' => $supplierList,
            'stok_list' => $stokList,
            'purchase_order_details' => $purchaseOrderDetails,
            'selected_supplier' => $selectedSupplier,
            'total_kuantitas_po' => $totalKuantitasPO,
            'total_harga_beli_po' => $totalHargaBeliPO,
            'ppn_po' => $ppnPO,
            'total_harga_beli_po_ppn' => $totalHargaBeliPOPPN,
            'admin_po' => auth()->user()->username
        ]);
    }

    public function update($id)
    {
        $purchaseOrder = $this->purchaseOrderModel->find($id);
        if (!$purchaseOrder) {
            return redirect()->back()->with('msg', 'Purchase Order tidak ditemukan!')->with('error', true);
        }

        $rules = [
            'tgl_order' => 'permit_empty|valid_date[Y-m-d]',
            'max_tgl_kirim' => 'permit_empty|valid_date[Y-m-d]',
            'nomor_ws' => 'permit_empty|string',
            'nomor_art' => 'permit_empty|string',
            'supplier_id' => 'required|integer',
            'ket_po' => 'permit_empty|string',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $this->db->transStart();

        $totalKuantitasList = $this->request->getPost('total_kuantitas');
        $jumlahHargaList = array_map(function ($harga) {
            return (float) preg_replace('/[^\d.]/', '', $harga);
        }, $this->request->getPost('jumlah_harga_beli'));

        $totalQtyPO = array_sum($totalKuantitasList);
        $totalHargaPO = array_sum($jumlahHargaList);
        $ppn = round($totalHargaPO * 0.11);  
        $totalSetelahPPN = $totalHargaPO + $ppn;

        $data = [
            'tgl_order' => $this->request->getPost('tgl_order'),
            'max_tgl_kirim' => $this->request->getPost('max_tgl_kirim'),
            'nomor_ws' => $this->request->getPost('nomor_ws'),
            'nomor_art' => $this->request->getPost('nomor_art'),
            'supplier_id' => (int) $this->request->getPost('supplier_id'),
            'ket_po' => $this->request->getPost('ket_po'),
            'admin_po' => auth()->user()->username,
            'total_qty' => $totalQtyPO,
            'jml_total_po' => $totalHargaPO,
            'ppn_po' => $ppn,
            'jml_total_po_ppn' => $totalSetelahPPN
        ];

        $this->purchaseOrderModel->update($id, $data);

        if (!$this->purchaseOrderModel->find($id)) {
            $this->db->transRollback();
            return redirect()->back()->with('msg', 'Gagal memperbarui data!')->with('error', true);
        } 

        $this->detailPoModel->where('po_id', $id)->delete();

        $kodeStokList = $this->request->getPost('kode_stok');
        foreach ($kodeStokList as $index => $stok_id) {
            $detailPOData = [
                'po_id' => $id,
                'stok_id' => (int) $stok_id,
                'qty_detpo' => $totalKuantitasList[$index],
                'jumlah_detpo' => $jumlahHargaList[$index]
            ];
            $this->detailPoModel->insert($detailPOData);
        }

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return redirect()->back()->with('msg', 'Gagal memperbarui data!')->with('error', true);
        }

        return redirect()->to(base_url('admin/purchase_order/' . $id))->with('msg', 'Data berhasil diperbarui!');
    }

    public function delete($id)
    {
        $purchaseOrder = $this->purchaseOrderModel->find($id);
        if (!$purchaseOrder) {
            return redirect()->to('/admin/purchase_order')->with('msg', 'Data tidak ditemukan!')->with('error', true);
        }
 
        $this->purchaseOrderModel->delete($id);
        $this->detailPoModel->where('po_id', $id)->delete();

        return redirect()->to('/admin/purchase_order')->with('msg', 'Data berhasil dihapus!');
    }

    public function downloadExcel()
    {
        $purchaseOrderModel = new PurchaseOrderModel();
        $detailPOModel = new DetailPOModel();
        $purchaseOrders = $purchaseOrderModel->findAll();
        $purchaseOrderDetails = [];

        foreach ($purchaseOrders as $purchaseOrder) {
            $details = $detailPOModel->where('po_id', $purchaseOrder['po_id'])->findAll();
            $purchaseOrderDetails[$purchaseOrder['po_id']] = $details;
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Nomor PO')
            ->setCellValue('B1', 'Tanggal Order')
            ->setCellValue('C1', 'Nomor WS')
            ->setCellValue('D1', 'Kirim Sebelum')
            ->setCellValue('E1', 'Kode Supplier')
            ->setCellValue('F1', 'Nama Supplier')
            ->setCellValue('G1', 'Keterangan')
            ->setCellValue('H1', 'Total Kuantitas')
            ->setCellValue('I1', 'Total Harga Beli');

        $row = 2; 
        foreach ($purchaseOrders as $purchaseOrder) {
            $supplier = $this->supplierModel->find($purchaseOrder['supplier_id']);
            $sheet->setCellValue('A' . $row, $purchaseOrder['nomor_po']);
            $sheet->setCellValue('B' . $row, $purchaseOrder['tgl_order']);
            $sheet->setCellValue('C' . $row, $purchaseOrder['nomor_ws']);
            $sheet->setCellValue('C' . $row, $purchaseOrder['nomor_art']);
            $sheet->setCellValue('D' . $row, $purchaseOrder['max_tgl_kirim']);
            $sheet->setCellValue('E' . $row, $supplier['kode_supplier']); // Supplier code
            $sheet->setCellValue('F' . $row, $supplier['nama_supplier']); // Supplier name
            $sheet->setCellValue('G' . $row, $purchaseOrder['ket_po']); // Keterangan (from PO)
            $sheet->setCellValue('H' . $row, $purchaseOrder['total_qty']); // Total Quantity (from PO)
            $sheet->setCellValue('I' . $row, $purchaseOrder['jml_total_po']); // Total Price (from PO)
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="purchase_orders.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

    public function printAll()
    {
        $data['purchase_orders'] = $this->purchaseOrderModel->findAll(); // Atau tambahkan filter tertentu
        return view('admin/purchase_order/print_all', $data);
    }

    public function printOne($id)
    {
        $po = $this->purchaseOrderModel->find($id);
        if (!$po) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Purchase Order tidak ditemukan.");
        }

        // Ambil data supplier
        $supplier = $this->supplierModel->find($po['supplier_id']);
        $po['nama_supplier'] = $supplier['nama_supplier'] ?? '-';
        $po['alamat_supplier'] = $supplier['alamat_supplier'] ?? '-';

        $detail_po = $this->detailPoModel
            ->select('detail_po.*, stok_bahan.kode_stok, stok_bahan.nama_barang, stok_bahan.harga_beli, satuan.jenis_satuan')
            ->join('stok_bahan', 'stok_bahan.stok_id = detail_po.stok_id', 'left')
            ->join('satuan', 'satuan.satuan_id = stok_bahan.satuan_id', 'left')
            ->where('detail_po.po_id', $id)
            ->findAll();

        return view('purchase_order/print', [
            'purchase_order' => $po,
            'detail_po' => $detail_po
        ]);
    }  

    public function stokBySupplier($supplier_id)
    {
        $stokList = $this->stokBahanModel->getStokBySupplier($supplier_id);
        return $this->response->setJSON($stokList);
    }

    public function search_supplier()
    {
        $term = $this->request->getGet('q');
        $supplierModel = new SupplierModel();

        $results = $supplierModel
            ->like('nama_supplier', $term)
            ->findAll(50);

        $data = array_map(function ($row) {
            return [
                'id' => $row['supplier_id'],
                'nama_supplier' => $row['nama_supplier'],
                'kode_supplier' => $row['kode_supplier']
            ];
        }, $results);

        return $this->response->setJSON($data);
    }

    public function search_stok()
    {
        $keyword = $this->request->getGet('q');
        $stokModel = new \App\Models\StokBahanModel();

        $result = $stokModel->like('kode_stok', $keyword)
                            ->orLike('nama_barang', $keyword)
                            ->findAll(50);

        return $this->response->setJSON($result);
    }
}