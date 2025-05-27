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
  <title>Purchase Order</title>
  <style>
    body { font-family: Arial, sans-serif; font-size: 12px; padding: 30px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #000; padding: 5px; text-align: left; }
    .no-border { border: none; }
    .text-center { text-align: center; }
    .text-right { text-align: right; }
    .header-table td { vertical-align: top; }
    .signature td { height: 80px; text-align: center; }
    .bold { font-weight: bold; }
  </style>
</head>
<body>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
  <div style="flex-shrink: 0;">
    <img src="<?= base_url('assets/images/akusara-logo.png') ?>" alt="Logo Perusahaan" style="height: 80px;">
  </div>
  <div style="text-align: right;">
    <h1 style="margin: 0;">PURCHASE ORDER</h1>
    <h3 style="margin: 0;">NO. PO: <?= esc($purchase_order['nomor_po']) ?></h3>
  </div>
</div>
<br>

<div style="border-top: 2px solid #000; margin-bottom: 15px;"></div>

<br>
<div style="text-align: right; margin-bottom: 5px;">
  Bandung, <?= formatTanggalIndonesia($purchase_order['tgl_order']) ?>
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
    <strong><?= esc($purchase_order['nama_supplier']) ?></strong><br>
    <?= esc($purchase_order['alamat_supplier'] ?? '-') ?>
  </div>
</div>

<br><br>
<p style="margin: 0;"><strong>No. Worksheet:</strong> <?= esc($purchase_order['nomor_ws']) ?></p>
<p style="margin: 0;"><strong>Barang dikirim sebelum tanggal:</strong> <?= formatTanggalIndonesia($purchase_order['max_tgl_kirim']) ?></p>
<p style="margin: 0;"><strong>Mohon dikirimkan barang-barang di bawah ini.</strong></p>
<br>
 
<table>
  <thead>
    <tr>
        <th class="text-center">Kode Barang</th>
        <th class="text-center">Nama Barang</th>
        <th class="text-center">Satuan</th>
        <th class="text-center">Harga</th>
        <th class="text-center">Qty</th>
        <th class="text-center">Harga Beli</th>
    </tr>
  </thead>
  <tbody>
    <?php
        $total_qty = 0;
        foreach ($detail_po as $i => $item):
        $total_qty += $item['qty_detpo']; 
    ?>
    <tr>
      <td><?= esc($item['kode_stok']) ?></td>
      <td><?= esc($item['nama_barang']) ?></td>
      <td><?= esc($item['jenis_satuan'] ?? '-') ?></td>
      <td class="text-right"><?= number_format($item['harga_beli'], 2, '.', ',') ?></td>
      <td class="text-right"><?= number_format($item['qty_detpo'], 2, '.', ',') ?></td>
      <td class="text-right"><?= number_format($item['jumlah_detpo'], 2, '.', ',') ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
  <tfoot>
  <tr>
    <td colspan="4" class="text-right bold">Total Kuantitas / Total Harga</td>
    <td class="text-right bold"><?= number_format($total_qty, 2, '.', ',') ?></td>
    <td class="text-right bold"><?= number_format($purchase_order['jml_total_po'], 2, '.', ',') ?></td>
  </tr>
  <tr>
    <td colspan="4" class="text-right bold">PPN</td>
    <td colspan="2" class="text-right bold"><?= number_format($purchase_order['ppn_po'], 2, '.', ',') ?></td>
  </tr>
  <tr>
    <td colspan="4" class="text-right bold">Jumlah Total</td>
    <td colspan="2" class="text-right bold"><?= number_format($purchase_order['jml_total_po_ppn'], 2, '.', ',') ?></td>
  </tr>
  <tr>
    <td colspan="1" class="bold">Keterangan</td>
    <td colspan="5" class="bold"><?= esc($purchase_order['ket_po']) ?></td>
  </tr>
</tfoot>
</table>

<br><br><br><br><br><br>
<div style="display: flex; justify-content: space-between; width: 60%; margin: 0 auto;">
  <div style="text-align: center;">
    Dibuat Oleh,<br><br><br><br><br><br><br>
    __________________
  </div>
  <div style="text-align: center;">
    Disetujui Oleh,<br><br><br><br><br><br><br>
    __________________
  </div>
</div>
</body>
</html>