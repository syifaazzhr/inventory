<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Status Purchase Order</title>
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
        <h5 class="card-title fw-semibold mb-4">Status Purchase Order</h5>
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
    <div class="col-12 mb-3">
      <div class="d-flex gap-2">
          <p class="my-auto">Filter:</p>
          <div>
              <a href="<?= base_url('admin/dashboard/status_po' . ($statusFilter === 'Belum Diterima' ? '' : '?status=Belum Diterima')); ?>" 
                class="btn btn<?= ($statusFilter === 'Belum Diterima') ? '' : '-outline'; ?>-warning py-2">
                  <?php if ($statusFilter === 'Belum Diterima') : ?>
                      <i class="ti ti-check"></i>
                  <?php endif; ?>
                  Belum Diterima
              </a>
          </div>
          <div>
              <a href="<?= base_url('admin/dashboard/status_po' . ($statusFilter === 'Sudah Diterima' ? '' : '?status=Sudah Diterima')); ?>" 
                class="btn btn<?= ($statusFilter === 'Sudah Diterima') ? '' : '-outline'; ?>-success py-2">
                  <?php if ($statusFilter === 'Sudah Diterima') : ?>
                      <i class="ti ti-check"></i>
                  <?php endif; ?>
                  Sudah Diterima
              </a>
          </div>
      </div>
    </div>

    <div class="table-responsive">
    <table class="table table-hover table-striped">
        <thead class="table-light">
        <tr>
            <th>Nomor PO</th>
            <th>Tanggal Kirim</th>
            <th>Status</th>
            <th class="text-center">Aksi</th>
        </tr>
        </thead>
        <tbody class="table-group-divider">
                <?php if (empty($purchase_orders)) : ?>
                    <tr class="table-light">
                        <td class="text-center" colspan="4"><span class="text-body"><b>Tidak ada data</b></span></td>
                    </tr>
                <?php else : ?>
                <?php foreach ($purchase_orders as $po) : ?>
                <tr class="table-light">
                    <td>
                        <span class="text-body"><?= esc($po['nomor_po']) ?></span>
                        <p class="text-body small mt-3 mb-0" style="font-size: 0.75rem;">
                            <em><?= "Jumlah: {$po['jumlah']}"; ?></em>
                        </p>
                    </td>
                    <td>
                        <span class="text-body"><?= date('d-m-Y', strtotime($po['max_tgl_kirim'])) ?></span>
                        <?php 
                            $status = isset($po['status_po']) ? $po['status_po'] : 'Belum Diketahui'; // <<< pindahkan ke atas
                            $today = new DateTime();
                            $dueDate = new DateTime($po['max_tgl_kirim']);
                            if ($status !== 'Sudah Diterima' && $today > $dueDate) {
                                $diffDays = $today->diff($dueDate)->days; 
                                if ($diffDays > 0) {
                                    echo '<p class="text-danger small mt-3 mb-0" style="font-size: 0.75rem;"><em>Terlambat: ' . $diffDays . ' Hari</em></p>';
                                }
                            }
                        ?>
                    </td>
                    <td>
                        <?php
                            $status = isset($po['status_po']) ? $po['status_po'] : 'Belum Diketahui';
                            $badgeClass = ($status === 'Sudah Diterima') ? 'bg-success' : 'bg-warning';
                        ?>
                        <span class="badge <?= $badgeClass; ?>"><?= $status; ?></span>
                    </td>
                    <td class="text-center">
                        <a href="<?= base_url('admin/purchase_order/' . $po['po_id'] . '?from=status'); ?>" class="btn btn-info">
                          <i class="ti ti-eye"></i> Detail
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    </div>
    <div class="d-flex justify-content-center mt-3">
      <?= $pager->links('default', 'custom_pager') ?>
    </div>
  </div>
</div>

<?= $this->endSection() ?>