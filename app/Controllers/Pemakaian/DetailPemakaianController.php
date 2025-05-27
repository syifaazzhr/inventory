<?php

namespace App\Controllers\Pemakaian;

use App\Models\DetailPemakaianModel;
use App\Models\PemakaianModel;
use App\Models\StokBahanModel;
use CodeIgniter\Controller;

class DetailPemakaianController extends Controller
{
    protected $detailPemakaianModel;
    protected $pemakaianModel;
    protected $stokBahanModel;

    public function __construct()
    {
        $this->detailPemakaianModel = new DetailPemakaianModel();
        $this->pemakaianModel = new PemakaianModel();
        $this->stokBahanModel = new StokBahanModel();
    }

    public function show($pakai_id)
    {
        $pemakaian = $this->pemakaianModel->find($pakai_id);
        if (!$pemakaian) {
            return redirect()->to('admin/pemakaian')->with('msg', 'Data tidak ditemukan!')->with('error', true);
        }

        $detailPemakaian = $this->detailPemakaianModel->getDetailByPemakaian($pakai_id);

        return view('pemakaian/detail', [
            'pemakaian' => $pemakaian,
            'detail_pemakaian' => $detailPemakaian
        ]);
    }

    public function create($pakai_id)
    {
        $pemakaian = $this->pemakaianModel->find($pakai_id);
        if (!$pemakaian) {
            return redirect()->to('admin/pemakaian')->with('msg', 'Data tidak ditemukan!')->with('error', true);
        }

        return view('pemakaian/detail_create', [
            'pemakaian' => $pemakaian,
            'stok_bahan_list' => $this->stokBahanModel->findAll()
        ]);
    }

    public function store($pakai_id)
    { 
        $rules = [
            'stok_id'    => 'required|integer',
            'qty_det_pakai'  => 'required|integer|greater_than[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $stokBahan = $this->stokBahanModel->find($this->request->getPost('stok_id'));
        if (!$stokBahan) {
            return redirect()->back()->withInput()->with('error', 'Bahan tidak ditemukan!');
        }

        $data = [
            'pakai_id' => (int) $pakai_id,
            'stok_id'    => (int) $this->request->getPost('stok_id'),
            'qty_det_pakai'  => (int) $this->request->getPost('qty_det_pakai')
        ];

        if (!$this->detailPemakaianModel->insert($data)) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan detail!');
        }

        return redirect()->to(base_url("admin/pemakaian/$pakai_id/detail"))->with('msg', 'Detail berhasil disimpan!');
    }

    public function delete($pakai_id, $detail_id)
    {
        $detailPemakaian = $this->detailPemakaianModel->find($detail_id);
        if (!$detailPemakaian) {
            return redirect()->to("admin/pemakaian/$pakai_id/detail")->with('msg', 'Detail tidak ditemukan!')->with('error', true);
        }

        $this->detailPemakaianModel->delete($detail_id);

        return redirect()->to(base_url("admin/pemakaian/$pakai_id/detail"))->with('msg', 'Detail berhasil dihapus!');
    }
}