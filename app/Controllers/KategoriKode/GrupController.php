<?php

namespace App\Controllers\KategoriKode;

use App\Models\GrupModel;
use CodeIgniter\Controller; 

class GrupController extends Controller
{ 
    protected $grupModel;

    public function __construct()
    {
        $this->grupModel = new GrupModel();
    } 

    public function index()
    {
        $grupModel = new GrupModel();
        $search = $this->request->getVar('search');
        $perPage = 50;

        $data = [
            'grup' => $grupModel->getGrup($search, $perPage, $this->request->getVar('page') ?: 1), // Mengambil semua data tanpa paginate
            'pager' => $grupModel->pager,
            'search' => $search
        ];

        return view('grup/index', $data);
    }

    public function create()
    {
        $model = new \App\Models\GrupModel();

        // Cek apakah form sudah disubmit
        if ($this->request->getMethod() === 'post') {
            // Ambil data yang dikirim melalui form
            $data = [
                'kode_grup' => $this->request->getPost('kode_grup'),
                'nama_grup' => $this->request->getPost('nama_grup')
            ];

            // Validasi data
            if ($this->validate([
                'kode_grup' => 'required',
                'nama_grup' => 'required'
            ])) {
                // Simpan data
                if ($model->insert($data)) {
                    // Jika berhasil, arahkan ke halaman index dan tampilkan pesan sukses
                    session()->setFlashdata('success', 'Data berhasil ditambahkan!');
                    return redirect()->to('/grup');  // Ganti '/grup' dengan URL index yang sesuai
                } else {
                    // Jika gagal, beri pesan error
                    session()->setFlashdata('error', 'Data gagal ditambahkan!');
                }
            }
        }

        return view('grup/create', [
            'validation' => \Config\Services::validation() // Mengirimkan objek validasi ke view
        ]);
    }

    public function store()
    {
        $grupModel = new GrupModel();
        $validation = \Config\Services::validation();

        $rules = [
            'kode_grup' => 'required|is_unique[grup.kode_grup]',
            'nama_grup' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('msg', 'Kode grup sudah terdaftar!');
        }

        $data = [
            'kode_grup' => strtoupper($this->request->getPost('kode_grup')),
            'nama_grup' => strtoupper($this->request->getPost('nama_grup'))
        ];

        if (!$this->grupModel->save($data)) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan data!');
        }

        $totalData = $grupModel->countAll();
        $dataPerPage = 50;
        $lastPage = ceil($totalData / $dataPerPage);

        return redirect()->to(base_url('admin/grup?page=' . $lastPage))->with('msg', 'Data berhasil disimpan!');
    }

    public function edit($id)
    {
        $model = new GrupModel();
        $grup = $model->find($id); // Ambil data berdasarkan ID

        if (!$grup) 
        {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Data tidak ditemukan!');
        }

        return view('grup/edit', [
            'grup' => $grup,
            'validation' => \Config\Services::validation(),
            'page' => $this->request->getGet('page') ?? 1
        ]);
    }

    public function update($id)
    {
        $model = new GrupModel();
        $page = $this->request->getPost('page') ?? 1;

        // Validasi input form
        if ($this->validate([
            'kode_grup' => 'required',
            'nama_grup' => 'required'
        ])) {
            $data = [
                'kode_grup' => strtoupper($this->request->getPost('kode_grup')),
                'nama_grup' => strtoupper($this->request->getPost('nama_grup'))
            ];

            // Update data ke database
            if ($model->update($id, $data)) {
                session()->setFlashdata('msg', 'Data berhasil diperbarui!');
                return redirect()->to('/admin/grup?page=' . $page);
            } else {
                session()->setFlashdata('msg', 'Gagal memperbarui data!');
            }
        } else {
            // Jika validasi gagal
            session()->setFlashdata('msg', 'Periksa kembali inputan anda!');
        }

        return redirect()->to('admin/grup?page=' . $page)->with('msg', 'Data berhasil diperbarui!');
    }

    public function delete($id)
    {
        $grup = $this->grupModel->find($id);
        if (!$grup) {
            return redirect()->to('admin/grup')->with('msg', 'Data tidak ditemukan!')->with('error', true);
        }

        $this->grupModel->delete($id);

        return redirect()->to('admin/grup')->with('msg', 'Data berhasil dihapus!');
    }

    public function search()
    {
        $search = $this->request->getVar('search');
        $grup = $this->grupModel->getGrup(50, $search); // Ambil hasil pencarian
        return $this->response->setJSON($grup); // Kirim hasil dalam format JSON
    }
}