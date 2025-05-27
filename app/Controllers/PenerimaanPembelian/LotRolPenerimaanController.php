<?php

namespace App\Controllers\PenerimaanPembelian;

use App\Models\LotRolPenerimaanModel;
use App\Models\StokBahanModel;
use App\Models\PurchaseOrderModel;
use App\Models\PenerimaanModel;
use CodeIgniter\Controller;

class LotRolPenerimaanController extends Controller
{
    protected $db;
    protected $lotRolPenerimaanModel;
    protected $stokBahanModel;
    protected $purchaseOrderModel;
    protected $penerimaanModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect(); 
        $this->lotRolPenerimaanModel = new LotRolPenerimaanModel();
        $this->stokBahanModel = new StokBahanModel();
        $this->purchaseOrderModel = new PurchaseOrderModel();
        $this->penerimaanModel = new PenerimaanModel();
    }

    // Show all Lot & Roll records
    public function index()
    {
        $search = $this->request->getVar('search');
        $lotRolPenerimaanData = $this->lotRolPenerimaanModel->getLotRol($search);

        return view('penerimaan_pembelian/lot_rol/index', [
            'lot_rol_data' => $lotRolPenerimaanData,
            'search' => $search
        ]);
    }

    public function show($terima_id, $det_terima_id)
    {
        // Fetch the Lot & Roll data based on terima_id and det_terima_id
        $lotRolPenerimaanData = $this->lotRolPenerimaanModel->where('terima_id', $terima_id)
                                        ->where('det_terima_id', $det_terima_id)
                                        ->findAll();

        // Fetch related details: Nomor Bon, Nomor PO, Kode Stok, Nama Barang, and Total Kuantitas
        $penerimaanModel = new PenerimaanModel();
        $detailPenerimaanModel = new \App\Models\DetailPenerimaanModel();
        $stokBahanModel = new StokBahanModel(); // Model for stok_bahan table
        
        // Retrieve the penerimaan details
        $penerimaan = $penerimaanModel->find($terima_id);

        // Retrieve the specific detail penerimaan for the selected det_terima_id
        $detailPenerimaan = $detailPenerimaanModel->where('terima_id', $terima_id)
                                                    ->where('det_terima_id', $det_terima_id)
                                                    ->first(); // Use first() to get the specific row

        // Fetch related purchase order
        $purchaseOrderModel = new PurchaseOrderModel();
        $purchaseOrder = $purchaseOrderModel->find($penerimaan['po_id']);

        // Fetch the stok_bahan details related to the kode_stok in the penerimaan
        $stokDetail = null; // Set a default value
        if ($detailPenerimaan && isset($detailPenerimaan['stok_id'])) {
            $stokDetail = $stokBahanModel->find($detailPenerimaan['stok_id']);
        }

        // Prepare the data for the view
        return view('penerimaan_pembelian/lot_rol/show', [
            'lotrol_penerimaan' => $lotRolPenerimaanData, // Pass all lot_rol data
            'penerimaan' => $penerimaan, // Include penerimaan details
            'purchase_order' => $purchaseOrder, // Include purchase order data
            'detail_penerimaan' => $detailPenerimaan, // Include the details of the penerimaan
            'stok_detail' => $stokDetail, // Pass the stock details to the view
            'terima_id' => $terima_id,  // Pass terima_id to the view
            'det_terima_id' => $det_terima_id // Pass det_terima_id to the view
        ]);
    }

    public function create()
    {
        // Mengambil terima_id dan det_terima_id dari URL
        $terima_id = $this->request->getVar('terima_id');
        $det_terima_id = $this->request->getVar('det_terima_id');
        
        // Ambil data penerimaan
        $penerimaan = $this->penerimaanModel->find($terima_id);
        $nomor_po = $penerimaan ? $this->purchaseOrderModel->find($penerimaan['po_id'])['nomor_po'] : '';
        
        // Ambil detail penerimaan berdasarkan terima_id dan det_terima_id
        $detailPenerimaan = $this->lotRolPenerimaanModel->getDetailByTerimaIdAndDetId($terima_id, $det_terima_id);
        
        // Ambil daftar PO untuk dipilih dalam form
        $po_list = [];
        $terimaList = $this->penerimaanModel->findAll();
        if (!empty($terimaList)) {
            foreach ($terimaList as $terima) {
                $po_list[$terima['po_id']] = $this->purchaseOrderModel->where('po_id', $terima['po_id'])->findAll();
            }
        }
        
        // Data untuk dikirim ke view
        return view('penerimaan_pembelian/lot_rol/create', [
            'terima_list' => $terimaList,
            'po_list' => $po_list,
            'terima_id' => $terima_id,
            'det_terima_id' => $det_terima_id,
            'detail' => $detailPenerimaan,
            'penerimaan' => $penerimaan,
            'nomor_po' => $nomor_po
        ]);
    }

    public function store()
    {
        // Debugging untuk melihat data yang diposting
        // dd($this->request->getPost()); // Pastikan data sudah sesuai

        // Validasi input
        $rules = [
            'terima_id' => 'required|integer',
            'det_terima_id' => 'required|integer',
            'lot_terima' => 'required',
            'rol_terima' => 'required',  // Memastikan rol_terima adalah array
            'qty_lotrol_terima' => 'required' // Memastikan qty_lotrol_terima adalah array
        ];

        // Periksa validasi
        if (!$this->validate($rules)) {
            dd($this->validator->getErrors()); // Debugging jika validasi gagal
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        // Mulai transaksi database
        $this->db->transStart();

        // Ambil data yang dibutuhkan
        $terima_id = (int) $this->request->getPost('terima_id');
        $det_terima_id = (int) $this->request->getPost('det_terima_id');
        $lot_terima = $this->request->getPost('lot_terima'); 
        $rol_terima = $this->request->getPost('rol_terima'); 
        $qty_lotrol_terima = $this->request->getPost('qty_lotrol_terima'); 

        // Validasi panjang array untuk `rol_terima` dan `qty_lotrol_terima`
        if (count($rol_terima) !== count($qty_lotrol_terima)) {
            dd('Jumlah elemen `rol_terima` dan `qty_lotrol_terima` tidak sama.');
        }

        // // Cek apakah qty_lotrol_terima adalah angka yang valid
        // foreach ($qty_lotrol_terima as $index => $qty) {
        //     if (!is_numeric($qty) || $qty < 1) {
        //         dd('Qty is not valid: ' . $qty); // Debugging untuk mengecek qty
        //     }
        // }

        // Menyimpan data Lot & Roll ke dalam tabel
        foreach ($rol_terima as $index => $rol) {
            $lotRolPenerimaanData = [
                'terima_id' => $terima_id,
                'det_terima_id' => $det_terima_id,
                'lot_terima' => $lot_terima,
                'rol_terima' => $rol, // Menggunakan setiap elemen dalam array
                'qty_lotrol_terima' => $qty_lotrol_terima[$index] // Mengambil qty_lotrol_terima berdasarkan index
            ];

            // Cek apakah insert berhasil
            if (!$this->lotRolPenerimaanModel->insert($lotRolPenerimaanData)) {
                // Jika gagal, tampilkan error
                dd('Insert failed! Error: ' . print_r($this->lotRolPenerimaanModel->errors(), true));
            }
        }

        // Commit transaksi jika sukses
        $this->db->transComplete();

        // Periksa status transaksi
        if ($this->db->transStatus() === false) {
            dd('Transaction failed');  // Tampilkan pesan jika transaksi gagal
        }

        // Menambahkan pesan sukses ke session flashdata
        session()->setFlashdata('msg', 'Data berhasil ditambahkan!');

        // Jika transaksi berhasil, redirect ke halaman show dengan terima_id dan det_terima_id
        return redirect()->to(base_url('admin/penerimaan_pembelian/lot_rol/' . $terima_id . '/' . $det_terima_id))->with('msg', 'Data berhasil disimpan!');
    }

    public function edit($id)
{
    // Ambil data Lot & Roll berdasarkan ID
    $lotRol = $this->lotRolPenerimaanModel->find($id);
    if (!$lotRol) {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Lot & Roll tidak ditemukan.');
    }

    $terimaList = $this->penerimaanModel->findAll();
    $stokList = $this->stokBahanModel->findAll();

    // Ambil terima_id dan det_terima_id dari $lotRol
    $terima_id = $lotRol['terima_id'];
    $det_terima_id = $lotRol['det_terima_id'];

    // Ambil data penerimaan berdasarkan terima_id
    $penerimaan = $this->penerimaanModel->find($terima_id);
    $nomor_po = $penerimaan ? $this->purchaseOrderModel->find($penerimaan['po_id'])['nomor_po'] : '';

    // Ambil detail penerimaan atau stok terkait
    $detailPenerimaanModel = new \App\Models\DetailPenerimaanModel();
    $detail = $detailPenerimaanModel->where('terima_id', $terima_id)
                                    ->where('det_terima_id', $det_terima_id)
                                    ->first(); // Ambil detail berdasarkan terima_id dan det_terima_id

    // Ambil data LotRolDetails yang terkait dengan terima_id dan det_terima_id
    $lotRolDetails = $this->lotRolPenerimaanModel->where('terima_id', $terima_id)
                                       ->where('det_terima_id', $det_terima_id)
                                       ->findAll();

    // Ambil detail stok terkait, pastikan stok_id ada dalam detail penerimaan
    $stokDetail = null;
    if (isset($detail['stok_id'])) {
        $stokDetail = $this->stokBahanModel->find($detail['stok_id']);
    }

    return view('penerimaan_pembelian/lot_rol/edit', [
        'lotrol_penerimaan' => $lotRol,
        'terima_list' => $terimaList,
        'stok_list' => $stokList,
        'terima_id' => $terima_id,  // Pastikan variabel diteruskan ke view
        'det_terima_id' => $det_terima_id,
        'lotrol_id' => $id,
        'penerimaan' => $penerimaan,  // Pastikan data penerimaan diteruskan ke view
        'nomor_po' => $nomor_po,
        'detail' => $detail,
        'lotRolDetails' => $lotRolDetails,
        'stok_detail' => $stokDetail  // Pastikan stok_detail diteruskan ke view
    ]);
}

public function update($id)
{
    // dd($this->request->getPost());

    $rules = [
        'terima_id' => 'required|integer',
        'det_terima_id' => 'required|integer',
        'lot_terima' => 'required',
        'rol_terima' => 'required',
        'qty_lotrol_terima' => 'required'
    ];

    if (!$this->validate($rules)) {
        return redirect()->back()->withInput()->with('validation', $this->validator);
    }

    $this->db->transStart();

    $terima_id = (int) $this->request->getPost('terima_id');
    $det_terima_id = (int) $this->request->getPost('det_terima_id');
    $lot_terima = $this->request->getPost('lot_terima');
    $rol_terima = $this->request->getPost('rol_terima');
    $qty_lotrol_terima = $this->request->getPost('qty_lotrol_terima');

    // Hapus semua data lotrol lama yang terkait
    $this->lotRolPenerimaanModel->where('terima_id', $terima_id)
                      ->where('det_terima_id', $det_terima_id)
                      ->delete();

    // Simpan ulang semua data dari form
    foreach ($rol_terima as $index => $rol) {
        $data = [
            'terima_id' => $terima_id,
            'det_terima_id' => $det_terima_id,
            'lot_terima' => $lot_terima,
            'rol_terima' => $rol,
            'qty_lotrol_terima' => $qty_lotrol_terima[$index]
        ];

        $this->lotRolPenerimaanModel->insert($data);
    }

    $this->db->transComplete();

    if ($this->db->transStatus() === false) {
        return redirect()->back()->with('msg', 'Gagal memperbarui data!')->with('error', true);
    }

    return redirect()->to(base_url('admin/penerimaan_pembelian/lot_rol/' . $terima_id . '/' . $det_terima_id))
                    ->with('msg', 'Data berhasil diperbarui!');
}
} 