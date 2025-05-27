<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Purchase Order</title>
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
      <?php
      $from = service('request')->getGet('from');
      $backUrl = base_url('admin/purchase_order?from=dashboard'); // Default ke Index PO

      if ($from === 'status') {
          $backUrl = base_url('admin/dashboard/status_po');
      }
      ?>

      <a href="<?= $backUrl; ?>" class="btn btn-outline-primary">
          <i class="ti ti-arrow-left"></i> Kembali
      </a>
      <?php if (isset($purchase_order) && auth()->user()->inGroup('admin')) : ?>
      <div>
        <a href="<?= base_url("admin/purchase_order/" . esc($purchase_order['po_id']) . "/print"); ?>" class="btn btn-secondary">
          <i class="ti ti-printer"></i> Cetak
        </a>
        <a href="<?= base_url('admin/purchase_order/' . esc($purchase_order['po_id']) . '/edit'); ?>" class="btn btn-primary">
          <i class="ti ti-pencil"></i> Ubah
        </a>
        <button type="button" class="btn btn-danger" onclick="showDeleteModal('<?= base_url("admin/purchase_order/{$purchase_order['po_id']}/delete") ?>')">
          <i class="ti ti-trash"></i> Hapus
        </button>
      </div>
      <?php endif; ?>
    </div>

    <h5 class="card-title fw-semibold mb-4">Detail Purchase Order</h5>

    <!-- Informasi Purchase Order -->
    <div class="card mb-4">
      <div class="card-body">
        <h6 class="card-title fw-semibold mb-3">Informasi Purchase Order</h6>
        <div class="table-responsive">
          <table class="table table-hover table-striped">
            <tbody class="table-group-divider">
              <tr>
                <td><b>Nomor PO</b></td>
                <td><?= esc($purchase_order['nomor_po'] ?? '-'); ?></td>
              </tr>
              <tr>
                <td><b>Tanggal Order</b></td>
                <td>
                  <?= isset($purchase_order['tgl_order']) && $purchase_order['tgl_order'] !== null
                      ? date('d-m-Y', strtotime($purchase_order['tgl_order']))
                      : '-' ?>
                </td>
              </tr>
              <tr>
                <td><b>Tanggal Kirim</b></td>
                <td>
                  <?= isset($purchase_order['max_tgl_kirim']) && $purchase_order['max_tgl_kirim'] !== null
                      ? date('d-m-Y', strtotime($purchase_order['max_tgl_kirim']))
                      : '-' ?>
                </td>
              </tr>
              <tr>
                <td><b>Nomor Worksheet</b></td>
                <td><?= esc($purchase_order['nomor_ws'] ?? '-'); ?></td>
              </tr>
              <tr>
                <td><b>Nomor Artikel</b></td>
                <td><?= esc($purchase_order['nomor_art'] ?? '-'); ?></td>
              <tr>
                <td><b>Supplier</b></td>
                <td><?= esc($supplier['kode_supplier'] ?? '-') . ' - ' . esc($supplier['nama_supplier'] ?? '-'); ?></td>
              </tr>
              <tr>
                <td><b>Keterangan</b></td>
                <td><?= esc($purchase_order['ket_po'] ?? '-'); ?></td>
              </tr>
              <tr>
                <td><b>Total Kuantitas</b></td>
                <td><?= number_format($purchase_order['total_qty'] ?? 0, 2, '.', ','); ?></td>
              </tr>
              <tr>
                <td><b>Total Beli</b></td>
                <td><?= number_format($purchase_order['jml_total_po'] ?? 0, 2, '.', ','); ?></td>
              </tr>
              <tr>
                <td><b>PPN</b></td>
                <td><?= number_format($purchase_order['ppn_po'] ?? 0, 2, '.', ','); ?></td>
              </tr>
              <tr>
                <td><b>Jumlah Total</b></td>
                <td><?= number_format($purchase_order['jml_total_po_ppn'] ?? 0, 2, '.', ','); ?></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        <h6 class="card-title fw-semibold mb-3">Detail Barang dalam Purchase Order</h6>
          <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
            <table class="table table-hover table-striped" style="width: auto; table-layout: auto;">
              <thead class="table-light">
                <tr>
                  <th scope="col" style="white-space: nowrap;">Kode Stok</th>
                  <th scope="col" style="white-space: nowrap;">Nama Barang</th>
                  <th scope="col" style="white-space: nowrap;">Satuan</th>
                  <th scope="col" style="white-space: nowrap;">Harga</th>
                  <th scope="col" style="white-space: nowrap;">Kuantitas per Barang</th>
                  <th scope="col" style="white-space: nowrap;">Harga Beli per Barang</th>
                </tr> 
              </thead>
              <tbody class="table-group-divider">
                <?php if (!empty($detail_po)) : ?>
                  <?php foreach ($detail_po as $detail) : ?>
                    <tr>
                      <td style="white-space: nowrap;"><?= esc($detail['kode_stok'] ?? '-'); ?></td>
                      <td style="white-space: nowrap;"><?= esc($detail['nama_barang'] ?? '-'); ?></td>
                      <td style="white-space: nowrap;"><?= esc($detail['jenis_satuan'] ?? '-'); ?></td>
                      <td style="white-space: nowrap;"><?= number_format($detail['harga_beli'] ?? 0, 2, '.', ','); ?></td>
                      <td style="white-space: nowrap;"><?= number_format($detail['qty_detpo'] ?? 0, 2, '.', ','); ?></td>
                      <td style="white-space: nowrap;"><?= number_format($detail['jumlah_detpo'] ?? 0, 2, '.', ','); ?></td>
                    </tr>
                  <?php endforeach; ?>
                <?php else : ?>
                  <tr>
                    <td colspan="4" class="text-center"><b>Tidak ada data barang dalam Purchase Order ini.</b></td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
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