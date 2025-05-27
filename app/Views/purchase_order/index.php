<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Purchase Order</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php if (session()->getFlashdata('msg')) : ?>
  <div class="pb-2">
    <div class="alert <?= (session()->getFlashdata('error') ?? false) ? 'alert-danger' : 'alert-success'; ?> alert-dismissible fade show" role="alert">
      <?= session()->getFlashdata('msg') ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  </div>
<?php endif; ?>

<link rel="stylesheet" href="<?= base_url('assets/css/home.css'); ?>">

<div class="card">
  <div class="card-body">
    <div class="row mb-2">
      <div class="col-12 col-lg-5">
        <h5 class="card-title fw-semibold mb-4">Data Purchase Order</h5>
      </div> 
      <div class="col-12 col-lg-7">
        <div class="d-flex gap-2 justify-content-md-end">
          <div>
            <form action="<?= base_url('admin/purchase_order'); ?>" method="get">
              <div class="input-group mb-3">
                <input type="text" class="form-control" name="search" value="<?= esc($search ?? ''); ?>" aria-label="Cari Purchase Order">
                <button class="btn btn-outline-secondary" type="submit">Cari</button>
              </div>
            </form>
          </div>
          <?php if (auth()->user()->inGroup('admin')) : ?>
          <div>
            <a href="<?= base_url('admin/purchase_order/download'); ?>" class="btn py-2" style="background-color: #1d8e44; color: white;">
                <i class="ti ti-download"></i>
                Simpan ke Excel
            </a> 
          </div>
          <div>
            <a href="<?= base_url('admin/purchase_order/create?from=index'); ?>" class="btn btn-primary py-2"><i class="ti ti-plus"></i>
                Tambah
            </a>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <div class="col-12 mb-3">
      <form action="<?= base_url('admin/purchase_order'); ?>" method="get">
        <div class="d-flex gap-2 align-items-end">
          <p class="my-auto fw-semibold">Filter:</p>
          <div>
              <input type="date" class="form-control" name="start_date" value="<?= esc($start_date ?? '') ?>" style="text-transform: none;">
          </div>
          <div>
              <input type="date" class="form-control" name="end_date" value="<?= esc($end_date ?? '') ?>" style="text-transform: none;">
          </div>
          <div>
            <button class="btn btn-success py-2" type="submit" name="filter_type" value="tgl_order">
              <i class="ti ti-filter"></i> Tanggal Order
            </button>
          </div>
          <div>
            <button class="btn btn-success py-2" type="submit" name="filter_type" value="max_tgl_kirim">
              <i class="ti ti-filter"></i> Tanggal Kirim
            </button>
          </div>
        </div>
      </form>
    </div>
    <div class="table-responsive">
      <table class="table table-hover table-striped">
        <thead class="table-light">
          <tr>
            <th>
              <a href="<?= current_url() . '?order_by=nomor_po&order=' . ($order === 'asc' ? 'desc' : 'asc'); ?>" style="color: #000; text-decoration: none;">
                Nomor PO
                <?php if ($orderBy === 'nomor_po') : ?>
                  <i class="ti <?= ($order === 'asc') ? 'ti-arrow-up' : 'ti-arrow-down'; ?>"></i>
                <?php endif; ?>
              </a>
            </th>
            <th>
              <a href="<?= current_url() . '?order_by=tgl_order&order=' . ($order === 'asc' ? 'desc' : 'asc'); ?>" style="color: #000; text-decoration: none;">
                Tanggal Order
                <?php if ($orderBy === 'tgl_order') : ?>
                  <i class="ti <?= ($order === 'asc') ? 'ti-arrow-up' : 'ti-arrow-down'; ?>"></i>
                <?php endif; ?>
              </a>
            </th>
            <th>
              <a href="<?= current_url() . '?order_by=max_tgl_kirim&order=' . ($order === 'asc' ? 'desc' : 'asc'); ?>" style="color: #000; text-decoration: none;">
                Tanggal Kirim
                <?php if ($orderBy === 'max_tgl_kirim') : ?>
                  <i class="ti <?= ($order === 'asc') ? 'ti-arrow-up' : 'ti-arrow-down'; ?>"></i>
                <?php endif; ?>
              </a>
            </th>
            <th>
              <a href="<?= current_url() . '?order_by=nomor_ws&order=' . ($order === 'asc' ? 'desc' : 'asc'); ?>" style="color: #000; text-decoration: none;">
                Nomor Worksheet
                <?php if ($orderBy === 'nomor_ws') : ?>
                  <i class="ti <?= ($order === 'asc') ? 'ti-arrow-up' : 'ti-arrow-down'; ?>"></i>
                <?php endif; ?>
              </a>
            </th>
            <th>
              <a href="<?= current_url() . '?order_by=nomor_art&order=' . ($order === 'asc' ? 'desc' : 'asc'); ?>" style="color: #000; text-decoration: none;">
                Nomor Artikel
                <?php if ($orderBy === 'nomor_art') : ?>
                  <i class="ti <?= ($order === 'asc') ? 'ti-arrow-up' : 'ti-arrow-down'; ?>"></i>
                <?php endif; ?>
              </a>
            </th>
            <th>
              <a href="<?= current_url() . '?order_by=kode_supplier&order=' . ($order === 'asc' ? 'desc' : 'asc'); ?>" style="color: #000; text-decoration: none;">
                Kode Supplier
                <?php if ($orderBy === 'kode_supplier') : ?>
                  <i class="ti <?= ($order === 'asc') ? 'ti-arrow-up' : 'ti-arrow-down'; ?>"></i>
                <?php endif; ?>
              </a>
            </th>
            <th>
              <a href="<?= current_url() . '?order_by=nama_supplier&order=' . ($order === 'asc' ? 'desc' : 'asc'); ?>" style="color: #000; text-decoration: none;">
                Nama Supplier
                <?php if ($orderBy === 'nama_supplier') : ?>
                  <i class="ti <?= ($order === 'asc') ? 'ti-arrow-up' : 'ti-arrow-down'; ?>"></i>
                <?php endif; ?>
              </a>
            </th>
            <th>
              <a href="<?= current_url() . '?order_by=ket_po&order=' . ($order === 'asc' ? 'desc' : 'asc'); ?>" style="color: #000; text-decoration: none;">
                Keterangan
                <?php if ($orderBy === 'ket_po') : ?>
                  <i class="ti <?= ($order === 'asc') ? 'ti-arrow-up' : 'ti-arrow-down'; ?>"></i>
                <?php endif; ?>
              </a>
            </th>
            <th>
              <a href="<?= current_url() . '?order_by=admin_po&order=' . ($order === 'asc' ? 'desc' : 'asc'); ?>" style="color: #000; text-decoration: none;">
                Admin
                <?php if ($orderBy === 'admin_po') : ?>
                  <i class="ti <?= ($order === 'asc') ? 'ti-arrow-up' : 'ti-arrow-down'; ?>"></i>
                <?php endif; ?>
              </a>
            </th>
            <th>
              <a href="<?= current_url() . '?order_by=total_qty&order=' . ($order === 'asc' ? 'desc' : 'asc'); ?>" style="color: #000; text-decoration: none;">
                Total Kuantitas
                <?php if ($orderBy === 'total_qty') : ?>
                  <i class="ti <?= ($order === 'asc') ? 'ti-arrow-up' : 'ti-arrow-down'; ?>"></i>
                <?php endif; ?>
              </a>
            </th>
            <th>
              <a href="<?= current_url() . '?order_by=jml_total_po&order=' . ($order === 'asc' ? 'desc' : 'asc'); ?>" style="color: #000; text-decoration: none;">
                Total Beli
                <?php if ($orderBy === 'jml_total_po') : ?>
                  <i class="ti <?= ($order === 'asc') ? 'ti-arrow-up' : 'ti-arrow-down'; ?>"></i>
                <?php endif; ?>
              </a>
            </th>
            <th>
              <a href="<?= current_url() . '?order_by=ppn_po&order=' . ($order === 'asc' ? 'desc' : 'asc'); ?>" style="color: #000; text-decoration: none;">
                PPN
                <?php if ($orderBy === 'ppn_po') : ?>
                  <i class="ti <?= ($order === 'asc') ? 'ti-arrow-up' : 'ti-arrow-down'; ?>"></i>
                <?php endif; ?>
              </a>
            </th>
            <th>
              <a href="<?= current_url() . '?order_by=jml_total_po_ppn&order=' . ($order === 'asc' ? 'desc' : 'asc'); ?>" style="color: #000; text-decoration: none;">
                Jumlah Total
                <?php if ($orderBy === 'jml_total_po_ppn') : ?>
                  <i class="ti <?= ($order === 'asc') ? 'ti-arrow-up' : 'ti-arrow-down'; ?>"></i>
                <?php endif; ?>
              </a>
            </th>
            <th class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody class="table-group-divider">
          <?php if (empty($purchase_orders)) : ?>
            <tr>
              <td class="text-center" colspan="12"><b>Tidak ada data</b></td>
            </tr>
          <?php else : ?>
            <?php $i = 1 + ($pager->getCurrentPage('default') - 1) * 10; ?>
            <?php foreach ($purchase_orders as $po) : ?>
              <tr>
                <td><?= esc($po['nomor_po']) ?></td>
                <td><?= date('d-m-Y', strtotime($po['tgl_order'])) ?></td>
                <td><?= date('d-m-Y', strtotime($po['max_tgl_kirim'])) ?></td>
                <td><?= esc($po['nomor_ws']) ?></td>
                <td><?= esc($po['nomor_art']) ?></td>
                <td><?= esc($po['kode_supplier']) ?></td>
                <td><?= esc($po['nama_supplier']) ?></td>
                <td><?= esc($po['ket_po']) ?></td>
                <td><?= esc($po['admin_po']) ?></td>
                <td><?= number_format($po['total_qty'], 2, '.', ',') ?></td>
                <td><?= number_format($po['jml_total_po'], 2, '.', ',') ?></td>
                <td><?= number_format($po['ppn_po'], 2, '.', ',') ?></td>
                <td><?= number_format($po['jml_total_po_ppn'], 2, '.', ',') ?></td>
                <td class="text-center">
                  <a href="<?= base_url('admin/purchase_order/' . $po['po_id'] . '?from=index'); ?>" class="btn btn-info">
                    <i class="ti ti-eye"></i> Detail
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
    <div class="d-flex justify-content-center mt-3">
      <?= $pager->links('default', 'custom_pager') ?>
    </div>
  </div>
</div>

<script>
    const startDateField = document.querySelector('input[name="start_date"]');
    const endDateField = document.querySelector('input[name="end_date"]');

    if (performance.navigation.type === 1) { 
        startDateField.value = '';
        endDateField.value = '';
 
        if (window.location.search) {
            const cleanUrl = window.location.origin + window.location.pathname;
            window.history.replaceState({}, document.title, cleanUrl);
            location.reload();
        }
    }
</script>

<?= $this->endSection() ?>