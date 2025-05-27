<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Tambah Grup</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<a href="<?= base_url('admin/ketSupplier'); ?>" class="btn btn-outline-primary mb-3">
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

<h5 class="card-title fw-semibold mb-4 text-dark">Tambah Data Keterangan Supplier</h5>

<div class="card mb-4">
  <div class="card-body">
  <form action="<?= base_url('admin/ketSupplier/store'); ?>" method="post">
   <?= csrf_field(); ?>
   <div class="row">
       <div class="col-12 col-md-6 mb-3">
           <label for="ket_jenis" class="form-label">Keterangan Supplier</label>
           <input type="text" class="form-control <?php if ($validation->hasError('ket_jenis')) : ?>is-invalid<?php endif ?>" id="ket_jenis" name="ket_jenis" value="<?= old('ket_jenis'); ?>" style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase();" required>
           <div class="invalid-feedback">
               <?= $validation->getError('ket_jenis'); ?>
           </div>
       </div>
   </div>
   <button type="submit" class="btn btn-primary mt-2">Simpan</button>
</form>
  </div>
</div>
<?= $this->endSection() ?>