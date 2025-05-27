<?php

namespace App\Controllers\Retur;

use App\Models\DetailReturModel;
use App\Models\ReturModel;
use App\Models\StokBahanModel;
use CodeIgniter\Controller;

class DetailReturController extends Controller
{
    protected $detailReturModel;
    protected $returModel;
    protected $stokBahanModel;

    public function __construct()
    {
        $this->detailReturModel = new DetailReturModel();
        $this->returModel = new ReturModel();
        $this->stokBahanModel = new StokBahanModel();
    }

    public function show($retur_id)
    {
        $retur = $this->returModel->find($retur_id);
        if (!$retur) {
            return redirect()->to('admin/retur')->with('msg', 'Data tidak ditemukan!')->with('error', true);
        }

        $detailRetur = $this->detailReturModel->getDetailByRetur($retur_id);

        return view('retur/detail', [
            'retur' => $retur,
            'detail_retur' => $detailRetur
        ]);
    }

    public function create($retur_id)
    {
        $retur = $this->returModel->find($retur_id);
        if (!$retur) {
            return redirect()->to('admin/retur')->with('msg', 'Data tidak ditemukan!')->with('error', true);
        }

        return view('retur/detail_create', [
            'retur' => $retur,
            'stok_bahan_list' => $this->stokBahanModel->findAll()
        ]);
    }

    public function store($retur_id)
    { 
        $rules = [
            'stok_id'    => 'required|integer',
            'qty_det_retur'  => 'required|integer|greater_than[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $stokBahan = $this->stokBahanModel->find($this->request->getPost('stok_id'));
        if (!$stokBahan) {
            return redirect()->back()->withInput()->with('error', 'Bahan tidak ditemukan!');
        }

        $jumlah_det_retur = $stokBahan['harga_beli'] * (int) $this->request->getPost('qty_det_retur');

        $data = [
            'retur_id' => (int) $retur_id,
            'stok_id'    => (int) $this->request->getPost('stok_id'),
            'qty_det_retur'  => (int) $this->request->getPost('qty_det_retur'),
            'jumlah_det_retur' => $jumlah_det_retur
        ];

        if (!$this->detailReturModel->insert($data)) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan detail!');
        }

        return redirect()->to(base_url("admin/retur/$retur_id/detail"))->with('msg', 'Detail berhasil disimpan!');
    }

    public function delete($retur_id, $detail_id)
    {
        $detailRetur = $this->detailReturModel->find($detail_id);
        if (!$detailRetur) {
            return redirect()->to("admin/retur/$retur_id/detail")->with('msg', 'Detail tidak ditemukan!')->with('error', true);
        }

        $this->detailReturModel->delete($detail_id);

        return redirect()->to(base_url("admin/retur/$retur_id/detail"))->with('msg', 'Detail berhasil dihapus!');
    }
}