<?php

namespace App\Controllers\KategoriKode;

use App\Models\WarnaModel;
use CodeIgniter\Controller;
use CodeIgniter\Validation\Validation;

class WarnaController extends Controller
{
    protected $warnaModel;

    public function __construct()
    {
        $this->warnaModel = new WarnaModel();
    }
 
    public function index()
    {
        $warnaModel = new WarnaModel();
        $search = $this->request->getGet('search');
        $perPage = 50;
        // Ambil data berdasarkan pencarian dan pagination
        $data = [
            'warna' => $warnaModel->getWarna($search, $perPage, $this->request->getVar('page') ?: 1), // Tentukan halaman berdasarkan parameter 'page'
            'pager' => $warnaModel->pager, // Untuk menampilkan kontrol pagination
            'search' => $search
        ];

        return view('warna/index', $data);
    }

    public function create()
    {
        $warnaModel = new \App\Models\WarnaModel();

        // Cek apakah form sudah disubmit
        if ($this->request->getMethod() === 'post') {
            // Ambil data yang dikirim melalui form
            $data = [
                'kode_warna' => $this->request->getPost('kode_warna'),
                'jenis_warna' => $this->request->getPost('jenis_warna')
            ];

            // Validasi data
            if ($this->validate([
                'kode_warna' => 'required|is_unique[warna.kode_warna]',
                'jenis_warna' => 'required'
            ])) {
                // Simpan data
                if ($warnaModel->insert($data)) {
                    // Jika berhasil, arahkan ke halaman index dan tampilkan pesan sukses
                    session()->setFlashdata('success', 'Data berhasil ditambahkan!');
                    return redirect()->to('/warna');  // Ganti '/grup' dengan URL index yang sesuai
                } else {
                    // Jika gagal, beri pesan error
                    session()->setFlashdata('error', 'Data gagal ditambahkan!');
                }
            }
        }

        // Kirim objek validasi ke view untuk menampilkan pesan error
        return view('warna/create', [
            'validation' => \Config\Services::validation() // Mengirimkan objek validasi ke view
        ]);
    }

    public function store()
    {
        $warnaModel = new WarnaModel();
        $validation = \Config\Services::validation();
        $rules = [
            'kode_warna' => 'required|is_unique[warna.kode_warna]',
            'jenis_warna' => 'required'
        ];
 
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('msg', 'Kode warna sudah terdaftar!');
        }        

        $data = [
            'kode_warna' => $this->request->getPost('kode_warna'),
            'jenis_warna' => $this->request->getPost('jenis_warna')
        ];

        if (!$this->warnaModel->save($data)) {
            return redirect()->back()->withInput()->with('msg', 'Gagal menambahkan data!');
        }

        $totalData = $warnaModel->countAll();
        $dataPerPage = 50; // ganti sesuai jumlah data per halaman kamu
        $lastPage = ceil($totalData / $dataPerPage);

        return redirect()->to(base_url('admin/warna?page=' . $lastPage))->with('msg', 'Data berhasil disimpan!');
    }
    
    public function edit($id)
    {
        $warna = $this->warnaModel->find($id); 
        $warnaModel = new WarnaModel();

        if (!$warna) 
        {
            return redirect()->to('admin/warna')->with('msg', 'Data tidak ditemukan!')->with('error', true);
        }

        return view('warna/edit', [
            'warna' => $warna,
            'validation' => \Config\Services::validation(),
            'page' => $this->request->getGet('page') ?? 1
        ]);
    }

    public function update($id)
    {
        $warnaModel = new WarnaModel();
        $validation = \Config\Services::validation();
        $page = $this->request->getPost('page') ?? 1;

        // Aturan Validasi
        $rules = [
            'kode_warna' => 'required|is_unique[warna.kode_warna,warna_id,' . $id . ']',
            'jenis_warna' => 'required',
        ];

        // Validasi Input
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $validation);
        }

        // Data yang akan diupdate
        $data = [
            'kode_warna' => $this->request->getPost('kode_warna'),
            'jenis_warna' => $this->request->getPost('jenis_warna'),
        ];

        // Eksekusi Update
        if ($warnaModel->update($id, $data)) {
            return redirect()->to('admin/warna?page=' . $page)->with('msg', 'Data berhasil diperbarui!');
        } else {
            return redirect()->back()->with('msg', 'Gagal memperbarui data!');
        }
    }

    public function delete($id)
    {
        $warna = $this->warnaModel->find($id);
        if (!$warna) {
            return redirect()->to('admin/warna')->with('msg', 'Data tidak ditemukan!')->with('error', true);
        }

        $this->warnaModel->delete($id);

        return redirect()->to('admin/warna')->with('msg', 'Data berhasil dihapus!');
    }


    public function search()
    {
        $search = $this->request->getVar('search');
        $warna = $this->warnaModel->getWarna(50, $search);
        return $this->response->setJSON($warna);
    }
}