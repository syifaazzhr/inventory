<?php

namespace App\Controllers\KategoriKode;

use App\Models\BahanModel;
use App\Models\GrupModel;
use CodeIgniter\Controller;
use CodeIgniter\Exceptions\PageNotFoundException;

class BahanController extends Controller
{
    protected $bahanModel;

    public function __construct()
    {
        $this->bahanModel = new BahanModel();
    }

    public function index()
    {
        $bahanModel = new BahanModel();
        $search = $this->request->getVar('search');
        $perPage = 50;

        // Ambil data berdasarkan pencarian dan pagination
        $data = [
            'bahan' => $bahanModel->getBahan($search, $perPage, $this->request->getVar('page') ?: 1),  // Ambil data bahan dengan pencarian
            'pager' => $bahanModel->pager,              // Untuk menampilkan kontrol pagination
            'search' => $search
        ];

        return view('bahan/index', $data);
    }

    public function create()
    {
        $grupModel = new \App\Models\GrupModel(); // Model untuk tabel grup

        $data = [
            'grup_list' => $grupModel->findAll(), // Ambil semua grup
            'validation' => \Config\Services::validation(),
            'oldInput' => session()->getFlashdata('oldInput') ?? []
        ];

        return view('bahan/create', $data);
    }

    public function store()
    {
        $bahanModel = new BahanModel();
        $validation = \Config\Services::validation();
 
        $rules = [
            'grup_id' => 'required',
            'kode_bahan' => 'required|is_unique[bahan.kode_bahan]',
            'nama_bahan' => 'required',
        ];
 
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('msg', 'Kode bahan sudah terdaftar!');
        } 
    
        $data = [
            'grup_id' => $this->request->getPost('grup_id'),
            'kode_bahan' => $this->request->getPost('kode_bahan'),
            'nama_bahan' => $this->request->getPost('nama_bahan'),
        ];

        if (!$this->bahanModel->save($data)) {
            return redirect()->back()->withInput()->with('msg', 'Gagal menambahkan data!');
        }

        $totalData = $bahanModel->countAll();
        $dataPerPage = 50;
        $lastPage = ceil($totalData / $dataPerPage);

        return redirect()->to(base_url('admin/bahan?page=' . $lastPage))->with('msg', 'Data berhasil disimpan!');
    }

    public function edit($id)
    {
        $bahan = $this->bahanModel->find($id);
        $grupModel = new GrupModel();

        if (!$bahan) {
            return redirect()->to('admin/bahan')->with('msg', 'Data tidak ditemukan!')->with('error', true);
        }

        return view('bahan/edit', [
            'bahan'      => $bahan,
            'grup_list' => $grupModel->findAll(),
            'validation' => \Config\Services::validation(),
            'page' => $this->request->getGet('page') ?? 1
        ]);
    }

    public function update($id)
    {
        $bahanModel = new BahanModel();
        $validation = \Config\Services::validation();
        $page = $this->request->getPost('page') ?? 1;

        // Aturan Validasi
        $rules = [
            'grup_id' => 'required',
            'kode_bahan' => 'required|is_unique[bahan.kode_bahan,bahan_id,' . $id . ']',
            'nama_bahan' => 'required',
        ]; 

        // Validasi Input
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $validation);
        }

        // Data yang akan diupdate
        $data = [
            'grup_id' => $this->request->getPost('grup_id'),
            'kode_bahan' => $this->request->getPost('kode_bahan'),
            'nama_bahan' => $this->request->getPost('nama_bahan'),
        ];

        // Eksekusi Update
        if ($bahanModel->update($id, $data)) {
            return redirect()->to('admin/bahan?page=' . $page)->with('msg', 'Data berhasil diperbarui!');
        } else {
            return redirect()->back()->with('msg', 'Gagal memperbarui data!');
        }
    }

    public function delete($id = null)
    {
        $bahan = $this->bahanModel->find($id);

        if (!$bahan) {
            return redirect()->to('admin/bahan')->with('msg', 'Data tidak ditemukan!')->with('error', true);
        }

        $this->bahanModel->delete($id);

        return redirect()->to('admin/bahan')->with('msg', 'Data berhasil dihapus!');
    }

    public function search()
    {
        $search = $this->request->getVar('search');
        $bahan = $this->bahanModel->getBahan(50, $search);

        return $this->response->setJSON($bahan);
    }
}