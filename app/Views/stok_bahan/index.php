<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Stok Bahan</title>
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
  <div class="row align-items-center mb-4">
  <div class="col-12 col-lg-4">
    <h5 class="card-title fw-semibold mb-3">Data Stok Bahan</h5>
  </div>
  <div class="col-12 col-lg-8">
    <div class="d-flex justify-content-end align-items-center gap-2 flex-nowrap">
      <form action="<?= base_url('admin/stok_bahan'); ?>" method="get" class="d-flex" style="height: 38px;">
        <div class="input-group" style="min-width: 200px;">
          <input type="text" class="form-control" name="search" value="<?= esc($search ?? ''); ?>">
          <button class="btn btn-outline-secondary" type="submit">Cari</button>
        </div>
      </form>
      <?php if (auth()->user()->inGroup('admin')) : ?>
      <a href="<?= base_url('admin/stok_bahan/download'); ?>"
        class="btn d-inline-flex align-items-center px-3"
        style="background-color: #1d8e44; color: white; height: 38px; white-space: nowrap;">
        <i class="ti ti-download me-1"></i> Simpan ke Excel
      </a>
      <?php endif; ?>
      <a href="<?= base_url('admin/stok_bahan/show'); ?>" 
        class="btn btn-info d-inline-flex align-items-center px-3" 
        style="height: 38px; white-space: nowrap;">
        <i class="ti ti-eye me-1"></i> Detail Stok
      </a>
      <?php if (auth()->user()->inGroup('admin')) : ?>
      <a href="<?= base_url('admin/stok_bahan/create'); ?>" 
        class="btn btn-primary d-inline-flex align-items-center px-3" 
        style="height: 38px; white-space: nowrap;">
        <i class="ti ti-plus me-1"></i> Tambah
      </a>
      <?php endif; ?>
    </div>
  </div>
</div>
    <div class="table-responsive">
      <table class="table table-hover table-striped">
        <thead class="table-light">
            <tr>
                <th scope="col">Kode Barang</th>
                <th scope="col">Nama Barang</th>
                <th scope="col">Grup</th>
                <th scope="col">Bahan</th> 
                <th scope="col">Warna</th>
                <th scope="col">Supplier</th>
                <th scope="col">Satuan</th>
                <th scope="col">Harga Beli</th>
                <th scope="col">Beli</th>
                <th scope="col">Retur</th>
                <th scope="col">Pakai</th>
                <th scope="col">Sisa</th>
                <th scope="col">Terakhir Diterima</th>
                <?php if (auth()->user()->inGroup('admin')) : ?>
                  <th scope="col" class="text-center">Aksi</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody class="table-group-divider">
          <?php $i = 1 + ($pager->getCurrentPage('default') - 1) * 10; ?>
          <?php if (empty($stok_bahan)) : ?>
            <tr>
              <td class="text-center" colspan="14"><b>Tidak ada data</b></td>
            </tr>
          <?php else : ?>
            <?php foreach ($stok_bahan as $stok) : ?>
                <tr>
                    <td><?= esc($stok['kode_stok']) ?></td>
                    <td><?= esc($stok['nama_barang']) ?></td>
                    <td><?= esc($stok['nama_grup']) ?></td>
                    <td><?= esc($stok['nama_bahan']) ?></td>
                    <td><?= esc($stok['jenis_warna']) ?></td>
                    <td><?= esc($stok['nama_supplier']) ?></td>
                    <td><?= esc($stok['jenis_satuan']) ?></td>
                    <td><?= esc(number_format($stok['harga_beli'], 2, '.', ',')) ?></td>
                    <?php
                      // $awal = (float) ($stok['awal'] ?? 0);
                      $beli = (float) ($stok['beli'] ?? 0);
                      $retur = (float) ($stok['retur'] ?? 0);
                      $pakai = (float) ($stok['pakai'] ?? 0);
                      $sisa = ($beli) - ($retur + $pakai);
                    ?>
                    <!-- <td><?= number_format($stok['awal'] ?? 0, 2, '.', ',') ?></td> -->
                    <td><?= $beli == 0 ? '-' : number_format($beli, 2, '.', ',') ?></td>
                    <td><?= $retur == 0 ? '-' : number_format($retur, 2, '.', ',') ?></td> 
                    <td><?= $pakai == 0 ? '-' : number_format($pakai, 2, '.', ',') ?></td>
                    <td><?= $sisa == 0 ? '-' : number_format($sisa, 2, '.', ',') ?></td>
                    <td><?= !empty($stok['last_received']) ? date('d-m-Y', strtotime($stok['last_received'])) : '-' ?></td>
                    <?php if (auth()->user()->inGroup('admin')) : ?>
                    <td class="text-center">
                      <div class="d-flex justify-content-center gap-2">
                        <a href="<?= base_url("admin/stok_bahan/{$stok['stok_id']}/edit"); ?>" class="btn btn-primary">
                          <i class="ti ti-edit"></i> Ubah
                        </a>
                        <form action="<?= base_url("admin/stok_bahan/{$stok['stok_id']}"); ?>" method="post" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                          <?= csrf_field(); ?>
                          <input type="hidden" name="_method" value="DELETE">
                          <button type="button" class="btn btn-danger" onclick="showDeleteModal('<?= base_url("admin/stok_bahan/{$stok['stok_id']}") ?>')">
                            <i class="ti ti-trash"></i> Hapus
                          </button>
                        </form>
                      </div>
                    </td>
                    <?php endif; ?>
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

<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmDeleteModalLabel">Konfirmasi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        Yakin ingin menghapus data ini?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
        <form id="deleteFormModal" method="post">
          <?= csrf_field() ?>
          <input type="hidden" name="_method" value="DELETE">
          <button type="submit" class="btn btn-primary">Ya, Hapus</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  function showDeleteModal(deleteUrl) {
    const form = document.getElementById('deleteFormModal');
    form.action = deleteUrl;

    const modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
    modal.show();
  }
</script>

<?= $this->endSection() ?>