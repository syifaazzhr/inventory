<?php

namespace App\Controllers\PenerimaanPembelian;

use App\Models\DetailPenerimaanModel;
use App\Models\PenerimaanModel;
use App\Models\StokBahanModel;
use CodeIgniter\Controller;

class DetailPenerimaanController extends Controller
{
    protected $detailPenerimaanModel;
    protected $penerimaanModel;
    protected $stokBahanModel;

    public function __construct()
    {
        $this->detailPenerimaanModel = new DetailPenerimaanModel();
        $this->penerimaanModel = new PenerimaanModel();
        $this->stokBahanModel = new StokBahanModel();
    }

    public function show($terima_id)
    {
        $penerimaan = $this->penerimaanModel->find($terima_id);
        if (!$penerimaan) {
            return redirect()->to('admin/penerimaan_pembelian')->with('msg', 'Data penerimaan tidak ditemukan!')->with('error', true);
        }

        $detailPenerimaan = $this->detailPenerimaanModel->getDetailByPenerimaan($terima_id);

        return view('penerimaan/detail', [
            'penerimaan' => $penerimaan,
            'detail_penerimaan' => $detailPenerimaan
        ]);
    }

    public function create($terima_id)
    {
        $penerimaan = $this->penerimaanModel->find($terima_id);
        if (!$penerimaan) {
            return redirect()->to('admin/penerimaan_pembelian')->with('msg', 'Data penerimaan tidak ditemukan!')->with('error', true);
        }

        return view('penerimaan/detail_create', [
            'penerimaan' => $penerimaan,
            'stok_bahan_list' => $this->stokBahanModel->findAll()
        ]);
    }

    public function store($terima_id)
    { 
        $rules = [
            'stok_id'    => 'required|integer',
            'qty_det_terima'  => 'required|integer|greater_than[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $stokBahan = $this->stokBahanModel->find($this->request->getPost('stok_id'));
        if (!$stokBahan) {
            return redirect()->back()->withInput()->with('error', 'Bahan tidak ditemukan!');
        }

        $jumlah_det_terima = $stokBahan['harga_beli'] * (int) $this->request->getPost('qty_det_terima');

        $data = [
            'terima_id' => (int) $terima_id,
            'stok_id'    => (int) $this->request->getPost('stok_id'),
            'qty_det_terima'  => (int) $this->request->getPost('qty_det_terima'),
            'jumlah_det_terima' => $jumlah_det_terima
        ];

        if (!$this->detailPenerimaanModel->insert($data)) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan detail penerimaan!');
        }

        return redirect()->to(base_url("admin/penerimaan_pembelian/$terima_id/detail"))->with('msg', 'Detail penerimaan berhasil disimpan!');
    }

    public function delete($terima_id, $detail_id)
    {
        $detailPenerimaan = $this->detailPenerimaanModel->find($detail_id);
        if (!$detailPenerimaan) {
            return redirect()->to("admin/penerimaan_pembelian/$terima_id/detail")->with('msg', 'Detail penerimaan tidak ditemukan!')->with('error', true);
        }

        $this->detailPenerimaanModel->delete($detail_id);

        return redirect()->to(base_url("admin/penerimaan_pembelian/$terima_id/detail"))->with('msg', 'Detail penerimaan berhasil dihapus!');
    }
}