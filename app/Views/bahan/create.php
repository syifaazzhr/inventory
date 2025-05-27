<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Bahan</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<a href="<?= base_url('admin/bahan'); ?>" class="btn btn-outline-primary mb-3">
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

<h5 class="card-title fw-semibold mb-4 text-dark">Tambah Data Bahan</h5>

<div class="card mb-4">
  <div class="card-body">
    <form action="<?= base_url('admin/bahan/store'); ?>" method="post">
      <?= csrf_field(); ?>
      <div class="row"> 
      <div class="col-12 col-md-6 mb-3">
          <label for="grup_id" class="form-label">Grup</label>
          <select class="form-control <?php if ($validation->hasError('grup_id')) : ?>is-invalid<?php endif ?>" id="grup_id" name="grup_id" required>
              <option value="">Pilih Grup</option>
              <?php foreach ($grup_list as $grup) : ?>
                  <option value="<?= $grup['grup_id']; ?>" <?= ($oldInput['grup_id'] ?? '') == $grup['grup_id'] ? 'selected' : ''; ?>>
                      <?= esc($grup['nama_grup']); ?>
                  </option>
              <?php endforeach; ?>
          </select>
          <div class="invalid-feedback">
              <?= $validation->getError('grup_id'); ?>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12 col-md-6 mb-3">
          <label for="kode_bahan" class="form-label">Kode Bahan</label>
          <input type="text" class="form-control <?php if ($validation->hasError('kode_bahan')) : ?>is-invalid<?php endif ?>" id="kode_bahan" name="kode_bahan" value="<?= old('kode_bahan', esc($kode_bahan ?? '')); ?>" style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase();" required>
          <div class="invalid-feedback">
            <?= $validation->getError('nama_bahan'); ?>
          </div>
        </div>
        <div class="col-12 col-md-6 mb-3">
          <label for="nama_bahan" class="form-label">Nama Bahan</label>
          <input type="text" class="form-control <?php if ($validation->hasError('nama_bahan')) : ?>is-invalid<?php endif ?>" id="nama_bahan" name="nama_bahan" value="<?= $oldInput['nama_bahan'] ?? ''; ?>" style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase();" required>
          <div class="invalid-feedback">
            <?= $validation->getError('nama_bahan'); ?>
          </div>
        </div>
      </div>
      <button type="submit" class="btn btn-primary mt-2">Simpan</button>
    </form>
  </div>
</div>
<?= $this->endSection() ?>