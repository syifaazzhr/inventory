<?php

namespace App\Controllers\Dashboard;

use App\Models\PurchaseOrderModel;
use App\Models\StokBahanModel;
use CodeIgniter\I18n\Time;
use CodeIgniter\RESTful\ResourceController;

class DashboardController extends ResourceController
{

    public function __construct()
    {
         
    }

    public function index()
    {
        return redirect('admin/dashboard');
    }

    public function dashboard()
    {
        $data = array_merge(
            $this->getPurchaseOrderThisMonth(),
            $this->getPenerimaanThisMonth() ,
            $this->getReturThisMonth(),
            $this->getPemakaianThisMonth(),
            $this->getPurchaseOrderToday(),
            $this->getPenerimaanToday(),
            $this->getReturToday(),
            $this->getPemakaianToday(),
            $this->getPOOverviewLast7Days(),
            $this->getPenerimaanOverviewLast7Days(),
            $this->getReturOverviewLast7Days(),
            $this->getPemakaianOverviewLast7Days(),
            $this->getBarangHampirHabis(),
            $this->getPOStatus(),
            [
                'dateNow' => \CodeIgniter\I18n\Time::now('Asia/Jakarta')
            ],
            $this->getPOStatusDetail(),
            [
                'dateNow' => Time::now('Asia/Jakarta')
            ]
        );

        return view('dashboard/index', $data);
    }

    protected function getPurchaseOrderThisMonth(): array
    {
        $now = Time::now('Asia/Jakarta');
        $year = $now->getYear();
        $month = $now->getMonth();

        // Hitung hari pertama dan terakhir bulan ini
        $firstDay = "{$year}-" . str_pad($month, 2, '0', STR_PAD_LEFT) . "-01";
        $lastDay = date('Y-m-t', strtotime($firstDay)); // t = jumlah hari dalam bulan tersebut

        $purchaseOrderModel = new \App\Models\PurchaseOrderModel();
        $poThisMonth = $purchaseOrderModel
            ->where("created_at >=", $firstDay)
            ->where("created_at <=", $lastDay)
            ->findAll();

        return [
            'poThisMonth' => $poThisMonth,
            'countPOThisMonth' => count($poThisMonth),
        ];
    }

    protected function getPenerimaanThisMonth(): array
    {
        $now = Time::now('Asia/Jakarta');
        $year = $now->getYear();
        $month = $now->getMonth();

        $firstDay = "{$year}-" . str_pad($month, 2, '0', STR_PAD_LEFT) . "-01";
        $lastDay = date('Y-m-t', strtotime($firstDay));

        $penerimaanModel = new \App\Models\PenerimaanModel();
        $penerimaanThisMonth = $penerimaanModel
            ->where("created_at >=", $firstDay)
            ->where("created_at <=", $lastDay)
            ->findAll();

        return [
            'penerimaanThisMonth' => $penerimaanThisMonth,
            'countPenerimaanThisMonth' => count($penerimaanThisMonth),
        ];
    }

    protected function getReturThisMonth(): array
    {
        $now = Time::now('Asia/Jakarta');
        $year = $now->getYear();
        $month = $now->getMonth();

        $firstDay = "{$year}-" . str_pad($month, 2, '0', STR_PAD_LEFT) . "-01";
        $lastDay = date('Y-m-t', strtotime($firstDay));

        $returModel = new \App\Models\ReturModel();
        $returThisMonth = $returModel
            ->where("created_at >=", $firstDay)
            ->where("created_at <=", $lastDay)
            ->findAll();

        return [
            'returThisMonth' => $returThisMonth,
            'countReturThisMonth' => count($returThisMonth),
        ];
    }

    protected function getPemakaianThisMonth(): array
    {
        $now = Time::now('Asia/Jakarta');
        $year = $now->getYear();
        $month = $now->getMonth();

        $firstDay = "{$year}-" . str_pad($month, 2, '0', STR_PAD_LEFT) . "-01";
        $lastDay = date('Y-m-t', strtotime($firstDay));

        $pemakaianModel = new \App\Models\PemakaianModel();
        $pemakaianThisMonth = $pemakaianModel
            ->where("created_at >=", $firstDay)
            ->where("created_at <=", $lastDay)
            ->findAll();

        return [
            'pemakaianThisMonth' => $pemakaianThisMonth,
            'countPemakaianThisMonth' => count($pemakaianThisMonth),
        ];
    }

