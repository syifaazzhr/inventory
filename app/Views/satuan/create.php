<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Satuan</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<a href="<?= base_url('admin/satuan'); ?>" class="btn btn-outline-primary mb-3">
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

<h5 class="card-title fw-semibold mb-4 text-dark">Tambah Data Satuan</h5>

<div class="card mb-4">
  <div class="card-body">
    <form action="<?= base_url('admin/satuan/store'); ?>" method="post">
      <?= csrf_field(); ?>

      <div class="row">
        <div class="col-12 col-md-6 mb-3">
          <label for="kode_satuan" class="form-label">Kode</label>
          <input type="text" class="form-control <?php if ($validation->hasError('kode_satuan')) : ?>is-invalid<?php endif ?>" id="kode_satuan" name="kode_satuan" value="<?= $oldInput['kode_satuan'] ?? ''; ?>" style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase();" required>
          <div class="invalid-feedback">
            <?= $validation->getError('kode_satuan'); ?>
          </div>
        </div>
        <div class="col-12 col-md-6 mb-3">
          <label for="jenis_satuan" class="form-label">Jenis</label>
          <input type="text" class="form-control <?php if ($validation->hasError('jenis_satuan')) : ?>is-invalid<?php endif ?>" id="jenis_satuan" name="jenis_satuan" value="<?= $oldInput['jenis_satuan'] ?? ''; ?>" style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase();" required>
          <div class="invalid-feedback">
            <?= $validation->getError('jenis_satuan'); ?>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-12 col-md-6 mb-3">
          <label for="isi_satuan" class="form-label">Isi</label>
          <input type="number" class="form-control <?php if ($validation->hasError('isi_satuan')) : ?>is-invalid<?php endif ?>" id="isi_satuan" name="isi_satuan" value="<?= $oldInput['isi_satuan'] ?? ''; ?>" style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase();" required>
          <div class="invalid-feedback">
            <?= $validation->getError('isi_satuan'); ?>
          </div>
        </div>
        <div class="col-12 col-md-6 mb-3">
          <label for="unit_id" class="form-label">Unit</label>
          <select class="form-control <?php if ($validation->hasError('unit_id')) : ?>is-invalid<?php endif ?>" id="unit_id" name="unit_id" required>
            <option value="">Pilih Unit Satuan</option>
            <?php foreach ($unitSatuan as $unit) : ?>
              <option value="<?= $unit['unit_id']; ?>" <?= ($oldInput['unit_id'] ?? '') == $unit['unit_id'] ? 'selected' : ''; ?>>
                <?= $unit['jenis_unit']; ?>
              </option>
            <?php endforeach; ?>
          </select>
          <div class="invalid-feedback">
            <?= $validation->getError('unit_id'); ?>
          </div>
        </div>
      </div>

      <button type="submit" class="btn btn-primary mt-2">Simpan</button>
    </form>
  </div>
</div>
<?= $this->endSection() ?>