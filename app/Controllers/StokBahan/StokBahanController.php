<?php

namespace App\Controllers\StokBahan;

use App\Models\StokBahanModel;
use App\Models\SupplierModel;
use App\Models\WarnaModel;
use App\Models\UnitSatuanModel;
use App\Models\SatuanModel; 
use App\Models\BahanModel;
use App\Models\GrupModel;
use CodeIgniter\Controller;

class StokBahanController extends Controller
{
    protected $db;
    protected $stokBahanModel;
    protected $supplierModel;
    protected $warnaModel;
    protected $satuanModel;
    protected $bahanModel;
    protected $grupModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect();

        $this->stokBahanModel = new StokBahanModel();
        $this->supplierModel = new SupplierModel();
        $this->warnaModel = new WarnaModel();
        $this->satuanModel = new SatuanModel();
        $this->bahanModel = new BahanModel();
        $this->grupModel = new GrupModel();
    }

    public function index()
    {
        $stokBahanModel = new StokBahanModel();
        $search = $this->request->getVar('search');
        $perPage = 50;
        $currentPage = (int) ($this->request->getVar('page') ?? 1);
        $offset = ($currentPage - 1) * $perPage;

        $allData = $stokBahanModel->getStokWithPenerimaan($search);
        $totalData = count($allData);
        $paginatedData = array_slice($allData, $offset, $perPage);

        $pager = \Config\Services::pager();
        $pager->makeLinks($currentPage, $perPage, $totalData);

        $data = [
            'stok_bahan' => $paginatedData,
            'pager'      => $pager,
            'search'     => $search
        ];

        return view('stok_bahan/index', $data);
    }

    public function show()
    {
        $stokBahanModel = new StokBahanModel();
        $search = $this->request->getVar('search');

        $allData = $stokBahanModel->getDetailStokRows($search);
        $perPage = 50;
        $currentPage = (int) ($this->request->getVar('page') ?? 1);
        $offset = ($currentPage - 1) * $perPage;

        $paginatedData = array_slice($allData, $offset, $perPage);
        $pager = \Config\Services::pager();
        $pager->makeLinks($currentPage, $perPage, count($allData));

        $data = [
            'stok_bahan' => $paginatedData,
            'pager'      => $pager,
            'search'     => $search
        ];

        return view('stok_bahan/show', $data);
    }

    public function create()
    {
        $kode_grup = $this->request->getGet('kode_grup');
        $kode_bahan = $this->request->getGet('kode_bahan');

        $kode_stok = ''; 
        if ($kode_grup && $kode_bahan) {
            $kode_stok = $this->stokBahanModel->generateKode($kode_grup, $kode_bahan);
        }

        return view('stok_bahan/create', [
            'validation'     => \Config\Services::validation(),
            'grup_list'      => $this->grupModel->findAll(),
            'bahan_list'     => $this->bahanModel->findAll(),
            'warna_list'     => $this->warnaModel->findAll(),
            'satuan_list'    => $this->satuanModel
                                    ->select('satuan.*, unit_satuan.jenis_unit')
                                    ->join('unit_satuan', 'unit_satuan.unit_id = satuan.unit_id', 'left')
                                    ->findAll(),
            'supplier_list'  => $this->supplierModel->findAll(),
            'kode_stok'      => $kode_stok
        ]);
    }

    public function store()
    {
        // dd($this->request->getPost());

        $rules = [
            'grup_id' => 'required|integer',
            'bahan_id' => 'required|integer',
            'warna_id' => 'required|integer',
            'satuan_id' => 'required|integer',
            'supplier_id' => 'required|integer',
            'harga_beli' => 'required|numeric',
            'harga_per_pcs' => 'required|numeric',
            'last_received' => 'required|valid_date[Y-m-d]',
            'nama_barang' => 'required|string',
        ];         

        if (!$this->validate($rules)) {
            dd($this->validator->getErrors());
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $grup = $this->grupModel->find((int) $this->request->getPost('grup_id'));
        $bahan = $this->bahanModel->find((int) $this->request->getPost('bahan_id'));
        $warna = $this->warnaModel->find((int) $this->request->getPost('warna_id'));
        $satuan = $this->satuanModel->find((int) $this->request->getPost('satuan_id'));
        $supplier = $this->supplierModel->find((int) $this->request->getPost('supplier_id'));

        if (!$bahan || !$warna || !$satuan || !$supplier) {
            return redirect()->back()->withInput()->with('error', 'Kode bahan, warna, satuan, atau supplier tidak ditemukan!');
        }

        $kode_stok = $this->stokBahanModel->generateKode($this->request->getPost('grup_id'), $bahan['kode_bahan']);

        $data = [
            'kode_stok' => $kode_stok,
            'grup_id' => (int) $grup['grup_id'],
            'bahan_id' => (int) $bahan['bahan_id'],
            'warna_id' => (int) $warna['warna_id'],
            'satuan_id' => (int) $satuan['satuan_id'],
            'supplier_id' => (int) $supplier['supplier_id'],
            'harga_beli' => (float) $this->request->getPost('harga_beli'),
            'harga_per_pcs' => (float) $this->request->getPost('harga_per_pcs'),
            'last_received' => $this->request->getPost('last_received'),
            'nama_barang' => trim($this->request->getPost('nama_barang')),
            'isi_satuan' => (float) $this->request->getPost('isi_satuan')
        ];

        // dd($data);

        if (!$this->stokBahanModel->insert($data)) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data!');
        }

        $totalData = $stokBahanModel->countAll();
        $dataPerPage = 50;
        $lastPage = ceil($totalData / $dataPerPage);

        return redirect()->to(base_url('admin/stok_bahan?page=' . $lastPage))->with('msg', 'Data berhasil disimpan!');
    }    


    public function edit($id)
    {
        $stokBahan = $this->stokBahanModel
            ->select('stok_bahan.*, grup.nama_grup, grup.kode_grup, bahan.nama_bahan, bahan.kode_bahan, warna.jenis_warna, warna.kode_warna, satuan.jenis_satuan, satuan.kode_satuan, satuan.isi_satuan, satuan.unit_id, unit_satuan.jenis_unit AS jenis_unit, supplier.nama_supplier, supplier.kode_supplier')
            ->join('grup', 'grup.grup_id = stok_bahan.grup_id', 'left')
            ->join('bahan', 'bahan.bahan_id = stok_bahan.bahan_id', 'left')
            ->join('warna', 'warna.warna_id = stok_bahan.warna_id', 'left')
            ->join('satuan', 'satuan.satuan_id = stok_bahan.satuan_id', 'left')
            ->join('unit_satuan', 'unit_satuan.unit_id = satuan.unit_id', 'left')
            ->join('supplier', 'supplier.supplier_id = stok_bahan.supplier_id', 'left')
            ->find($id);

        if (!$stokBahan) {
            return redirect()->to('stok_bahan')->with('msg', 'Data tidak ditemukan!')->with('error', true);
        }

        return view('stok_bahan/edit', [
            'stok' => $stokBahan,
            'grup_list' => $this->grupModel->findAll(),
            'bahan_list' => $this->bahanModel->findAll(),
            'warna_list' => $this->warnaModel->findAll(),
            'satuan_list' => $this->satuanModel
                ->select('satuan.*, unit_satuan.jenis_unit')
                ->join('unit_satuan', 'unit_satuan.unit_id = satuan.unit_id', 'left')
                ->findAll(),
            'supplier_list' => $this->supplierModel->findAll(),
            'validation' => \Config\Services::validation()
        ]);    
    }

    public function update($id)
    {
        $stokBahan = $this->stokBahanModel->find($id);
        if (!$stokBahan) {
            dd($this->validator->getErrors());
            return redirect()->to('admin/stok_bahan')->with('msg', 'Data tidak ditemukan!')->with('error', true);
        }

        $rules = [
            'grup_id' => 'required|integer',
            'bahan_id' => 'required|integer',
            'warna_id' => 'required|integer',
            'satuan_id' => 'required|integer',
            'supplier_id' => 'required|integer',
            'harga_beli' => 'required|numeric',
            'harga_per_pcs' => 'required|numeric',
            'last_received' => 'required|valid_date[Y-m-d]',
            'nama_barang' => 'required|string',
        ]; 

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $grup = $this->grupModel->where('grup_id', $this->request->getPost('grup_id'))->first();
        $bahan = $this->bahanModel->where('bahan_id', $this->request->getPost('bahan_id'))->first();
        $warna = $this->warnaModel->where('warna_id', $this->request->getPost('warna_id'))->first();
        $satuan = $this->satuanModel->where('satuan_id', $this->request->getPost('satuan_id'))->first();
        $supplier = $this->supplierModel->where('supplier_id', $this->request->getPost('supplier_id'))->first();

        if (!$bahan || !$warna || !$satuan || !$supplier) {
            return redirect()->back()->withInput()->with('error', 'Kode bahan, warna, satuan, atau supplier tidak valid!');
        }

        $data = [
            'grup_id' => (int) $grup['grup_id'],
            'bahan_id' => (int) $bahan['bahan_id'],
            'warna_id' => (int) $warna['warna_id'],
            'satuan_id' => (int) $satuan['satuan_id'],
            'supplier_id' => (int) $supplier['supplier_id'],
            'harga_beli' => (float) $this->request->getPost('harga_beli'),
            'harga_per_pcs' => (float) $this->request->getPost('harga_per_pcs'),
            'last_received' => $this->request->getPost('last_received'),
            'nama_barang' => trim($this->request->getPost('nama_barang')),
            'isi_satuan' => (float) $this->request->getPost('isi_satuan')
        ];

        if (!$this->stokBahanModel->update($id, $data)) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data!');
        }

        return redirect()->to('admin/stok_bahan?page=' . $page)->with('msg', 'Data berhasil diperbarui!');
    }

    public function delete($id)
    {
        $stokBahan = $this->stokBahanModel->find($id);
        
        if (!$stokBahan) {
            return redirect()->to('admin/stok_bahan')->with('msg', 'Data tidak ditemukan!')->with('error', true);
        }

        $this->stokBahanModel->delete($id);
        return redirect()->to('admin/stok_bahan')->with('msg', 'Data berhasil dihapus!');
    }

    public function searchGrup()
    {
        $keyword = $this->request->getGet('query');
        $builder = $this->db->table('grup');
        $builder->like('nama_grup', $keyword);
        $result = $builder->get()->getResultArray();

        return $this->response->setJSON($result);
    }

    public function searchBahan()
    {
        $keyword = $this->request->getGet('query');
        $grupId = $this->request->getGet('grup_id');

        $builder = $this->db->table('bahan');
        $builder->like('nama_bahan', $keyword);

        if (!empty($grupId)) {
            $builder->where('grup_id', $grupId);
        }

        $result = $builder->get()->getResultArray();

        return $this->response->setJSON($result);
    }

    public function searchWarna()
    {
        $keyword = $this->request->getGet('query');
        $builder = $this->db->table('warna');
        $builder->like('jenis_warna', $keyword);
        $result = $builder->get()->getResultArray();

        return $this->response->setJSON($result);
    }

    public function searchSatuan()
    {
        $keyword = $this->request->getGet('query');

        $builder = $this->db->table('satuan');
        $builder->select('satuan.*, unit_satuan.jenis_unit');
        $builder->join('unit_satuan', 'unit_satuan.unit_id = satuan.unit_id', 'left');
        $builder->like('jenis_satuan', $keyword);

        $result = $builder->get()->getResultArray();

        return $this->response->setJSON($result);
    }

    public function searchSupplier()
    {
        $keyword = $this->request->getGet('query');
        $result = $this->supplierModel->like('nama_supplier', $keyword)->findAll();

        return $this->response->setJSON($result);
    }

    public function downloadExcel()
    {
        $stokBahanModel = new StokBahanModel();
        $stokBahan = $stokBahanModel->getStokWithPenerimaan(); // Ambil data stok dengan penerimaan yang ada

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Kode Barang')
            ->setCellValue('B1', 'Nama Barang')
            ->setCellValue('C1', 'Grup')
            ->setCellValue('D1', 'Bahan')
            ->setCellValue('E1', 'Warna')
            ->setCellValue('F1', 'Supplier')
            ->setCellValue('G1', 'Satuan')
            ->setCellValue('H1', 'Harga Beli')
            ->setCellValue('I1', 'Beli')
            ->setCellValue('J1', 'Retur')
            ->setCellValue('K1', 'Pakai')
            ->setCellValue('L1', 'Sisa')
            ->setCellValue('M1', 'Terakhir Diterima');

        $row = 2;
        foreach ($stokBahan as $stok) {
            $beli = (float) ($stok['beli'] ?? 0);
            $retur = (float) ($stok['retur'] ?? 0);
            $pakai = (float) ($stok['pakai'] ?? 0);
            $sisa = ($beli) - ($retur + $pakai);
    
            $sheet->setCellValue('A' . $row, esc($stok['kode_stok']))
                ->setCellValue('B' . $row, esc($stok['nama_barang']))
                ->setCellValue('C' . $row, esc($stok['nama_grup']))
                ->setCellValue('D' . $row, esc($stok['nama_bahan']))
                ->setCellValue('E' . $row, esc($stok['jenis_warna']))
                ->setCellValue('F' . $row, esc($stok['nama_supplier']))
                ->setCellValue('G' . $row, esc($stok['jenis_satuan']))
                ->setCellValue('H' . $row, esc(number_format($stok['harga_beli'], 2, '.', ',')))
                ->setCellValue('I' . $row, $beli == 0 ? '-' : number_format($beli, 2, '.', ','))
                ->setCellValue('J' . $row, $retur == 0 ? '-' : number_format($retur, 2, '.', ','))
                ->setCellValue('K' . $row, $pakai == 0 ? '-' : number_format($pakai, 2, '.', ','))
                ->setCellValue('L' . $row, $sisa == 0 ? '-' : number_format($sisa, 2, '.', ','))
                ->setCellValue('M' . $row, !empty($stok['last_received']) ? date('d-m-Y', strtotime($stok['last_received'])) : '-');

            $row++;
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="stok_bahan.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

    public function downloadDetailExcel()
    {
        $stokBahanModel = new StokBahanModel();
        $search = $this->request->getVar('search');
        $stokBahan = $stokBahanModel->getDetailStokRows($search); 

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Kode Barang')
            ->setCellValue('B1', 'Nama Barang')
            ->setCellValue('C1', 'Nomor PO')
            ->setCellValue('D1', 'Tanggal Terima')
            ->setCellValue('E1', 'Lot')
            ->setCellValue('F1', 'Rol')
            ->setCellValue('G1', 'Satuan')
            ->setCellValue('H1', 'Harga Beli')
            ->setCellValue('I1', 'Beli')
            ->setCellValue('J1', 'Retur')
            ->setCellValue('K1', 'Pakai')
            ->setCellValue('L1', 'Sisa');

        $row = 2;
        foreach ($stokBahan as $stok) {
            $beli = (float) ($stok['beli'] ?? 0);
            $retur = (float) ($stok['retur'] ?? 0);
            $pakai = (float) ($stok['pakai'] ?? 0);
            $sisa = ($beli) - ($retur + $pakai);

            $sheet->setCellValue('A' . $row, esc($stok['kode_stok']))
                ->setCellValue('B' . $row, esc($stok['nama_barang']))
                ->setCellValue('C' . $row, esc($stok['nomor_po'] ?? '-'))
                ->setCellValue('D' . $row, !empty($stok['tgl_terima']) ? date('d-m-Y', strtotime($stok['tgl_terima'])) : '-')
                ->setCellValue('E' . $row, esc($stok['lot_terima'] ?? '-'))
                ->setCellValue('F' . $row, esc($stok['rol_terima'] ?? '-'))
                ->setCellValue('G' . $row, esc($stok['jenis_satuan']))
                ->setCellValue('H' . $row, esc(number_format($stok['harga_beli'], 2, '.', ',')))
                ->setCellValue('I' . $row, $beli == 0 ? '-' : number_format($beli, 2, '.', ','))
                ->setCellValue('J' . $row, $retur == 0 ? '-' : number_format($retur, 2, '.', ','))
                ->setCellValue('K' . $row, $pakai == 0 ? '-' : number_format($pakai, 2, '.', ','))
                ->setCellValue('L' . $row, $sisa == 0 ? '-' : number_format($sisa, 2, '.', ','));

            $row++;
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="detail_stok_bahan.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }
} 