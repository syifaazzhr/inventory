<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Warna</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<a href="<?= base_url('admin/warna'); ?>" class="btn btn-outline-primary mb-3">
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

<h5 class="card-title fw-semibold mb-4 text-dark">Ubah Data Warna</h5>

<div class="card mb-4">
  <div class="card-body">
    <form action="<?= base_url("admin/warna/{$warna['warna_id']}/update"); ?>" method="post">
    <?= csrf_field(); ?>
    <input type="hidden" name="page" value="<?= esc($page) ?>">
      <div class="row">
        <div class="col-12 col-md-6 mb-3">
          <label for="kode_warna" class="form-label">Kode</label>
          <input type="text" class="form-control <?php if ($validation->hasError('kode_warna')) : ?>is-invalid<?php endif ?>" id="kode_warna" name="kode_warna" value="<?= old('kode_warna', $warna['kode_warna']); ?>" style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase();" required>
          <div class="invalid-feedback">
            <?= $validation->getError('kode_warna'); ?>
          </div>
        </div>
        <div class="col-12 col-md-6 mb-3">
          <label for="jenis_warna" class="form-label">Jenis</label>
          <input type="text" class="form-control <?php if ($validation->hasError('jenis_warna')) : ?>is-invalid<?php endif ?>" id="jenis_warna" name="jenis_warna" value="<?= old('jenis_warna', $warna['jenis_warna']); ?>" style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase();" required>
          <div class="invalid-feedback">
            <?= $validation->getError('jenis_warna'); ?>
          </div>
        </div>
      </div>
      <button type="submit" class="btn btn-primary mt-2">Simpan Perubahan</button>
    </form>
  </div>
</div>
<?= $this->endSection() ?>