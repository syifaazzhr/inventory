<?php

namespace App\Controllers\KategoriKode;

use App\Models\SatuanModel;
use App\Models\UnitSatuanModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\Controller;

class SatuanController extends Controller
{
    protected $satuanModel;

    public function __construct()
    { 
        $this->satuanModel = new SatuanModel();
    }

    public function index()
    {
        $satuanModel = new SatuanModel();
        $search = $this->request->getVar('search');
        $perPage = 50; 

        $data = [
            'satuan' => $satuanModel->getSatuan($search, $perPage, $this->request->getVar('page') ?: 1),
            'pager' => $satuanModel->pager, 
            'search' => $search
        ];

        return view('satuan/index', $data);
    }

    public function create()
    {
        $unitSatuanModel = new UnitSatuanModel();

        return view('satuan/create', [
            'validation' => \Config\Services::validation(),
            'oldInput'   => session()->getFlashdata('oldInput') ?? [],
            'unitSatuan' => $unitSatuanModel->findAll(),
        ]);
    }

    public function store()
    {
        $satuanModel = new SatuanModel();
        $unitSatuanModel = new UnitSatuanModel();

        $rules = [
            'kode_satuan'  => 'required|is_unique[satuan.kode_satuan]',
            'jenis_satuan' => 'required',
            'isi_satuan'   => 'required|integer',
            'unit_id'      => 'required|integer|in_list[' . implode(',', array_column($unitSatuanModel->findAll(), 'unit_id')) . ']',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('msg', 'Kode satuan sudah terdaftar!');
        }

        $data = [
            'kode_satuan'  => $this->request->getPost('kode_satuan'), // Tidak otomatis, harus diisi pengguna
            'jenis_satuan' => $this->request->getPost('jenis_satuan'),
            'isi_satuan'   => $this->request->getPost('isi_satuan'),
            'unit_id'      => $this->request->getPost('unit_id'),
        ];

        if (!$satuanModel->save($data)) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan data!');
        }

        $totalData = $satuanModel->countAll();
        $dataPerPage = 50;
        $lastPage = ceil($totalData / $dataPerPage);

        return redirect()->to(base_url('admin/satuan?page=' . $lastPage))->with('msg', 'Data berhasil disimpan!');
    }

    public function edit($id)
    {
        $satuanModel = new SatuanModel();
        $unitSatuanModel = new UnitSatuanModel();

        $satuan = $satuanModel->find($id);
        if (!$satuan) {
            return redirect()->to('admin/satuan')->with('msg', 'Data tidak ditemukan!')->with('error', true);
        }

        return view('satuan/edit', [
            'satuan' => $satuan,
            'unitSatuan' => $unitSatuanModel->findAll(),
            'validation' => \Config\Services::validation(),
            'page' => $this->request->getGet('page') ?? 1
        ]);
    }

    public function update($id)
    {
        $satuanModel = new SatuanModel();
        $unitSatuanModel = new UnitSatuanModel();
        $validation = \Config\Services::validation();

        // Validasi input
        $rules = [
            'kode_satuan'  => "required|is_unique[satuan.kode_satuan,satuan_id,{$id}]", 
            'jenis_satuan'    => 'required',
            'isi_satuan'     => 'required|integer',
            'unit_id' => 'required|integer|in_list[' . implode(',', array_column($unitSatuanModel->findAll(), 'unit_id')) . ']',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $validation);
        }

        // Data baru untuk update
        $data = [
            'kode_satuan'  => $this->request->getPost('kode_satuan'),
            'jenis_satuan'    => $this->request->getPost('jenis_satuan'),
            'isi_satuan'     => $this->request->getPost('isi_satuan'),
            'unit_id' => $this->request->getPost('unit_id'),
        ];

        // Lakukan update
        if (!$satuanModel->update($id, $data)) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data!');
        }

        $page = $this->request->getPost('page') ?? 1;
        return redirect()->to('admin/satuan?page=' . $page)->with('msg', 'Data berhasil diperbarui!');
    }


    public function delete($id = null)
    {
        $satuanModel = new SatuanModel();

        $satuan = $satuanModel->find($id);
        if (!$satuan) {
            return redirect()->to('admin/satuan')->with('msg', 'Data tidak ditemukan!')->with('error', true);
        }

        $satuanModel->delete($id);

        return redirect()->to('admin/satuan')->with('msg', 'Data berhasil dihapus!');
    }

    public function search()
    {
        $search = $this->request->getVar('search');
        $satuanModel = new SatuanModel();

        $satuan = $satuanModel->getSatuan(50, $search);

        return $this->response->setJSON($satuan);
    }
} 