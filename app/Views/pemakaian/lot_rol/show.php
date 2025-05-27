<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Pemakaian Bahan</title>
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
      <a href="<?= base_url('admin/pemakaian/' . esc($pemakaian['pakai_id'])); ?>" class="btn btn-outline-primary">
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
                <td><b>Nomor Pemakaian</b></td>
                <td><?= esc($pemakaian['nomor_pakai'] ?? '-'); ?></td>
              </tr>
              <tr>
                <td><b>Kode Stok</b></td>
                <td><?= esc($detail_pemakaian['kode_stok'] ?? '-'); ?></td>
              </tr>
             <tr>
                <td><b>Nama Barang</b></td>
                <td><?= esc($detail_pemakaian['nama_barang'] ?? '-'); ?></td>
              </tr>
              <tr>
                <td><b>Total Kuantitas</b></td>
                <td><?= number_format($detail_pemakaian['qty_det_pakai'] ?? 0, 2, '.', ','); ?></td>
              </tr>
              <tr>
                <td><b>Total Pemakaian</b></td>
                <td>
                  <?php
                    $total_pakai_qty = 0;
                    foreach ($lotrol_pemakaian ?? [] as $pemakaianItem) {
                        $total_pakai_qty += (float) $pemakaianItem['qty_lotrol_pakai'];
                    }
                    echo number_format($total_pakai_qty, 2, '.', ',');
                  ?>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
 
    <div class="card mb-4">
      <div class="card-body">
        <div class="d-flex justify-content-between mb-3">
          <h6 class="card-title fw-semibold mb-0">Detail Rol</h6>
          <?php if (auth()->user()->inGroup('admin')): ?>
          <a href="<?= base_url('admin/pemakaian/lot_rol/edit_by_pemakaian/' . esc($pemakaian['pakai_id']) . '/' . esc($det_pakai_id)); ?>" class="btn btn-primary">
            <i class="ti ti-pencil"></i> Ubah
          </a>
          <?php endif; ?>
        </div>
        <div class="table-responsive">
          <table class="table table-hover table-striped">
            <thead class="table-light">
              <tr> 
                <th scope="col" style="white-space: nowrap;">Nomor PO</th>
                <th scope="col" style="white-space: nowrap;">Lot</th>
                <th scope="col" style="white-space: nowrap;">Rol</th>
                <?php if (!empty($lotrol_pemakaian)) : ?>
                  <th scope="col" style="white-space: nowrap;">Pakai</th>
                <?php else: ?>
                  <th scope="col" style="white-space: nowrap;">Kuantitas</th>
                <?php endif; ?>
              </tr>
            </thead>
            <tbody class="table-group-divider">
              <?php if (!empty($lotrol_pemakaian)): ?>
                <?php foreach ($lotrol_pemakaian as $pemakaianItem): ?>
                  <?php
                    $lot = $pemakaianItem['lot_terima_asal'];
                    $rol = $pemakaianItem['rol_terima_asal'];
                    $qty = (float) $pemakaianItem['qty_lotrol_pakai'];

                    $nomor_po = '-';
                    foreach ($lotrol_penerimaan as $lotrol) {
                        if ($lotrol['lot_asal'] === $lot && $lotrol['rol_asal'] === $rol) {
                            $nomor_po = $lotrol['nomor_po'] ?? '-';
                            break;
                        }
                    } 

                    if ($nomor_po === '-') {
                        $data = \Config\Database::connect()
                            ->table('lotrol_penerimaan')
                            ->join('detail_penerimaan', 'detail_penerimaan.det_terima_id = lotrol_penerimaan.det_terima_id')
                            ->join('penerimaan_pembelian', 'penerimaan_pembelian.terima_id = detail_penerimaan.terima_id')
                            ->join('purchase_order', 'purchase_order.po_id = penerimaan_pembelian.po_id')
                            ->select('purchase_order.nomor_po')
                            ->where('lotrol_penerimaan.lot_terima', $lot)
                            ->where('lotrol_penerimaan.rol_terima', $rol)
                            ->get()
                            ->getRowArray();

                        $nomor_po = $data['nomor_po'] ?? '-'; 
                    }
                  ?>
                  <tr>
                    <td style="white-space: nowrap;"><?= esc($nomor_po); ?></td>
                    <td style="white-space: nowrap;"><?= esc($lot); ?></td>
                    <td style="white-space: nowrap;"><?= esc($rol); ?></td>
                    <td style="white-space: nowrap;"><?= number_format($qty, 2, '.', ','); ?></td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <?php foreach ($lotrol_penerimaan as $lotrol): ?>
                    <tr>
                        <td style="white-space: nowrap;"><?= esc($lotrol['nomor_po'] ?? '-'); ?></td>
                        <td style="white-space: nowrap;"><?= esc($lotrol['lot_asal']); ?></td>
                        <td style="white-space: nowrap;"><?= esc($lotrol['rol_asal']); ?></td>
                        <td style="white-space: nowrap;"><?= number_format($lotrol['sisa_kuantitas'], 2, '.', ','); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>