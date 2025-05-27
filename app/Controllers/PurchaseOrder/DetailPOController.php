<?php

namespace App\Controllers\PurchaseOrder;

use App\Models\DetailPOModel;
use App\Models\PurchaseOrderModel;
use App\Models\StokBahanModel;
use CodeIgniter\Controller;

class DetailPOController extends Controller
{
    protected $detailPOModel;
    protected $purchaseOrderModel;
    protected $stokBahanModel;

    public function __construct()
    {
        $this->detailPOModel = new DetailPOModel();
        $this->purchaseOrderModel = new PurchaseOrderModel();
        $this->stokBahanModel = new StokBahanModel();
    }

    /**
     * Menampilkan detail PO berdasarkan PO ID
     */
    public function show($po_id)
    {
        $purchaseOrder = $this->purchaseOrderModel->find($po_id);
        if (!$purchaseOrder) {
            return redirect()->to('admin/purchase_order')->with('msg', 'Data PO tidak ditemukan!')->with('error', true);
        }

        $detailPO = $this->detailPOModel->getDetailByPO($po_id);

        return view('purchase_order/detail', [
            'purchase_order' => $purchaseOrder,
            'detail_po' => $detailPO
        ]);
    }

    public function create($po_id)
    {
        $purchaseOrder = $this->purchaseOrderModel->find($po_id);
        if (!$purchaseOrder) {
            return redirect()->to('admin/purchase_order')->with('msg', 'Data PO tidak ditemukan!')->with('error', true);
        }

        return view('purchase_order/detail_create', [
            'purchase_order' => $purchaseOrder,
            'stok_bahan_list' => $this->stokBahanModel->findAll()
        ]);
    }

    public function store($po_id)
    {
        $rules = [
            'stok_id'    => 'required|integer',
            'qty_detpo'  => 'required|integer|greater_than[0]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $stokBahan = $this->stokBahanModel->find($this->request->getPost('stok_id'));
        if (!$stokBahan) {
            return redirect()->back()->withInput()->with('error', 'Bahan tidak ditemukan!');
        }

        $jumlah_detpo = $stokBahan['harga_beli'] * (int) $this->request->getPost('qty_detpo');

        $data = [
            'po_id'      => (int) $po_id,
            'stok_id'    => (int) $this->request->getPost('stok_id'),
            'qty_detpo'  => (int) $this->request->getPost('qty_detpo'),
            'jumlah_detpo' => $jumlah_detpo
        ];

        if (!$this->detailPOModel->insert($data)) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan detail PO!');
        }

        return redirect()->to(base_url("admin/purchase_order/$po_id/detail"))->with('msg', 'Detail PO berhasil disimpan!');
    } 

    public function edit($po_id, $detpo_id)
    {
        $detailPO = $this->detailPOModel->find($detpo_id);
        if (!$detailPO) {
            return redirect()->to("admin/purchase_order/$po_id/detail")->with('msg', 'Detail PO tidak ditemukan!')->with('error', true);
        }

        return view('purchase_order/detail_edit', [
            'detail_po' => $detailPO,
            'stok_bahan_list' => $this->stokBahanModel->findAll()
        ]);
    }

    public function update($po_id, $detpo_id)
    {
        $detailPO = $this->detailPOModel->find($detpo_id);
        if (!$detailPO) {
            return redirect()->to("admin/purchase_order/$po_id/detail")->with('msg', 'Detail PO tidak ditemukan!')->with('error', true);
        }

        $rules = [
            'stok_id'    => 'required|integer',
            'qty_detpo'  => 'required|integer|min_length[1]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $stokBahan = $this->stokBahanModel->find($this->request->getPost('stok_id'));
        if (!$stokBahan) {
            return redirect()->back()->withInput()->with('error', 'Bahan tidak ditemukan!');
        }

        $jumlah_detpo = $stokBahan['harga_beli'] * (int) $this->request->getPost('qty_detpo');

        $data = [
            'stok_id'    => (int) $this->request->getPost('stok_id'),
            'qty_detpo'  => (int) $this->request->getPost('qty_detpo'),
            'jumlah_detpo' => $jumlah_detpo
        ];

        if (!$this->detailPOModel->update($detpo_id, $data)) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui detail PO!');
        }

        return redirect()->to(base_url("admin/purchase_order/$po_id/detail"))->with('msg', 'Detail PO berhasil diperbarui!');
    }

    public function delete($po_id, $detpo_id)
    {
        $detailPO = $this->detailPOModel->find($detpo_id);
        if (!$detailPO) {
            return redirect()->to("admin/purchase_order/$po_id/detail")->with('msg', 'Detail PO tidak ditemukan!')->with('error', true);
        }

        $this->detailPOModel->delete($detpo_id);

        return redirect()->to(base_url("admin/purchase_order/$po_id/detail"))->with('msg', 'Detail PO berhasil dihapus!');
    }
}