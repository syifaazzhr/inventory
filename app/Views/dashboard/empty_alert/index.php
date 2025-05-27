<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Stok Bahan Hampir Habis</title>
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
    <div class="mb-4">
      <a href="<?= base_url('admin/dashboard'); ?>" class="btn btn-outline-primary"> 
          <i class="ti ti-arrow-left"></i>
          Kembali
      </a>
    </div>
    <div class="row mb-2">
      <div class="col-12 col-lg-5">
        <h5 class="card-title fw-semibold mb-4">Data Stok Bahan yang Hampir Habis</h5>
      </div>
      <div class="col-12 col-lg-7">
        <div class="d-flex gap-2 justify-content-md-end">
          <div>
            <form action="<?= base_url('admin/purchase_order'); ?>" method="get">
              <div class="input-group mb-3">
                <input type="text" class="form-control" name="search" value="<?= esc($search ?? ''); ?>" aria-label="Cari Penerimaan Pembelian">
                <button class="btn btn-outline-secondary" type="submit">Cari</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-hover table-striped">
        <thead class="table-light">
          <tr>
              <th>Kode Stok</th>
              <th>Nama Barang</th>
              <th>Sisa</th>
              <?php if (auth()->user()->inGroup('admin')) : ?>
                  <th>Aksi</th>
              <?php endif; ?>
          </tr>
        </thead>
        <tbody class="table-group-divider">
            <?php if (empty($barangHampirHabis)) : ?>
                <tr class="table-light">
                    <td class="text-center" colspan="<?= auth()->user()->inGroup('admin') ? '4' : '3' ?>"><span class="text-body"><b>Tidak ada barang hampir habis</b></span></td>
                </tr>
            <?php else : ?>
                <?php foreach ($barangHampirHabis as $barang) : ?>
                    <tr class="table-light">
                        <td><span class="text-body"><?= esc($barang['kode_stok']); ?></span></td>
                        <td><span class="text-body"><?= esc($barang['nama_barang']); ?></span></td>
                        <td><span class="text-body"><?= $barang['sisa'] == 0 ? '-' : number_format($barang['sisa'], 2, '.', ',') ?></span></td>
                        <?php if (auth()->user()->inGroup('admin')) : ?>
                            <td>
                                <a href="<?= base_url('admin/purchase_order/create?from=empty'); ?>" class="btn btn-info">Buat PO</a>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?= $this->endSection() ?>