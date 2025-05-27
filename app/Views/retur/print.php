<!DOCTYPE html>

<?php
function formatTanggalIndonesia($tanggal) {
    $bulan = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    $tanggalObj = strtotime($tanggal);
    $hari = date('d', $tanggalObj);
    $bulanIndex = (int) date('m', $tanggalObj);
    $tahun = date('Y', $tanggalObj);

    return $hari . ' ' . $bulan[$bulanIndex] . ' ' . $tahun;
}
?>

<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Retur Pembelian</title>
  <style>
    body { font-family: Arial, sans-serif; font-size: 12px; padding: 30px; }
    table { width: 100%; border-collapse: collapse;}
    th, td { border: 1px solid #000; padding: 5px; text-align: left; }
    .text-center { text-align: center; }
    .text-right { text-align: right; }
    .bold { font-weight: bold; }
  </style>
</head>
<body>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
  <div style="flex-shrink: 0;">
    <img src="<?= base_url('assets/images/akusara-logo.png') ?>" alt="Logo Perusahaan" style="height: 80px;">
  </div>
  <div style="text-align: right;">
    <h1 style="margin: 0;">RETUR PEMBELIAN</h1>
    <h3 style="margin: 0;">NO. Retur: <?= esc($retur['nomor_retur']) ?></h3>
  </div>
</div>
<br>

<div style="border-top: 2px solid #000; margin-bottom: 15px;"></div>

<br>
<div style="text-align: right; margin-bottom: 5px;">
  Bandung, <?= formatTanggalIndonesia($retur['tgl_retur']) ?>
</div>
<br>

<div style="display: flex; justify-content: space-between; align-items: flex-start;">
  <div style="width: 33%;">
    <strong>CV AKUSARA</strong><br>
    JL SUKARAJA II NO 15<br>
    KOTA BANDUNG
  </div>
  <div style="width: 34%;"></div>
  <div style="width: 33%; text-align: right;">
    Kepada Yth.<br>
    <strong><?= esc($retur['nama_supplier']) ?></strong><br>
    <?= esc($retur['alamat_supplier'] ?? '-') ?>
  </div>
</div>

<br><br>
<p style="margin: 0;"><strong>Mohon diterima pengembalian barang-barang di bawah ini.</strong></p>
<br>

<table>
  <thead>
    <tr>
      <th class="text-center">Kode Stok</th>
      <th class="text-center">Nama Barang</th>
      <th class="text-center">Satuan</th>
      <th class="text-center">Harga Beli</th>
      <th class="text-center">Qty Retur</th>
      <th class="text-center">Jumlah</th>
    </tr>
  </thead>
  <tbody>
    <?php
        $total_qty_retur = 0; 
        foreach ($detail_retur as $i => $item):
        $total_qty_retur += $item['qty_det_retur']; 
    ?>
    <tr>
      <td><?= esc($item['kode_stok']) ?></td>
      <td><?= esc($item['nama_barang']) ?></td>
      <td><?= esc($item['jenis_satuan']) ?></td>
      <td class="text-right"><?= number_format($item['jumlah_det_retur'] / $item['qty_det_retur'], 2, '.', ',') ?></td>
      <td class="text-right"><?= number_format($item['qty_det_retur'], 2, '.', ',') ?></td>
      <td class="text-right"><?= number_format($item['jumlah_det_retur'], 2, '.', ',') ?></td>
    </tr>
    <?php endforeach; ?>
    <tr>
      <td colspan="4" class="text-right bold">Total Kuantitas / Total Harga</td>
      <td class="text-right bold"><?= number_format($total_qty_retur, 2, '.', ',') ?></td>
      <td class="text-right bold"><?= number_format($retur['total_beli_retur'], 2, '.', ',') ?></td>
    </tr>
    <tr>
      <td colspan="4" class="text-right bold">PPN</td>
      <td colspan="2" class="text-right bold"><?= number_format($retur['ppn_retur'], 2, '.', ',') ?></td>
    </tr>
    <tr>
      <td colspan="4" class="text-right bold">Jumlah Total</td>
      <td colspan="2" class="text-right bold"><?= number_format($retur['total_beli_retur_ppn'], 2, '.', ',') ?></td>
    </tr>
    <tr>
      <td colspan="1" class="bold">Keterangan</td>
      <td colspan="5" class="bold"><?= esc($retur['ket_retur']) ?></td>
    </tr>
  </tbody>
</table>

<br><br>
<p style="margin: 0;"><strong>Berikut adalah rincian Lot dan Rol untuk retur ini.</strong></p>
<br>
<div style="display: flex; flex-wrap: wrap; gap: 15px;">
<?php 
if (!empty($lotrol_retur)):
    $grouped = [];
    foreach ($lotrol_retur as $row) {
        $po = $row['nomor_po'] ?? '-';
        $lot = $row['lot_terima_asal'] ?? '-';
        $grouped["$po|$lot"][] = $row;
    }

    foreach ($grouped as $key => $items):
        [$po, $lot] = explode('|', $key);
?>
    <div style="border: 1px solid #000; padding: 10px; display: inline-block; min-width: 200px;">
        <p style="margin: 0 0 5px 0;"><strong>Nomor PO: <?= esc($po) ?><br>Lot: <?= esc($lot) ?></strong></p>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th class="text-center">Rol</th>
                    <th class="text-center">Retur</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td class="text-center"><?= esc($item['rol_terima_asal'] ?? '-') ?></td>
                    <td class="text-center"><?= number_format($item['qty_lotrol_retur'] ?? 0, 2, '.', ',') ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php 
    endforeach;
else: 
?>
    <p class="text-center">Tidak ada data Lot & Rol</p>
<?php endif; ?>
</div>

<br><br><br><br><br><br><br>
<div style="display: flex; justify-content: space-between; width: 60%; margin: 0 auto;">
  <div style="text-align: center;">
    Dibuat Oleh,<br><br><br><br><br><br>
    __________________
  </div>
  <div style="text-align: center;">
    Disetujui Oleh,<br><br><br><br><br><br>
    __________________
  </div>
</div>
</body>
</html>