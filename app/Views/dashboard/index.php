<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Dashboard</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- REPORT BULAN INI -->
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header card-header-primary">
        <h3 class="card-title"><b>Laporan Bulan Ini</b></h3>
        <?= $dateNow->toLocalizedString('MMMM Y'); ?>
      </div>
      <div class="card-body">
        <div class="row text-center g-3">
          <!-- PURCHASE ORDER BULAN INI -->
          <div class="col-12 col-sm-6 col-lg-3">
            <a href="<?= base_url('admin/purchase_order?month=' . date('Y-m') . '&from=dashboard'); ?>">
              <div class="p-3 border rounded shadow-sm bg-light">
                <h2><i class="ti ti-file-text"></i></h2>
                <h4><b><?= $countPOThisMonth ?? 0; ?> Purchase Order</b></h4>
              </div>
            </a>
          </div>
          <!-- PENERIMAAN BULAN INI -->
          <div class="col-12 col-sm-6 col-lg-3">
            <a href="<?= base_url('admin/penerimaan_pembelian?month=' . date('Y-m')); ?>">
              <div class="p-3 border rounded shadow-sm bg-light">
                <h2><i class="ti ti-box"></i></h2>
                <h4><b><?= $countPenerimaanThisMonth ?? 0; ?> Penerimaan</b></h4>
              </div>
            </a>
          </div>
          <!-- RETUR BULAN INI -->
          <div class="col-12 col-sm-6 col-lg-3">
            <a href="<?= base_url('admin/retur?month=' . date('Y-m')); ?>">
              <div class="p-3 border rounded shadow-sm bg-light">
                <h2><i class="ti ti-columns"></i></h2>
                <h4><b><?= $countReturThisMonth ?? 0; ?> Retur</b></h4>
              </div>
            </a>
          </div>
          <!-- PEMAKAIAN BULAN INI -->
          <div class="col-12 col-sm-6 col-lg-3">
            <a href="<?= base_url('admin/pemakaian?month=' . date('Y-m')); ?>">
              <div class="p-3 border rounded shadow-sm bg-light">
                <h2><i class="ti ti-columns"></i></h2>
                <h4><b><?= $countPemakaianThisMonth ?? 0; ?> Pemakaian</b></h4>
              </div>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- REPORT HARI INI -->
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header card-header-primary">
        <h3 class="card-title"><b>Laporan Hari Ini</b></h3>
        <?= $dateNow->toLocalizedString('d MMMM Y'); ?>
      </div>
      <div class="card-body">
        <div class="row text-center g-3">
          <!-- PURCHASE ORDER HARI INI -->
          <div class="col-12 col-sm-6 col-lg-3">
            <h4 class="text-primary"><b>Purchase Order</b></h4>
            <h3><?= count($poToday); ?></h3>
          </div>
          <!-- PENERIMAAN HARI INI -->
          <div class="col-12 col-sm-6 col-lg-3">
            <h4 class="text-success"><b>Penerimaan</b></h4>
            <h3><?= count($penerimaanToday); ?></h3>
          </div>
          <!-- RETUR HARI INI -->
          <div class="col-12 col-sm-6 col-lg-3">
            <h4 class="text-danger"><b>Retur</b></h4>
            <h3><?= count($returToday); ?></h3>
          </div>
          <!-- PEMAKAIAN HARI INI -->
          <div class="col-12 col-sm-6 col-lg-3">
            <h4 class="text-danger"><b>Pemakaian</b></h4>
            <h3><?= count($pemakaianToday); ?></h3>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <!-- OVERVIEW CHART -->
  <div class="col-lg-8 d-flex align-items-strech">
    <div class="card w-100">
      <div class="card-body">
        <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">
          <div class="mb-3 mb-sm-0">
            <h5 class="card-title fw-semibold">Laporan 7 hari terakhir</h5>
          </div>
        </div>
        <div id="chart"></div>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="row">
      <!-- BARANG HAMPIR HABIS -->
      <div class="col-lg-12">
        <a href="<?= base_url('admin/dashboard/empty_alert'); ?>" style="text-decoration: none; color: inherit;">
          <div class="card overflow-hidden hover-shadow">
            <div class="card-body">
              <h1 class="card-title mb-9 fw-semibold"> Stok Bahan Hampir Habis </h1>
              <div class="row align-items-start">
                <div class="col-9">
                  <h5 class="fw-semibold mb-3"><?= count($barangHampirHabis ?? []); ?> Barang</h5>
                  <div class="d-flex align-items-center">
                    <div class="me-4">
                      <span class="fs-2"><?= $dateNow->toLocalizedString('MMMM Y'); ?></span>
                    </div>
                  </div>
                </div>
                <div class="col-3">
                  <div class="d-flex justify-content-end">
                    <div class="text-white bg-warning rounded-circle p-6 d-flex align-items-center justify-content-center">
                      <i class="ti ti-alert-triangle fs-6"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
      <!-- STATUS PO -->
      <div class="col-lg-12">
        <a href="<?= base_url('admin/dashboard/status_po'); ?>" style="text-decoration: none; color: inherit;">
          <div class="card overflow-hidden hover-shadow">
            <div class="card-body">
              <h1 class="card-title mb-4 fw-semibold"> Status Purchase Order </h1>
              <div class="row align-items-start">
                <div class="col-9">
                  <h5 class="fw-semibold mb-3"><?= $poBelumDiterima ?? 0; ?> Belum Diterima</h5>
                  <!-- <h5 class="fw-semibold mb-3"><?= $poSudahDiterima ?? 0; ?> Sudah Diterima</h5> -->
                   <div class="d-flex align-items-center">
                    <div class="me-4">
                      <span class="fs-2"><?= $dateNow->toLocalizedString('MMMM Y'); ?></span>
                    </div>
                  </div>
                </div>
                <div class="col-3">
                  <div class="d-flex justify-content-end">
                    <div class="text-white bg-info rounded-circle p-6 d-flex align-items-center justify-content-center">
                      <i class="ti ti-file-check fs-6"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url("assets/libs/apexcharts/apexcharts.min.js") ?>"></script>