    protected function getPurchaseOrderToday(): array
    {
        $now = Time::now('Asia/Jakarta')->toDateString();
        $start = "$now 00:00:00";
        $end = "$now 23:59:59";

        $purchaseOrderModel = new \App\Models\PurchaseOrderModel();
        $poToday = $purchaseOrderModel
            ->where('created_at >=', $start)
            ->where('created_at <=', $end)
            ->findAll();

        return ['poToday' => $poToday];
    }

    protected function getPenerimaanToday(): array
    {
        $now = Time::now('Asia/Jakarta')->toDateString();
        $start = "$now 00:00:00";
        $end = "$now 23:59:59";

        $penerimaanModel = new \App\Models\PenerimaanModel();
        $penerimaanToday = $penerimaanModel
            ->where('created_at >=', $start)
            ->where('created_at <=', $end)
            ->findAll();

        return ['penerimaanToday' => $penerimaanToday];
    }

    protected function getReturToday(): array
    {
        $now = Time::now('Asia/Jakarta')->toDateString();
        $start = "$now 00:00:00";
        $end = "$now 23:59:59";

        $returModel = new \App\Models\ReturModel();
        $returToday = $returModel
            ->where('created_at >=', $start)
            ->where('created_at <=', $end)
            ->findAll();

        return ['returToday' => $returToday];
    }

    protected function getPemakaianToday(): array
    {
        $now = Time::now('Asia/Jakarta')->toDateString();
        $start = "$now 00:00:00";
        $end = "$now 23:59:59";

        $pemakaianModel = new \App\Models\PemakaianModel();
        $pemakaianToday = $pemakaianModel
            ->where('created_at >=', $start)
            ->where('created_at <=', $end)
            ->findAll();

        return ['pemakaianToday' => $pemakaianToday];
    }

        protected function getPOOverviewLast7Days(): array
    {
        $now = Time::now('Asia/Jakarta');
        $dates = [];
        $poCounts = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = $now->subDays($i)->toDateString();
            $start = "$date 00:00:00";
            $end = "$date 23:59:59";

            $purchaseOrderModel = new \App\Models\PurchaseOrderModel();
            $count = $purchaseOrderModel
                ->where('created_at >=', $start)
                ->where('created_at <=', $end)
                ->countAllResults();

            $dates[] = date('d-m', strtotime($date));
            $poCounts[] = $count;
        }

