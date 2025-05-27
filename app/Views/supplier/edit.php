<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Supplier</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<a href="<?= base_url('admin/supplier'); ?>" class="btn btn-outline-primary mb-3">
  <i class="ti ti-arrow-left"></i>
  Kembali
</a>

<?php if (session()->getFlashdata('msg')) : ?>
  <div class="pb-2">
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?= session()->getFlashdata('msg') ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  </div>
<?php endif; ?> 

<h5 class="card-title fw-semibold mb-4 text-dark">Ubah Data Supplier</h5>

<div class="card mb-4">
  <div class="card-body">
    <form action="<?= base_url("admin/supplier/{$supplier['supplier_id']}/update"); ?>" method="post">
      <?= csrf_field(); ?>
      <input type="hidden" name="page" value="<?= esc($page) ?>">
      <div class="row">
        <div class="col-12 col-md-6 mb-3">
          <label for="kode_supplier" class="form-label">Kode</label>
          <input type="text" class="form-control" id="kode_supplier" name="kode_supplier" value="<?= esc($supplier['kode_supplier']); ?>" readonly>
        </div>
        <div class="col-12 col-md-6 mb-3">
          <label for="nama_supplier" class="form-label">Nama</label>
          <input type="text" class="form-control <?php if ($validation->hasError('nama_supplier')) : ?>is-invalid<?php endif ?>" id="nama_supplier" name="nama_supplier" value="<?= esc($supplier['nama_supplier']); ?>" required>
          <div class="invalid-feedback">
            <?= $validation->getError('nama_supplier'); ?>
          </div>
        </div>
        <div class="col-12 col-md-6 mb-3">
          <label for="ket_id" class="form-label">Keterangan</label>
          <select class="form-control <?php if ($validation->hasError('ket_id')) : ?>is-invalid<?php endif ?>" id="ket_id" name="ket_id" required>
              <option value="">Pilih Keterangan</option>
              <?php foreach ($ket_list as $keterangan) : ?>
                <option value="<?= $keterangan['ket_id']; ?>" <?= (isset($supplier['ket_id']) && $supplier['ket_id'] == $keterangan['ket_id']) ? 'selected' : ''; ?>>
                <?= $keterangan['ket_jenis']; ?></option>
              <?php endforeach; ?>
          </select>
          <div class="invalid-feedback">
              <?= $validation->getError('ket_id'); ?>
          </div>
        </div>
        <div class="col-12 col-md-6 mb-3">
          <label for="kota_supplier" class="form-label">Kota</label>
          <input type="text" class="form-control <?php if ($validation->hasError('kota_supplier')) : ?>is-invalid<?php endif ?>" id="kota_supplier" name="kota_supplier" value="<?= esc($supplier['kota_supplier']); ?>" required>
          <div class="invalid-feedback">
            <?= $validation->getError('kota_supplier'); ?>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="mb-3">
          <label for="alamat_supplier" class="form-label">Alamat</label>
          <textarea class="form-control <?php if ($validation->hasError('alamat_supplier')) : ?>is-invalid<?php endif ?>" id="alamat_supplier" name="alamat_supplier" required><?= esc($supplier['alamat_supplier']); ?></textarea>
          <div class="invalid-feedback">
          <?= $validation->getError('alamat_supplier'); ?>
          </div>
        </div>
        <div class="col-12 col-md-6 mb-3">
          <label for="no_telp_supplier" class="form-label">Nomor Telepon</label>
          <input type="tel" class="form-control <?php if ($validation->hasError('no_telp_supplier')) : ?>is-invalid<?php endif ?>" id="no_telp_supplier" name="no_telp_supplier" value="<?= esc($supplier['no_telp_supplier']); ?>" required>
          <div class="invalid-feedback">
            <?= $validation->getError('no_telp_supplier'); ?>
          </div>
        </div>
        <div class="col-12 col-md-6 mb-3">
          <label for="no_hp_supplier" class="form-label">Nomor HP</label>
          <input type="tel" class="form-control <?php if ($validation->hasError('no_hp_supplier')) : ?>is-invalid<?php endif ?>" id="no_hp_supplier" name="no_hp_supplier" value="<?= esc($supplier['no_hp_supplier']); ?>" required>
          <div class="invalid-feedback">
            <?= $validation->getError('no_hp_supplier'); ?>
          </div>
        </div>
        <div class="col-12 col-md-6 mb-3">
          <label for="kontak_supplier" class="form-label">Kontak</label>
          <input type="text" class="form-control <?php if ($validation->hasError('kontak_supplier')) : ?>is-invalid<?php endif ?>" id="kontak_supplier" name="kontak_supplier" value="<?= esc($supplier['kontak_supplier']); ?>" required>
          <div class="invalid-feedback">
            <?= $validation->getError('kontak_supplier'); ?>
          </div>
        </div>
        <div class="col-12 col-md-6 mb-3">
          <label for="ntop_supplier" class="form-label">NTOP</label>
          <input type="text" class="form-control <?php if ($validation->hasError('ntop_supplier')) : ?>is-invalid<?php endif ?>" id="ntop_supplier" name="ntop_supplier" value="<?= esc($supplier['ntop_supplier']); ?>" required>
          <div class="invalid-feedback">
            <?= $validation->getError('ntop_supplier'); ?>
          </div>
        </div>
      </div>
      <button type="submit" class="btn btn-primary mt-2">Simpan Perubahan</button>
    </form>
  </div>
</div>
<?= $this->endSection() ?>