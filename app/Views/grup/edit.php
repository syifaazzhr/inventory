<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Grup</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<a href="<?= base_url('admin/grup'); ?>" class="btn btn-outline-primary mb-3">
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

<h5 class="card-title fw-semibold mb-4 text-dark">Ubah Data Grup</h5>

<div class="card mb-4">
  <div class="card-body">
    <form action="<?= base_url("admin/grup/{$grup['grup_id']}/update"); ?>" method="post">
      <?= csrf_field(); ?>
      <input type="hidden" name="page" value="<?= esc($page) ?>">
      <div class="row">
        <div class="col-12 col-md-6 mb-3">
          <label for="kode_grup" class="form-label">Kode Grup</label>
          <input type="text" class="form-control <?php if ($validation->hasError('kode_grup')) : ?>is-invalid<?php endif ?>" id="kode_grup" name="kode_grup" value="<?= old('kode_grup', $grup['kode_grup']); ?>" style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase();" required>
          <div class="invalid-feedback">
            <?= $validation->getError('kode_grup'); ?>
          </div>
        </div>
        <div class="col-12 col-md-6 mb-3">
          <label for="nama_grup" class="form-label">Nama Grup</label>
          <input type="text" class="form-control <?php if ($validation->hasError('nama_grup')) : ?>is-invalid<?php endif ?>" id="nama_grup" name="nama_grup" value="<?= old('nama_grup', $grup['nama_grup']); ?>" style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase();" required>
          <div class="invalid-feedback">
            <?= $validation->getError('nama_grup'); ?>
          </div>
        </div>
      </div>
      <button type="submit" class="btn btn-primary mt-2">Simpan</button>
    </form>
  </div>
</div>
<?= $this->endSection() ?>