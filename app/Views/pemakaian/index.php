<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Pemakaian Bahan</title>
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
        <h5 class="card-title fw-semibold mb-4">Data Pemakaian Bahan</h5>
      </div>
      <div class="col-12 col-lg-7">
        <div class="d-flex gap-2 justify-content-md-end">
          <div>
            <form action="<?= base_url('admin/pemakaian'); ?>" method="get">
              <div class="input-group mb-3">
                <input type="text" class="form-control" name="search" value="<?= esc($search ?? ''); ?>" aria-label="Cari Pemakaian Bahan">
                <button class="btn btn-outline-secondary" type="submit">Cari</button>
              </div>
            </form>
          </div>
          <?php if (auth()->user()->inGroup('admin')) : ?>
          <div>
            <a href="<?= base_url('admin/pemakaian/download'); ?>" class="btn py-2" style="background-color: #1d8e44; color: white;">
                <i class="ti ti-download"></i>
                Simpan ke Excel
            </a>
          </div>
          <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle py-2" type="button" id="dropdownTambah" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="ti ti-plus"></i> Tambah
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownTambah">
              <li><a class="dropdown-item" href="<?= base_url('admin/pemakaian/create?jenis=Aksesoris'); ?>">Bahan Aksesoris</a></li>
              <li><a class="dropdown-item" href="<?= base_url('admin/pemakaian/create?jenis=Kain'); ?>">Bahan Kain</a></li>
            </ul>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <div class="col-12 mb-3">
      <form action="<?= base_url('admin/pemakaian'); ?>" method="get">
        <div class="d-flex gap-2 align-items-end">
          <p class="my-auto fw-semibold">Filter:</p>
          <div>
              <input type="date" class="form-control" name="start_date" value="<?= esc($start_date ?? '') ?>" style="text-transform: none;">
          </div>
          <div>
              <input type="date" class="form-control" name="end_date" value="<?= esc($end_date ?? '') ?>" style="text-transform: none;">
          </div>
          <div>
            <button class="btn btn-success py-2" type="submit" name="filter_type" value="tgl_keluar">
              <i class="ti ti-filter"></i> Tanggal Keluar
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
              <a href="<?= current_url() . '?order_by=nomor_pakai&order=' . ($order === 'asc' ? 'desc' : 'asc'); ?>" style="color: #000; text-decoration: none;">
                Nomor Pakai
                <?php if ($orderBy === 'nomor_pakai') : ?>
                  <i class="ti <?= ($order === 'asc') ? 'ti-arrow-up' : 'ti-arrow-down'; ?>"></i>
                <?php endif; ?>
              </a>
            </th>
            <th>
              <a href="<?= current_url() . '?order_by=tgl_keluar&order=' . ($order === 'asc' ? 'desc' : 'asc'); ?>" style="color: #000; text-decoration: none;">
                Tanggal Keluar
                <?php if ($orderBy === 'tgl_keluar') : ?>
                  <i class="ti <?= ($order === 'asc') ? 'ti-arrow-up' : 'ti-arrow-down'; ?>"></i>
                <?php endif; ?>
              </a>
            </th>
            <th>
              <a href="<?= current_url() . '?order_by=nomor_ws_warna_pakai&order=' . ($order === 'asc' ? 'desc' : 'asc'); ?>" style="color: #000; text-decoration: none;">
                Nomor Worksheet Warna
                <?php if ($orderBy === 'nomor_ws_warna_pakai') : ?>
                  <i class="ti <?= ($order === 'asc') ? 'ti-arrow-up' : 'ti-arrow-down'; ?>"></i>
                <?php endif; ?>
              </a>
            </th>
            <th>
              <a href="<?= current_url() . '?order_by=nomor_ws_pakai&order=' . ($order === 'asc' ? 'desc' : 'asc'); ?>" style="color: #000; text-decoration: none;">
                Nomor Worksheet
                <?php if ($orderBy === 'nomor_ws_pakai') : ?>
                  <i class="ti <?= ($order === 'asc') ? 'ti-arrow-up' : 'ti-arrow-down'; ?>"></i>
                <?php endif; ?>
              </a>
            </th>
            <th>
              <a href="<?= current_url() . '?order_by=nomor_art_pakai&order=' . ($order === 'asc' ? 'desc' : 'asc'); ?>" style="color: #000; text-decoration: none;">
                Nomor Artikel
                <?php if ($orderBy === 'nomor_art_pakai') : ?>
                  <i class="ti <?= ($order === 'asc') ? 'ti-arrow-up' : 'ti-arrow-down'; ?>"></i>
                <?php endif; ?>
              </a>
            </th>
            <th>
              <a href="<?= current_url() . '?order_by=nama_penerima&order=' . ($order === 'asc' ? 'desc' : 'asc'); ?>" style="color: #000; text-decoration: none;">
                Nama Penerima
                <?php if ($orderBy === 'nama_penerima') : ?>
                  <i class="ti <?= ($order === 'asc') ? 'ti-arrow-up' : 'ti-arrow-down'; ?>"></i>
                <?php endif; ?>
              </a>
            </th>
            <th>
              <a href="<?= current_url() . '?order_by=ket_pakai&order=' . ($order === 'asc' ? 'desc' : 'asc'); ?>" style="color: #000; text-decoration: none;">
                Keterangan
                <?php if ($orderBy === 'ket_pakai') : ?>
                  <i class="ti <?= ($order === 'asc') ? 'ti-arrow-up' : 'ti-arrow-down'; ?>"></i>
                <?php endif; ?>
              </a>
            </th>
            <th>
              <a href="<?= current_url() . '?order_by=admin_pakai&order=' . ($order === 'asc' ? 'desc' : 'asc'); ?>" style="color: #000; text-decoration: none;">
                Admin
                <?php if ($orderBy === 'admin_pakai') : ?>
                  <i class="ti <?= ($order === 'asc') ? 'ti-arrow-up' : 'ti-arrow-down'; ?>"></i>
                <?php endif; ?>
              </a>
            </th>
            <th>
              <a href="<?= current_url() . '?order_by=total_qty_pakai&order=' . ($order === 'asc' ? 'desc' : 'asc'); ?>" style="color: #000; text-decoration: none;">
                Total Kuantitas
                <?php if ($orderBy === 'total_qty_pakai') : ?>
                  <i class="ti <?= ($order === 'asc') ? 'ti-arrow-up' : 'ti-arrow-down'; ?>"></i>
                <?php endif; ?>
              </a>
            </th>
            <th class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody class="table-group-divider">
          <?php if (empty($pemakaian)) : ?>
            <tr>
              <td class="text-center" colspan="12"><b>Tidak ada data</b></td>
            </tr>
          <?php else : ?>
            <?php $i = 1 + ($pager->getCurrentPage('default') - 1) * 10; ?>
            <?php foreach ($pemakaian as $data) : ?>
              <tr>
                <td><?= esc($data['nomor_pakai']) ?></td>
                <td><?= date('d-m-Y', strtotime($data['tgl_keluar'])) ?></td>
                <td><?= esc($data['nomor_ws_warna_pakai']) ?></td>
                <td><?= esc($data['nomor_ws_pakai']) ?></td>
                <td><?= esc($data['nomor_art_pakai']) ?></td>
                <td><?= esc($data['nama_penerima']) ?></td>
                <td><?= esc($data['ket_pakai']) ?></td>
                <td><?= esc($data['admin_pakai']) ?></td>
                <td><?= number_format($data['total_qty_pakai'], 2, '.', ',') ?></td>
                <td class="text-center">
                  <a href="<?= base_url("admin/pemakaian/" . esc($data['pakai_id'])); ?>" class="btn btn-info">
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
    if (performance.navigation.type === 1) { 
        document.querySelector('input[name="start_date"]').value = '';
        document.querySelector('input[name="end_date"]').value = '';
        
        if (window.location.search) {
            const cleanUrl = window.location.origin + window.location.pathname;
            window.history.replaceState({}, document.title, cleanUrl);
            location.reload();
        }
    }
</script>

<?= $this->endSection() ?>