<?php

namespace App\Controllers\Supplier;

use App\Models\SupplierModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\Controller;
use App\Models\KetSupplierModel;

class SupplierController extends Controller
{
    protected $supplierModel;

    public function __construct()
    {
        $this->supplierModel = new SupplierModel();
    }

    public function index()
    {
        $supplierModel = new SupplierModel();
        $search = $this->request->getVar('search'); 
        $perPage = 50; // Tentukan jumlah data per halaman

        $data = [
            'suppliers' => $supplierModel->getSuppliers($search, $perPage, $this->request->getVar('page') ?: 1),
            'pager' => $supplierModel->pager, // Untuk menampilkan kontrol pagination
            'search' => $search
        ];

        return view('supplier/index', $data);
    }

    public function create()
    {
        // $validation = \Config\Services::validation();
        $keteranganModel = new KetSupplierModel();
        $supplierModel = new SupplierModel();

        $lastSupplier = $supplierModel->orderBy('kode_supplier', 'DESC')->first();
        $newCode = $lastSupplier ? 'S' . str_pad((int) substr($lastSupplier['kode_supplier'], 1) + 1, 3, '0', STR_PAD_LEFT) : 'S001';

        return view('supplier/create', [
            'validation' => \Config\Services::validation(),
            'oldInput'   => session()->getFlashdata('oldInput') ?? [],
            'ket_list' => $keteranganModel->findAll(),
            'kode_supplier' => $newCode,
        ]);
    }

    public function store()
    {
        $request = $this->request->getPost();
        $supplierModel = new SupplierModel();
        $keteranganModel = new KetSupplierModel();
        // $validation = \Config\Services::validation();

        $rules = [
            'kode_supplier'        => 'required|is_unique[supplier.kode_supplier]',
            'nama_supplier'        => 'required',
            'ket_id'               => 'required|integer|in_list[' . implode(',', array_column($keteranganModel->findAll(), 'ket_id')) . ']',
            'alamat_supplier'      => 'required',
            'kota_supplier'        => 'required',
            'no_telp_supplier'     => 'required',
            'no_hp_supplier'       => 'required',
            'kontak_supplier'      => 'required',
            'ntop_supplier'        => 'required|integer'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $data = [
            'kode_supplier'          => $this->request->getPost('kode_supplier') ?: $supplierModel->generateKode(),
            'nama_supplier'          => $this->request->getPost('nama_supplier'),
            'ket_id'                 => $this->request->getPost('ket_id'),
            'alamat_supplier'        => $this->request->getPost('alamat_supplier'),
            'kota_supplier'          => $this->request->getPost('kota_supplier'),
            'no_telp_supplier'       => $this->request->getPost('no_telp_supplier'),
            'no_hp_supplier'         => $this->request->getPost('no_hp_supplier'),
            'kontak_supplier'        => $this->request->getPost('kontak_supplier'),
            'ntop_supplier'          => $this->request->getPost('ntop_supplier'),
        ];
  
        if (!$supplierModel->save($data)) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data!');
        }

        $totalData = $supplierModel->countAll();
        $dataPerPage = 50; // ganti sesuai jumlah data per halaman kamu
        $lastPage = ceil($totalData / $dataPerPage);

        return redirect()->to(base_url('admin/supplier?page=' . $lastPage))->with('msg', 'Data berhasil disimpan!');
    }

    public function edit($id)
    {
        $supplierModel = new SupplierModel();
        $keteranganModel = new KetSupplierModel();
        // $ket_list = $keteranganModel->findAll();
        
        $supplier = $supplierModel->find($id);
        if (!$supplier) {
            return redirect()->to('admin/supplier')->with('msg', 'Data tidak ditemukan!')->with('error', true);
        }

        return view('supplier/edit', [
            'supplier' => $supplier,
            'ket_list' => $keteranganModel->findAll(),
            'validation' => \Config\Services::validation(),
            'page' => $this->request->getGet('page') ?? 1
        ]);
    }

    public function update($id)
    {
        $supplierModel = new SupplierModel();
        $keteranganModel = new KetSupplierModel();
        $validation = \Config\Services::validation();

        $existingData = $supplierModel->find($id);
        if (!$existingData) {
            return redirect()->to('admin/supplier')->with('msg', 'Data tidak ditemukan!')->with('error', true);
        }

        $rules = [
            'kode_supplier'        => "required|is_unique[supplier.kode_supplier,supplier_id,{$id}]",
            'nama_supplier'        => 'required',
            'ket_id'               => 'required',
            'alamat_supplier'      => 'required',
            'kota_supplier'        => 'required',
            'no_telp_supplier'     => 'required',
            'no_hp_supplier'       => 'required',
            'kontak_supplier'      => 'required',
            'ntop_supplier'        => 'required|integer'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $validation);
        }

        $data = [
            'kode_supplier'          => $this->request->getPost('kode_supplier'),
            'nama_supplier'          => $this->request->getPost('nama_supplier'),
            'ket_id'                 => $this->request->getPost('ket_id'),
            'alamat_supplier'        => $this->request->getPost('alamat_supplier'),
            'kota_supplier'          => $this->request->getPost('kota_supplier'),
            'no_telp_supplier'       => $this->request->getPost('no_telp_supplier'),
            'no_hp_supplier'         => $this->request->getPost('no_hp_supplier'),
            'kontak_supplier'        => $this->request->getPost('kontak_supplier'),
            'ntop_supplier'          => $this->request->getPost('ntop_supplier'),
        ];

        if (empty(array_diff_assoc($data, $existingData))) {
            return redirect()->to('admin/supplier')->with('msg', 'Tidak ada perubahan data!');
        }         

        // Lakukan update
        if (!$supplierModel->update($id, $data)) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data!');
        }

        $page = $this->request->getPost('page') ?? 1;
        return redirect()->to('admin/supplier?page=' . $page)->with('msg', 'Data berhasil diperbarui!');
    }

    public function delete($id = null)
    {
        $supplierModel = new SupplierModel();

        $supplier = $supplierModel->find($id);
        if (!$supplier) {
            return redirect()->to('admin/supplier')->with('msg', 'Data tidak ditemukan!')->with('error', true);
        }

        $supplierModel->delete($id);

        return redirect()->to('admin/supplier')->with('msg', 'Data berhasil dihapus!');
    }

    public function search()
    {
        $search = $this->request->getVar('search');
        $supplierModel = new SupplierModel();

        $suppliers = $supplierModel->getSuppliers(50, $search); // Ambil hasil pencarian

        return $this->response->setJSON($suppliers); // Kirim hasil dalam format JSON
    }
}