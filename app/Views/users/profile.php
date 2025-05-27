<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Profil Saya</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php if (session()->getFlashdata('msg')) : ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <?= session()->getFlashdata('msg') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
  </div>
<?php endif; ?>

<div class="card">
  <div class="card-body">
    <h5 class="card-title fw-semibold mb-4">Profil Saya</h5>
    <table class="table">
      <tr>
        <th>Username</th>
        <td><?= $user->username ?></td>
      </tr>
      <tr>
        <th>Group</th>
        <td><?= implode(', ', $user->getGroups()) ?></td>
      </tr>
    </table>

    <div class="mt-3">
      <a href="<?= base_url("admin/users/{$user->id}/edit") ?>" class="btn btn-primary">
        <i class="ti ti-edit"></i> Ubah Data
      </a>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
