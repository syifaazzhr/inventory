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
      <a href="<?= base_url('admin/penerimaan_pembelian'); ?>" class="btn btn-outline-primary">
        <i class="ti ti-arrow-left"></i>
        Kembali
      </a>
      <?php if (isset($penerimaan) && auth()->user()->inGroup('admin')) : ?>
      <div>
        <a href="<?= base_url("admin/penerimaan_pembelian/" . esc($penerimaan['terima_id']) . "/print"); ?>" class="btn btn-secondary">
          <i class="ti ti-printer"></i> Cetak
        </a>
        <a href="<?= base_url('admin/penerimaan_pembelian/' . esc($penerimaan['terima_id']) . '/edit'); ?>" class="btn btn-primary">
          <i class="ti ti-pencil"></i> Ubah
        </a>
        <button type="button" class="btn btn-danger" onclick="showDeleteModal('<?= base_url("admin/penerimaan_pembelian/{$penerimaan['terima_id']}/delete") ?>')">
          <i class="ti ti-trash"></i> Hapus
        </button>
      </div>
      <?php endif; ?>
    </div>

    <h5 class="card-title fw-semibold mb-4">Detail Penerimaan Pembelian</h5>

    <div class="card mb-4">
      <div class="card-body">
        <h6 class="card-title fw-semibold mb-3">Informasi Penerimaan</h6>
        <div class="table-responsive">
          <table class="table table-hover table-striped">
            <tbody class="table-group-divider">
              <tr>
                <td><b>Nomor Bon</b></td>
                <td><?= esc($penerimaan['nomor_bon'] ?? '-'); ?></td>
              </tr>
              <tr>
                <td><b>Tanggal Terima</b></td>
                <td>
                  <?= isset($penerimaan['tgl_terima']) && $penerimaan['tgl_terima'] !== null
                      ? date('d-m-Y', strtotime($penerimaan['tgl_terima']))
                      : '-' ?>
                </td>
              </tr>
              <tr>
                <td><b>Supplier</b></td>
                <td><?= esc($supplier['kode_supplier'] ?? '-') . ' - ' . esc($supplier['nama_supplier'] ?? '-'); ?></td>
              </tr>
              <tr>
                <td><b>Nomor PO</b></td>
                <td><?= esc($purchase_order['nomor_po'] ?? '-'); ?></td>
              </tr>
              <tr>
                <td><b>Nomor Faktur</b></td>
                <td><?= esc($penerimaan['nomor_faktur'] ?? '-'); ?></td>
              </tr>
              <tr>
                <td><b>Surat Jalan</b></td>
                <td><?= esc($penerimaan['surat_jalan'] ?? '-'); ?></td>
              </tr>
              <tr>
                <td><b>Tanggal Kirim</b></td>
                <td>
                  <?= isset($penerimaan['tgl_kirim']) && $penerimaan['tgl_kirim'] !== null
                      ? date('d-m-Y', strtotime($penerimaan['tgl_kirim']))
                      : '-' ?>
                </td>
              </tr>
              <tr>
                <td><b>Tanggal Jatuh Tempo</b></td>
                <td>
                  <?= isset($penerimaan['tgl_jatuh_tempo']) && $penerimaan['tgl_jatuh_tempo'] !== null
                      ? date('d-m-Y', strtotime($penerimaan['tgl_jatuh_tempo']))
                      : '-' ?>
                </td>
              </tr> 
              <tr>
                <td><b>Total Kuantitas</b></td>
                <td><?= number_format($penerimaan['total_qty_terima'] ?? 0, 2, '.', ','); ?></td>
              </tr>
              <tr>
                <td><b>Total Beli</b></td>
                <td><?= number_format($penerimaan['total_beli_terima'] ?? 0, 2, '.', ','); ?></td>
              </tr>
              <tr>
                <td><b>PPN</b></td>
                <td><?= number_format($penerimaan['ppn_terima'] ?? 0, 2, '.', ','); ?></td>
              </tr>
              <tr>
                <td><b>Jumlah Total</b></td>
                <td><?= number_format($penerimaan['total_beli_terima_ppn'] ?? 0, 2, '.', ','); ?></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        <h6 class="card-title fw-semibold mb-3">Detail Barang yang Diterima</h6>
        <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
          <table class="table table-hover table-striped">
            <thead class="table-light">
              <tr>
                <th scope="col" style="white-space: nowrap;">Kode Stok</th>
                <th scope="col" style="white-space: nowrap;">Nama Barang</th>
                <th scope="col" style="white-space: nowrap;">Satuan</th>
                <th scope="col" style="white-space: nowrap;">Harga</th>
                <th scope="col" style="white-space: nowrap;">Kuantitas yang Diterima</th>
                <th scope="col" style="white-space: nowrap;">Harga Beli per Barang</th>
                <th scope="col" style="white-space: nowrap;">Aksi</th>
              </tr>
            </thead>
            <tbody class="table-group-divider">
              <?php if (!empty($detail_penerimaan)) : ?>
                <?php foreach ($detail_penerimaan as $detail) : ?>
                  <tr>
                    <td style="white-space: nowrap;"><?= esc($detail['kode_stok'] ?? '-'); ?></td>
                    <td style="white-space: nowrap;"><?= esc($detail['nama_barang'] ?? '-'); ?></td>
                    <td style="white-space: nowrap;"><?= esc($detail['jenis_satuan'] ?? '-'); ?></td>
                    <td style="white-space: nowrap;"><?= number_format($detail['harga_beli'] ?? 0, 2, '.', ','); ?></td>
                    <td style="white-space: nowrap;"><?= number_format($detail['qty_det_terima'] ?? 0, 2, '.', ','); ?></td>
                    <td style="white-space: nowrap;"><?= number_format($detail['jumlah_det_terima'] ?? 0, 2, '.', ','); ?></td>
                    <td style="white-space: nowrap;">
                      <a href="<?= base_url('admin/penerimaan_pembelian/lot_rol/' . esc($detail['terima_id']) . '/' . esc($detail['det_terima_id'])); ?>" class="btn btn-info">
                        <i class="ti ti-list"></i> Lot & Rol
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else : ?>
                <tr>
                  <td colspan="5" class="text-center"><b>Tidak ada data barang dalam penerimaan ini.</b></td>
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