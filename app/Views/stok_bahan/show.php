<?= $this->extend('layouts/admin_layout') ?>
<?= $this->section('head') ?>
<title>Stok Bahan</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card">
  <div class="card-body">
    <div class="d-flex justify-content-between mb-4">
      <a href="<?= base_url('admin/stok_bahan'); ?>" class="btn btn-outline-primary">
        <i class="ti ti-arrow-left"></i> Kembali
      </a>
      <div class="d-flex justify-content-end align-items-center gap-2 flex-nowrap">
        <form action="<?= base_url('admin/stok_bahan/show'); ?>" method="get" class="d-flex" style="height: 38px;">
          <div class="input-group" style="min-width: 200px;">
            <input type="text" class="form-control" name="search" value="<?= esc($search ?? ''); ?>">
            <button class="btn btn-outline-secondary" type="submit">Cari</button>
          </div>
        </form>
        <?php if (auth()->user()->inGroup('admin')) : ?>
        <div>
          <a href="<?= base_url('admin/stok_bahan/download_detail_excel'); ?>"
              class="btn d-inline-flex align-items-center px-3"
              style="background-color: #1d8e44; color: white; height: 38px; white-space: nowrap;">
              <i class="ti ti-download me-1"></i> Simpan ke Excel
          </a>
        </div>
        <?php endif; ?>
      </div>
    </div>
    <h5 class="card-title fw-semibold mb-4">Detail Semua Stok Bahan</h5>
    <div class="table-responsive">
  <table class="table table-hover table-striped" style="table-layout: auto; width: max-content;">
        <thead class="table-light">
          <tr>
            <th scope="col">Kode Barang</th>
            <th scope="col">Nama Barang</th> 
            <th scope="col">Nomor PO</th>
            <th scope="col">Tanggal Terima</th>
            <th scope="col">Lot</th>
            <th scope="col">Rol</th>
            <th scope="col">Harga</th>
            <th scope="col">Satuan</th>
            <th scope="col">Beli</th>
            <th scope="col">Retur</th>
            <th scope="col">Pakai</th>
            <th scope="col">Sisa</th>
          </tr>
        </thead>
        <tbody class="table-group-divider">
          <?php if (empty($stok_bahan)) : ?>
            <tr>
              <td colspan="13" class="text-center"><b>Tidak ada data</b></td>
            </tr>
          <?php else : ?>
            <?php foreach ($stok_bahan as $row): ?>
              <?php
                $beli = (float) ($row['beli'] ?? 0);
                $retur = (float) ($row['retur'] ?? 0);
                $pakai = (float) ($row['pakai'] ?? 0);
                $sisa = $beli - $retur - $pakai;
              ?>
              <tr> 
                <td><?= esc($row['kode_stok']) ?></td>
                <td><?= esc($row['nama_barang']) ?></td>
                <td><?= esc($row['nomor_po'] ?? '-') ?></td>
                <td>
                  <?= isset($row['tgl_terima']) && $row['tgl_terima'] !== null
                      ? date('d-m-Y', strtotime($row['tgl_terima']))
                      : '-' ?>
                </td>
                <td><?= esc($row['lot_terima'] ?? '-') ?></td>
                <td><?= esc($row['rol_terima'] ?? '-') ?></td>
                <td><?= number_format($row['harga_beli'], 2, '.', ',') ?></td>
                <td><?= esc($row['jenis_satuan']) ?></td>
                <td><?= $beli == 0 ? '-' : number_format($beli, 2, '.', ',') ?></td>
                <td><?= $retur == 0 ? '-' : number_format($retur, 2, '.', ',') ?></td>
                <td><?= $pakai == 0 ? '-' : number_format($pakai, 2, '.', ',') ?></td>
                <td><?= $sisa == 0 ? '-' : number_format($sisa, 2, '.', ',') ?></td>
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

<script>
    document.querySelector('input[name="search"]').addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });
</script>

<?= $this->endSection() ?>