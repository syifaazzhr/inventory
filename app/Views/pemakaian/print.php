<!DOCTYPE html>

<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Pemakaian Bahan</title>
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

<div style="text-align: center; margin-bottom: 5px;">
  <h2 style="margin: 0;">PEMAKAIAN BAHAN</h2>
  <p style="margin: 0;">NO. PEMAKAIAN: <?= esc($pemakaian['nomor_pakai']) ?></p>
</div>

<h4>DETAIL BARANG</h4>
<table>
  <thead>
    <tr>
      <th>Kode Stok</th>
      <th>Nama Barang</th>
      <th>Qty</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($detail_pemakaian as $item): ?>
    <tr>
      <td><?= esc($item['kode_stok']) ?></td>
      <td><?= esc($item['nama_barang']) ?></td>
      <td class="text-right"><?= number_format($item['qty_det_pakai'], 2, ',', '.') ?></td>
    </tr>
    <?php endforeach; ?>
    <tr>
      <td colspan="2" class="text-right bold">TOTAL</td>
      <td class="text-right bold"><?= number_format($pemakaian['total_qty_pakai'], 2, ',', '.') ?></td>
    </tr>
  </tbody>
</table>

<h4>DETAIL ROL</h4>
<table>
  <thead> 
    <tr>
      <th>Nomor PO</th>
      <th>Lot</th>
      <th>Rol</th>
      <th>Qty Pakai</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($lotrol_pemakaian)): ?>
      <?php foreach ($lotrol_pemakaian as $item): ?>
        <tr>
          <td><?= esc($item['nomor_po'] ?? '-') ?></td>
          <td><?= esc($item['lot_terima_asal']) ?></td>
          <td><?= esc($item['rol_terima_asal']) ?></td>
          <td class="text-right"><?= number_format($item['qty_lotrol_pakai'], 2, ',', '.') ?></td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <?php foreach ($lotrol_penerimaan as $lotrol): ?>
        <tr>
          <td><?= esc($lotrol['nomor_po'] ?? '-') ?></td>
          <td><?= esc($lotrol['lot']) ?></td>
          <td><?= esc($lotrol['rol']) ?></td>
          <td class="text-right"><?= number_format($lotrol['sisa_kuantitas'], 2, ',', '.') ?></td>
        </tr>
      <?php endforeach; ?>
    <?php endif; ?>
  </tbody>
</table>
</body>
</html>