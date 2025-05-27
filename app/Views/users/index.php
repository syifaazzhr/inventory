<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Data Pengguna</title>
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

<div class="card">
  <div class="card-body">
    <div class="d-flex justify-content-between mb-2">
      <h5 class="card-title fw-semibold mb-4">Data Pengguna</h5>
      <div>
        <a href="<?= base_url('admin/users/new'); ?>" class="btn btn-primary">
          <i class="ti ti-plus"></i>
          Tambah Pengguna Baru
        </a>
      </div>
    </div>
    <div class="overflow-x-scroll">
      <table class="table table-hover table-striped">
        <thead class="table-light">
          <tr>
            <th scope="col">Nama Pengguna</th>
            <th scope="col" class="text-center">Grup</th>
            <th scope="col" class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody class="table-group-divider">
        <?php $i = 1 + ($itemPerPage * ($currentPage - 1)) ?>
        <?php foreach ($users as $user) : ?>
          <?php
          $userAttributes = $user->toArray();
          $userIdentities = !empty($user->identities) ? $user->identities[0]->toArray() : null;
          $userGroup = !empty($user->getGroups()) ? $user->getGroups()[0] : null;
          ?>
          <tr>
            <td>
             <b><?= $userAttributes['username']; ?></b>
            </td>
            <td class="text-center">
                <?php if ($userGroup === 'admin') : ?>
                  <span class="badge bg-success rounded-3 fw-semibold text-black"><?= $userGroup; ?></span>
                <?php elseif ($userGroup === 'user') : ?>
                  <span class="badge bg-primary rounded-3 fw-semibold"><?= $userGroup; ?></span>
                <?php else : ?>
                  <span class="badge bg-black rounded-3 fw-semibold"><?= $userGroup; ?></span>
                <?php endif; ?>
              </td>
            <td>
                <div class="d-flex justify-content-center gap-2">
                  <a href="<?= base_url("admin/users/{$userAttributes['id']}/edit"); ?>" class="btn btn-primary mb-2">
                    <i class="ti ti-edit"></i>
                    Ubah
                  </a>
                  <form action="<?= base_url("admin/users/{$userAttributes['id']}"); ?>" method="post">
                    <?= csrf_field(); ?>
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?');">
                      <i class="ti ti-trash"></i>
                      Hapus
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <div class="d-flex justify-content-center mt-3">
      <?= $pager->links('default', 'custom_pager') ?>
    </div>
  </div>
</div> 
<?= $this->endSection() ?>