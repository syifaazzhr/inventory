<?php

namespace App\Controllers\Supplier;

use App\Models\KetSupplierModel;
use CodeIgniter\Controller;

class KetSupplierController extends Controller
{ 
    protected $ketSupplierModel;

    public function __construct()
    {
        $this->ketSupplierModel = new KetSupplierModel();
    } 

    public function index()
    {
        $ketSupplierModel = new KetSupplierModel();
        $search = $this->request->getVar('search');
        $perPage = 50;

        $data = [
            'ketSupplier' => $ketSupplierModel->getKetSupplier($search, $perPage, $this->request->getVar('page') ?: 1), // Mengambil semua data tanpa paginate
            'pager' => $ketSupplierModel->pager,
            'search' => $search
        ];

        return view('ketSupplier/index', $data);
    }

    public function create()
    {
        $model = new \App\Models\KetSupplierModel();

        // Cek apakah form sudah disubmit
        if ($this->request->getMethod() === 'post') {
            // Ambil data yang dikirim melalui form
            $data = [
                'ket_jenis' => $this->request->getPost('ket_jenis')
            ];

            // Validasi data
            if ($this->validate([
                'ket_jenis' => 'required'
            ])) {
                // Simpan data
                if ($model->insert($data)) {
                    // Jika berhasil, arahkan ke halaman index dan tampilkan pesan sukses
                    session()->setFlashdata('success', 'Data berhasil ditambahkan!');
                    return redirect()->to('/ketSupplier');  // Ganti '/ketSupplier' dengan URL index yang sesuai
                } else {
                    // Jika gagal, beri pesan error
                    session()->setFlashdata('error', 'Data gagal ditambahkan!');
                }
            }
        }

        // Kirim objek validasi ke view untuk menampilkan pesan error
        return view('ketSupplier/create', [
            'validation' => \Config\Services::validation() // Mengirimkan objek validasi ke view
        ]);
    }

    public function store()
    {
        $ketSupplierModel = new KetSupplierModel();
        $validation = \Config\Services::validation();
        $rules = [
            'ket_jenis' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $validation);
        }

        $data = [
            'ket_jenis' => $this->request->getPost('ket_jenis')
        ];

        if (!$this->ketSupplierModel->save($data)) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan data!');
        }

        $totalData = $ketSupplierModel->countAll();
        $dataPerPage = 10; // ganti sesuai jumlah data per halaman kamu
        $lastPage = ceil($totalData / $dataPerPage);

        return redirect()->to(base_url('admin/ketSupplier?page=' . $lastPage))->with('msg', 'Data berhasil disimpan!');
    }

    public function edit($id)
    {
        $model = new KetSupplierModel();
        $ketSupplier = $model->find($id); // Ambil data berdasarkan ID

        if (!$ketSupplier) 
        {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Data tidak ditemukan!');
        }

        return view('ketSupplier/edit', [
            'ketSupplier' => $ketSupplier,
            'validation' => \Config\Services::validation(),
            'page' => $this->request->getGet('page') ?? 1
        ]);
    }

    public function update($id)
    {
        $ketSuppliermodel = new KetSupplierModel();
        $page = $this->request->getPost('page') ?? 1;

        // Validasi input form
        if ($this->validate([
            'ket_jenis' => 'required'
        ])) {
            // Ambil data yang dikirimkan melalui form
            $data = [
                'ket_jenis' => $this->request->getPost('ket_jenis')
            ];

            // Update data ke database
            if ($ketSuppliermodel->update($id, $data)) {
                session()->setFlashdata('msg', 'Data berhasil diperbarui!');
                return redirect()->to('/admin/ketSupplier?page=' . $page);
            } else {
                session()->setFlashdata('msg', 'Gagal memperbarui data!');
            }
        } else {
            // Jika validasi gagal
            session()->setFlashdata('msg', 'Periksa kembali inputan anda!');
        }

        // Jika validasi gagal atau kesalahan lain, tampilkan kembali form edit
        return redirect()->to('admin/ketSupplier?page=' . $page)->with('msg', 'Data berhasil diperbarui!');
    }

    public function delete($id)
    {
        $ketSupplier = $this->ketSupplierModel->find($id);
        if (!$ketSupplier) {
            return redirect()->to('admin/ketSupplier')->with('msg', 'Data tidak ditemukan!')->with('error', true);
        }

        $this->ketSupplierModel->delete($id);

        return redirect()->to('admin/ketSupplier')->with('msg', 'Data berhasil dihapus!');
    }

    public function search()
    {
        $search = $this->request->getVar('search');
        $ketSupplier = $this->ketSupplierModel->getKetSupplier(10, $search); // Ambil hasil pencarian
        return $this->response->setJSON($ketSupplier); // Kirim hasil dalam format JSON
    }
}