        return [
            'lastWeekDateStringRange' => $dates,
            'poOverview' => $poCounts
        ];
    }

    protected function getPenerimaanOverviewLast7Days(): array
    {
        $now = Time::now('Asia/Jakarta');
        $dates = [];
        $penerimaanCounts = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = $now->subDays($i)->toDateString();
            $start = "$date 00:00:00";
            $end = "$date 23:59:59";

            $penerimaanModel = new \App\Models\PenerimaanModel();
            $count = $penerimaanModel
                ->where('created_at >=', $start)
                ->where('created_at <=', $end)
                ->countAllResults();

            $dates[] = date('d-m', strtotime($date));
            $penerimaanCounts[] = $count;
        }

        return [
            'penerimaanOverview' => $penerimaanCounts,
            'lastWeekDateStringRange' => $dates
        ];
    }

    protected function getReturOverviewLast7Days(): array
    {
        $now = Time::now('Asia/Jakarta');
        $dates = [];
        $returCounts = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = $now->subDays($i)->toDateString();
            $start = "$date 00:00:00";
            $end = "$date 23:59:59";

            $returModel = new \App\Models\ReturModel();
            $count = $returModel
                ->where('created_at >=', $start)
                ->where('created_at <=', $end)
                ->countAllResults();

            $dates[] = date('d-m', strtotime($date));
            $returCounts[] = $count;
        }

        return [
            'returOverview' => $returCounts,
            'lastWeekDateStringRange' => $dates
        ];
    }

    protected function getPemakaianOverviewLast7Days(): array
    {
        $now = Time::now('Asia/Jakarta');
        $dates = [];
        $pemakaianCounts = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = $now->subDays($i)->toDateString();
            $start = "$date 00:00:00";
            $end = "$date 23:59:59";

            $pemakaianModel = new \App\Models\PemakaianModel();
            $count = $pemakaianModel
                ->where('created_at >=', $start)
                ->where('created_at <=', $end)
                ->countAllResults();

            $dates[] = date('d-m', strtotime($date));
            $pemakaianCounts[] = $count;
        }

        return [ 
            'pemakaianOverview' => $pemakaianCounts,
            'lastWeekDateStringRange' => $dates
        ];
    }

    protected function getBarangHampirHabis(): array
    {
        $stokBahanModel = new \App\Models\StokBahanModel();
        $data = $stokBahanModel->getBarangHampirHabis();

        // Kalau mau pastikan perhitungan sisa
        foreach ($data as &$barang) {
            $barang['sisa'] = floatval($barang['qty_diterima']) - (floatval($barang['qty_retur']) + floatval($barang['qty_pemakaian']));
        }

        return [
            'barangHampirHabis' => $data
        ];
    }

    public function barangHampirHabisPage()
    {
        $stokBahanModel = new \App\Models\StokBahanModel();
        $data = $stokBahanModel->getBarangHampirHabis();

        return view('dashboard/empty_alert/index', [
            'barangHampirHabis' => $data,
        ]);
    }

    protected function getPOStatus(): array
    {
        $purchaseOrderModel = new \App\Models\PurchaseOrderModel();
        $db = \Config\Database::connect();

        // Cek total PO yang sudah diterima (sudah ada di tabel penerimaan_pembelian)
        $poSudahDiterima = $db->table('purchase_order po')
            ->join('penerimaan_pembelian pp', 'po.po_id = pp.po_id')
            ->select('po.po_id')
            ->groupBy('po.po_id')
            ->countAllResults();

        $poBelumDiterima = $purchaseOrderModel
            ->select('po_id')
            ->whereNotIn('po_id', function($builder) {
                return $builder->select('po_id')->from('penerimaan_pembelian');
            })
            ->countAllResults();

        return [
            'poBelumDiterima' => $poBelumDiterima,
            'poSudahDiterima' => $poSudahDiterima,
        ];
    }

    public function statusPOPage()
    {
        $status = $this->request->getGet('status');
        $statusFilter = ($status === 'Belum Diterima' || $status === 'Sudah Diterima') ? $status : null;

        $data = $this->getPOStatusDetail($statusFilter);
        return view('dashboard/status_po/index', $data);
    }

    protected function getPOStatusDetail($statusFilter = null): array
    {
        $purchaseOrderModel = new \App\Models\PurchaseOrderModel();
        $perPage = 50;

        $builder = $purchaseOrderModel
            ->select('purchase_order.po_id, purchase_order.nomor_po, purchase_order.max_tgl_kirim, 
                    COUNT(detail_po.detpo_id) AS jumlah,
                    CASE 
                        WHEN EXISTS (SELECT 1 FROM penerimaan_pembelian WHERE penerimaan_pembelian.po_id = purchase_order.po_id) 
                        THEN "Sudah Diterima" 
                        ELSE "Belum Diterima" 
                    END AS status_po')
            ->join('detail_po', 'detail_po.po_id = purchase_order.po_id', 'left')
            ->groupBy('purchase_order.po_id')
            ->orderBy('purchase_order.created_at', 'DESC');

        if ($statusFilter) {
            $builder->having('status_po', $statusFilter);
        }

        $purchase_orders = $builder->paginate($perPage, 'default');
        $pager = $purchaseOrderModel->pager;

        return [
            'purchase_orders' => $purchase_orders,
            'pager' => $pager,
            'statusFilter' => $statusFilter
        ];
    }
}