<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Grup</title>
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
    <div class="row mb-2">
      <div class="col-12 col-lg-5">
        <h5 class="card-title fw-semibold mb-4">Data Grup</h5>
      </div>
      <div class="col-12 col-lg-7">
        <div class="d-flex gap-2 justify-content-md-end">
          <div>
          <form action="<?= base_url('admin/grup'); ?>" method="get">
              <div class="input-group mb-3">
                  <input type="text" class="form-control" name="search" value="<?= esc($search ?? ''); ?>" aria-label="Cari grup">
                  <button class="btn btn-outline-secondary" type="submit">Cari</button>
              </div>
          </form>
          </div>
          <?php if (auth()->user()->inGroup('admin')) : ?>
          <div>
            <a href="<?= base_url('admin/grup/create'); ?>" class="btn btn-primary py-2">
              <i class="ti ti-plus"></i>
              Tambah
            </a>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <div class="table-responsive">
      <table class="table table-hover table-striped">
        <thead class="table-light">
          <tr>
            <th scope="col">Kode Grup</th>
            <th scope="col">Nama Grup</th>
            <?php if (auth()->user()->inGroup('admin')) : ?>
              <th scope="col" class="text-center">Aksi</th>
            <?php endif; ?>
          </tr>
        </thead>
        <tbody class="table-group-divider">
          <?php $i = 1 + ($pager->getCurrentPage('default') - 1) * 10; ?>
          <?php if (empty($grup)) : ?>
            <tr>
              <td class="text-center" colspan="3"><b>Tidak ada data</b></td>
            </tr>
          <?php endif; ?>
          <?php foreach ($grup as $row) : ?> 
            <tr>
              <td><?= esc($row['kode_grup']); ?></td>
              <td><?= esc($row['nama_grup']); ?></td>
              <?php if (auth()->user()->inGroup('admin')) : ?>
              <td class="text-center">
                <div class="d-flex justify-content-center gap-2">
                  <a href="<?= base_url("admin/grup/{$row['grup_id']}/edit?page=" . $pager->getCurrentPage()); ?>" class="btn btn-primary">
                    <i class="ti ti-edit"></i> Ubah
                  </a>
                  <form action="<?= base_url("admin/grup/{$row['grup_id']}"); ?>" method="post" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                    <?= csrf_field(); ?>
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="button" class="btn btn-danger" onclick="showDeleteModal('<?= base_url("admin/grup/{$row['grup_id']}") ?>')">
                      <i class="ti ti-trash"></i> Hapus
                    </button>
                  </form>
                </div>
              </td>
              <?php endif; ?>
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

<!-- Modal Konfirmasi Hapus -->
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