<script>
  $(function() {
    const poData = [
      <?php foreach ($poOverview as $value) : ?>
        <?= "'{$value}', "; ?>
      <?php endforeach; ?>
    ].map(value => parseInt(value));
      
    const penerimaanData = [
      <?php foreach ($penerimaanOverview as $value) : ?>
        <?= "'{$value}', "; ?>
      <?php endforeach; ?>
    ].map(value => parseInt(value));
      
    const returData = [
      <?php foreach ($returOverview as $value) : ?>
        <?= "'{$value}', "; ?>
      <?php endforeach; ?>
    ].map(value => parseInt(value));

    const pemakaianData = [
      <?php foreach ($pemakaianOverview as $value) : ?>
        <?= "'{$value}', "; ?>
      <?php endforeach; ?>
    ].map(value => parseInt(value));

    const highestValue = Math.max(
      Math.max(...poData),
      Math.max(...penerimaanData),
      Math.max(...returData),
      Math.max(...pemakaianData)
    );

    var chart = {
      series: [
        {
          name: "Purchase Order",
          type: 'bar',
          data: poData
        },
        {
          name: "Penerimaan",
          type: 'bar',
          data: penerimaanData
        },
        {
          name: "Retur",
          type: 'bar',
          data: returData
        },
        {
          name: "Pemakaian",
          type: 'bar',
          data: pemakaianData
        },
      ],
      chart: {
        type: "bar",
        height: 400,
        offsetX: -15,
        toolbar: {
          show: true
        },
        foreColor: "#adb0bb",
        fontFamily: 'inherit',
        sparkline: {
          enabled: false
        },
      },
      plotOptions: {
        bar: {
          columnWidth: '80%',
          dataLabels: {
            position: 'top',
          }
        }
      },
      colors: ["#007bff", "#28a745", "#dc3545", "#ffc107"], 
      markers: {
        size: 0
      },
      dataLabels: {
        enabled: false,
        offsetY: -17,
        style: {
          colors: ['#666666']
        },
      },
      legend: {
        show: true
      },
      grid: {
        borderColor: "rgba(0,0,0,0.1)",
        strokeDashArray: 3,
        xaxis: {
          lines: {
            show: false,
          },
        },
      },
      xaxis: {
        type: "category",
        categories: [
          <?php foreach ($lastWeekDateStringRange as $value) : ?>
            <?= "'{$value}', "; ?>
          <?php endforeach; ?>
        ],
        labels: {
          style: {
            cssClass: "fill-color"
          },
        },
      },
      yaxis: {
        show: true,
        min: 0,
        max: () => {
          const roundedHighestValue = (Math.ceil(highestValue / 10) * 10);

          if (roundedHighestValue <= 30) {
            return roundedHighestValue + 5;
          } else {
            return roundedHighestValue + 10;
          }
        },
        tickAmount: 5,
        labels: {
          style: {
            cssClass: "fill-color",
          },
        },
      },
      tooltip: {
        theme: "light"
      },
      responsive: [{
        breakpoint: 600,
        options: {
          plotOptions: {
            bar: {
              columnWidth: '100%',
            }
          },
          dataLabels: {
            enabled: false,
          },
        }
      }]
    };
    new ApexCharts(document.querySelector("#chart"), chart).render();
  })
</script>
<?= $this->endSection() ?>