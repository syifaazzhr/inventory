<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Penerimaan Pembelian</title>
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
        <h5 class="card-title fw-semibold mb-4">Data Penerimaan Pembelian</h5>
      </div>
      <div class="col-12 col-lg-7">
        <div class="d-flex gap-2 justify-content-md-end">
          <div>
            <form action="<?= base_url('admin/penerimaan_pembelian'); ?>" method="get">
              <div class="input-group mb-3">
                <input type="text" class="form-control" name="search" value="<?= esc($search ?? ''); ?>" aria-label="Cari Penerimaan Pembelian">
                <button class="btn btn-outline-secondary" type="submit">Cari</button>
              </div>
            </form>
          </div>
          <?php if (auth()->user()->inGroup('admin')) : ?>
          <div>
            <a href="<?= base_url('admin/penerimaan_pembelian/download'); ?>" class="btn py-2" style="background-color: #1d8e44; color: white;">
                <i class="ti ti-download"></i>
                Simpan ke Excel
            </a>
          </div>
          <div>
              <a href="<?= base_url('admin/penerimaan_pembelian/create'); ?>" class="btn btn-primary py-2">
                  <i class="ti ti-plus"></i>
                  Tambah
              </a>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <div class="col-12 mb-3">
      <form action="<?= base_url('admin/penerimaan_pembelian'); ?>" method="get">
        <div class="d-flex gap-2 align-items-end">
          <p class="my-auto fw-semibold">Filter:</p>
          <div>
              <input type="date" class="form-control" name="start_date" value="<?= esc($start_date ?? '') ?>" style="text-transform: none;">
          </div>
          <div>
              <input type="date" class="form-control" name="end_date" value="<?= esc($end_date ?? '') ?>" style="text-transform: none;">
          </div>
          <div>
            <button class="btn btn-success py-2" type="submit" name="filter_type" value="tgl_terima">
              <i class="ti ti-filter"></i> Tanggal Terima
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
              <a href="<?= current_url() . '?order_by=nomor_bon&order=' . ($order === 'asc' ? 'desc' : 'asc'); ?>" style="color: #000; text-decoration: none;">
                Nomor Penerimaan
                <?php if ($orderBy === 'nomor_bon') : ?>
                  <i class="ti <?= ($order === 'asc') ? 'ti-arrow-up' : 'ti-arrow-down'; ?>"></i>
                <?php endif; ?>
              </a>
            </th>
            <th>
              <a href="<?= current_url() . '?order_by=tgl_terima&order=' . ($order === 'asc' ? 'desc' : 'asc'); ?>" style="color: #000; text-decoration: none;">
                Tanggal Terima
                <?php if ($orderBy === 'tgl_terima') : ?>
                  <i class="ti <?= ($order === 'asc') ? 'ti-arrow-up' : 'ti-arrow-down'; ?>"></i>
                <?php endif; ?>
              </a>
            </th>
            <th>
              <a href="<?= current_url() . '?order_by=nomor_po&order=' . ($order === 'asc' ? 'desc' : 'asc'); ?>" style="color: #000; text-decoration: none;">
                Nomor PO
                <?php if ($orderBy === 'nomor_po') : ?>
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
              <a href="<?= current_url() . '?order_by=nomor_faktur&order=' . ($order === 'asc' ? 'desc' : 'asc'); ?>" style="color: #000; text-decoration: none;">
                Nomor Faktur
                <?php if ($orderBy === 'nomor_faktur') : ?>
                  <i class="ti <?= ($order === 'asc') ? 'ti-arrow-up' : 'ti-arrow-down'; ?>"></i>
                <?php endif; ?>
              </a>
            </th>
            <th>
              <a href="<?= current_url() . '?order_by=surat_jalan&order=' . ($order === 'asc' ? 'desc' : 'asc'); ?>" style="color: #000; text-decoration: none;">
                Surat Jalan
                <?php if ($orderBy === 'surat_jalan') : ?>
                  <i class="ti <?= ($order === 'asc') ? 'ti-arrow-up' : 'ti-arrow-down'; ?>"></i>
                <?php endif; ?>
              </a>
            </th>
            <th>
              <a href="<?= current_url() . '?order_by=tgl_kirim&order=' . ($order === 'asc' ? 'desc' : 'asc'); ?>" style="color: #000; text-decoration: none;">
                Tanggal Kirim
                <?php if ($orderBy === 'tgl_kirim') : ?>
                  <i class="ti <?= ($order === 'asc') ? 'ti-arrow-up' : 'ti-arrow-down'; ?>"></i>
                <?php endif; ?>
              </a>
            </th>
            <th>
              <a href="<?= current_url() . '?order_by=tgl_jatuh_tempo&order=' . ($order === 'asc' ? 'desc' : 'asc'); ?>" style="color: #000; text-decoration: none;">
                Tanggal Jatuh Tempo
                <?php if ($orderBy === 'tgl_jatuh_tempo') : ?>
                  <i class="ti <?= ($order === 'asc') ? 'ti-arrow-up' : 'ti-arrow-down'; ?>"></i>
                <?php endif; ?>
              </a>
            </th>
            <th>
              <a href="<?= current_url() . '?order_by=admin_terima&order=' . ($order === 'asc' ? 'desc' : 'asc'); ?>" style="color: #000; text-decoration: none;">
                Admin
                <?php if ($orderBy === 'admin_terima') : ?>
                  <i class="ti <?= ($order === 'asc') ? 'ti-arrow-up' : 'ti-arrow-down'; ?>"></i>
                <?php endif; ?>
              </a>
            </th>
            <th>
              <a href="<?= current_url() . '?order_by=total_qty_terima&order=' . ($order === 'asc' ? 'desc' : 'asc'); ?>" style="color: #000; text-decoration: none;">
                Total Kuantitas
                <?php if ($orderBy === 'total_qty_terima') : ?>
                  <i class="ti <?= ($order === 'asc') ? 'ti-arrow-up' : 'ti-arrow-down'; ?>"></i>
                <?php endif; ?>
              </a>
            </th>
            <th>
              <a href="<?= current_url() . '?order_by=total_beli_terima&order=' . ($order === 'asc' ? 'desc' : 'asc'); ?>" style="color: #000; text-decoration: none;">
                Total Beli
                <?php if ($orderBy === 'total_beli_terima') : ?>
                  <i class="ti <?= ($order === 'asc') ? 'ti-arrow-up' : 'ti-arrow-down'; ?>"></i>
                <?php endif; ?>
              </a>
            </th>
            <th>
              <a href="<?= current_url() . '?order_by=ppn_terima&order=' . ($order === 'asc' ? 'desc' : 'asc'); ?>" style="color: #000; text-decoration: none;">
                PPN
                <?php if ($orderBy === 'ppn_terima') : ?>
                  <i class="ti <?= ($order === 'asc') ? 'ti-arrow-up' : 'ti-arrow-down'; ?>"></i>
                <?php endif; ?>
              </a>
            </th>
            <th>
              <a href="<?= current_url() . '?order_by=total_beli_terima_ppn&order=' . ($order === 'asc' ? 'desc' : 'asc'); ?>" style="color: #000; text-decoration: none;">
                Jumlah Total
                <?php if ($orderBy === 'total_beli_terima_ppn') : ?>
                  <i class="ti <?= ($order === 'asc') ? 'ti-arrow-up' : 'ti-arrow-down'; ?>"></i>
                <?php endif; ?>
              </a>
            </th>
            <th class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody class="table-group-divider">
          <?php if (empty($penerimaan_pembelian)) : ?> 
            <tr>
              <td class="text-center" colspan="13"><b>Tidak ada data</b></td>
            </tr>
          <?php else : ?>
            <?php $i = 1 + ($pager->getCurrentPage('default') - 1) * 10; ?>
            <?php foreach ($penerimaan_pembelian as $penerimaan) : ?>
              <tr>
                <td><?= esc($penerimaan['nomor_bon']) ?></td>
                <td><?= date('d-m-Y', strtotime($penerimaan['tgl_terima'])) ?></td>
                <td><?= esc($penerimaan['nomor_po']) ?></td>
                <td><?= esc($penerimaan['kode_supplier']) ?></td>
                <td><?= esc($penerimaan['nama_supplier']) ?></td>
                <td><?= esc($penerimaan['nomor_faktur']) ?></td>
                <td><?= esc($penerimaan['surat_jalan']) ?></td>
                <td><?= date('d-m-Y', strtotime($penerimaan['tgl_kirim']))?></td>
                <td><?= date('d-m-Y', strtotime($penerimaan['tgl_jatuh_tempo']))?></td>
                <td><?= esc($penerimaan['admin_terima']) ?></td>
                <td><?= number_format($penerimaan['total_qty_terima'], 2, '.', ',') ?></td>
                <td><?= number_format($penerimaan['total_beli_terima'], 2, '.', ',') ?></td>
                <td><?= number_format($penerimaan['ppn_terima'], 2, '.', ',') ?></td>
                <td><?= number_format($penerimaan['total_beli_terima_ppn'], 2, '.', ',') ?></td>
                <td class="text-center">
                  <a href="<?= base_url("admin/penerimaan_pembelian/" . esc($penerimaan['terima_id'])); ?>" class="btn btn-info">
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