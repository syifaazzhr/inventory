<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Penerimaan Pembelian</title>
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

<h2 class="text-center">PENERIMAAN PEMBELIAN</h2>

<table>
  <tr><td><b>Nomor Penerimaan</b></td><td><?= esc($penerimaan['nomor_bon']) ?></td></tr>
  <tr><td><b>Tanggal Terima</b></td><td><?= esc(date('d-m-Y', strtotime($penerimaan['tgl_terima']))) ?></td></tr>
  <tr><td><b>Nomor PO</b></td><td><?= esc($purchase_order['nomor_po']) ?></td></tr>
  <tr><td><b>Supplier</b></td><td><?= esc($supplier['nama_supplier']) ?></td></tr>
</table>

<br>
<h4>DETAIL BARANG</h4>
<table>
  <thead>
    <tr>
      <th class="text-center">Kode Barang</th>
      <th class="text-center">Nama Barang</th>
      <th class="text-center">Kuantitas</th>
      <th class="text-center">Rol</th>
      <th class="text-center">Jumlah</th>
    </tr>
  </thead>
  <tbody>
    <?php
        $total_qty = 0;
        foreach ($detail_penerimaan as $item):
        $total_qty += $item['qty_det_terima']; 
    ?>
    <tr>
      <td><?= esc($item['kode_stok']) ?></td>
      <td><?= esc($item['nama_barang']) ?></td>
      <td class="text-right"><?= number_format($item['qty_det_terima'], 2, '.', ',') ?></td>
      <td class="text-right">
        <?php 
          $uniqueRolls = [];
          foreach ($lotrol_penerimaan as $lotrol) {
            if ($lotrol['kode_stok'] == $item['kode_stok']) {
              $uniqueRolls[$lotrol['rol_terima']] = true;
            }
          }
          $jumlahRol = count($uniqueRolls); 
          echo number_format($jumlahRol, 0, '.', ','); 
        ?>
      </td>
      <td class="text-right"><?= number_format($item['jumlah_det_terima'], 2, '.', ',') ?></td>
    </tr>
    <?php endforeach; ?>
    <tr>
      <td colspan="2" class="text-right bold">Total Kuantitas / Total Rol / Total Harga</td>
      <td class="text-right bold"><?= number_format($total_qty, 2, '.', ',') ?></td>
      <td class="text-right bold"><?= number_format($jumlahRol) ?></td>
      <td class="text-right bold"><?= number_format($penerimaan['total_beli_terima'], 2, '.', ',') ?></td>
    </tr>
  </tbody>
</table>

<br>
<h4>DETAIL LOT & ROL</h4>

<div style="display: flex; flex-wrap: wrap; gap: 15px;">
    <?php 
    if (!empty($lotrol_penerimaan)):
        $grouped = [];
        foreach ($lotrol_penerimaan as $row) {
            $lot = $row['lot_terima'] ?? '-';
            $grouped["$lot"][] = $row;
        }

        foreach ($grouped as $key => $items):
            [$lot] = explode('|', $key);
    ?>
    <div style="border: 1px solid #000; padding: 10px; display: inline-block; min-width: 200px;"> 
      <?php 
        $uniqueRolls = [];
        foreach ($items as $item) {
            $uniqueRolls[$item['rol_terima']] = true;
        }
        $jumlahRol = count($uniqueRolls);
      ?>
      <p style="margin: 0 0 5px 0;"><strong>Kode Barang: <?= esc($items[0]['kode_stok'] ?? '-') ?></strong></p>
      <p style="margin: 0 0 5px 0;"><strong>Lot: <?= esc($lot) ?></strong></p>
      <p style="margin: 0 0 5px 0;"><strong>Jumlah Rol: <?= number_format($jumlahRol) ?></strong></p> 
      <br>
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
                    <td class="text-center"><?= esc($item['rol_terima'] ?? '-') ?></td>
                    <td class="text-center"><?= number_format($item['qty_lotrol_terima'] ?? 0, 2, '.', ',') ?></td>
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

</body>
</html>