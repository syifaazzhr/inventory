<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Penerimaan Pembelian</title>
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
    <div class="d-flex justify-content-between mb-4">
    <a href="<?= base_url('admin/penerimaan_pembelian/' . esc($penerimaan['terima_id'])); ?>" class="btn btn-outline-primary">
      <i class="ti ti-arrow-left"></i>
      Kembali
    </a>
  </div>
    <h5 class="card-title fw-semibold mb-4">Detail Lot & Rol</h5>
    <div class="card mb-4">
      <div class="card-body">
        <h6 class="card-title fw-semibold mb-3">Informasi Lot & Rol</h6>
        <div class="table-responsive">
          <table class="table table-hover table-striped">
            <tbody class="table-group-divider">
              <tr>
                <td><b>Nomor Bon</b></td>
                <td><?= esc($penerimaan['nomor_bon'] ?? '-'); ?></td>
              </tr>
              <tr>
                <td><b>Nomor PO</b></td>
                <td><?= esc($purchase_order['nomor_po'] ?? '-'); ?></td>
              </tr>
              <tr>
                <td><b>Kode Stok</b></td>
                <td><?= esc($stok_detail['kode_stok'] ?? '-'); ?></td>
              </tr>
              <tr>
                <td><b>Nama Barang</b></td>
                <td><?= esc($stok_detail['nama_barang'] ?? '-'); ?></td>
              </tr>
              <tr>
                <td><b>Total Kuantitas</b></td>
                <td><?= number_format($detail_penerimaan['qty_det_terima'] ?? 0, 2, '.', ','); ?></td>
              </tr>
              <!-- <?php if (!empty($lotrol_penerimaan)) : ?>
                <tr>
                  <td><b>Lot</b></td>
                  <td><?= esc($lotrol_penerimaan[0]['lot_terima']); ?></td>
                </tr>
              <?php endif; ?> -->
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Detail Rol -->
    <div class="card mb-4">
      <div class="card-body">
        <div class="d-flex justify-content-between mb-3">
          <h6 class="card-title fw-semibold mb-0">Detail Rol</h6>
          <?php if (auth()->user()->inGroup('admin')) : ?>
              <?php if (!empty($lotrol_penerimaan)) : ?>
                  <a href="<?= base_url('admin/penerimaan_pembelian/lot_rol/' . esc($lotrol_penerimaan[0]['lotrol_terima_id']) . '/edit'); ?>" class="btn btn-primary">
                      <i class="ti ti-pencil"></i> Ubah
                  </a>
              <?php else : ?>
                  <a href="<?= base_url('admin/penerimaan_pembelian/lot_rol/create?terima_id=' . esc($terima_id) . '&det_terima_id=' . esc($det_terima_id)); ?>" class="btn btn-primary">
                      <i class="ti ti-plus"></i> Tambah
                  </a>
              <?php endif; ?>
          <?php endif; ?>
        </div>

        <div class="table-responsive">
          <table class="table table-hover table-striped">
            <thead class="table-light">
              <tr>
                <th scope="col" style="white-space: nowrap;">Lot</th>
                <th scope="col" style="white-space: nowrap;">Rol</th>
                <th scope="col" style="white-space: nowrap;">Kuantitas per Rol</th>
              </tr>
            </thead>
            <tbody class="table-group-divider">
              <?php if (!empty($lotrol_penerimaan)) : ?>
                <?php foreach ($lotrol_penerimaan as $detail) : ?>
                  <tr>
                    <td style="white-space: nowrap;"><?= esc($detail['lot_terima'] ?? '-'); ?></td>
                    <td style="white-space: nowrap;"><?= esc($detail['rol_terima'] ?? '-'); ?></td>
                    <td style="white-space: nowrap;"><?= number_format($detail['qty_lotrol_terima'] ?? 0, 2, '.', ','); ?></td>
                  </tr>
                <?php endforeach; ?>
              <?php else : ?>
                <tr>
                  <td colspan="3" class="text-center"><b>Tidak ada detail rol untuk data ini.</b></td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
 
  </div>
</div>
<?= $this->endSection() ?>