<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Penerimaan Pembelian</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<a href="<?= base_url('admin/penerimaan_pembelian/lot_rol/' . esc($terima_id) . '/' . $det_terima_id); ?>" class="btn btn-outline-primary mb-3">
  <i class="ti ti-arrow-left"></i> Kembali
</a>

<div class="card">
  <div class="card-body">
    <h5 class="card-title fw-semibold">Form Buat Lot & Rol</h5>
    
    <form action="<?= base_url('admin/penerimaan_pembelian/lot_rol/store'); ?>" method="post">
  <?= csrf_field(); ?>

  <!-- Hidden fields for terima_id and det_terima_id -->
  <input type="hidden" name="terima_id" value="<?= esc($terima_id); ?>">
  <input type="hidden" name="det_terima_id" value="<?= esc($det_terima_id); ?>">

  <!-- Form to display Nomor Bon and Nomor PO -->
  <div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Nomor Bon</label>
        <input type="text" class="form-control" value="<?= esc($penerimaan['nomor_bon']); ?>" readonly>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Nomor PO</label>
        <input type="text" class="form-control" value="<?= esc($nomor_po); ?>" readonly>
    </div>
  </div>

  <!-- Display Kode Stok, Nama Barang, and Total Kuantitas -->
  <div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Kode Stok</label>
        <input type="text" class="form-control" value="<?= esc($detail['kode_stok']); ?>" readonly>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Nama Barang</label>
        <input type="text" class="form-control" value="<?= esc($detail['nama_barang']); ?>" readonly>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Kuantitas</label>
        <input type="text" class="form-control" value="<?= esc($detail['qty_det_terima']); ?>" readonly>
    </div>
  </div>

  <!-- Inputs for lot_terima, rol_terima, qty_lotrol_terima -->
  <div class="row"> 
    <div class="col-md-4 mb-3">
        <label class="form-label">Lot Ke</label>
        <input type="text" class="form-control lot_terima" name="lot_terima[]" required>
    </div>
  </div>

  <div id="detail-lot-rol-container">
    <div class="row detail-lot-rol">
        <div class="col-md-1 mb-3 d-flex align-items-end">
            <button type="button" class="btn btn-primary btn-add-detail">+</button>
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">Rol Ke</label>
            <input type="text" class="form-control rol_terima" name="rol_terima[]" required>
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">Kuantitas Per Rol</label>
            <input type="text" class="form-control qty_lotrol_terima" name="qty_lotrol_terima[]" required>
        </div>
    </div>
  </div>

  <!-- Kalkulasi Section -->
  <div class="row mt-3 d-flex align-items-end">
      <div class="col-md-2 mb-3">
          <button type="button" class="btn btn-primary" id="kalkulasi-btn">Kalkulasi</button>
      </div>
      <div class="col-md-2 mb-3">
          <label class="form-label">Jumlah Kuantitas</label>
          <input type="number" class="form-control" id="jumlah_kuantitas" readonly>
      </div>
      <div class="col-md-2 mb-3">
          <label class="form-label">Selisih</label>
          <input type="number" class="form-control" id="selisih" readonly>
      </div>
      <div class="col-md-5 mb-3">
          <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
  </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const container = document.getElementById('detail-lot-rol-container');
  const addButton = document.querySelector('.btn-add-detail');
  const kalkulasiButton = document.getElementById('kalkulasi-btn');
  const jumlahKuantitasField = document.getElementById('jumlah_kuantitas');
  const selisihField = document.getElementById('selisih');
  const totalKuantitas = <?= esc($detail['qty_det_terima']); ?>;
  const inputs = document.querySelectorAll('input[type="text"], textarea');

  inputs.forEach(function(input) {
      input.addEventListener('input', function() {
      this.value = this.value.toUpperCase();
      });
  });

  let counter = 1; // Mulai dengan counter 1 untuk Rol Ke

  // Fungsi untuk menambah baris baru untuk Lot & Rol
  function addRow() {
    let row = document.querySelector('.detail-lot-rol');
    let newRow = row.cloneNode(true);

    // Reset nilai input pada elemen baru
    newRow.querySelector('.rol_terima').value = 'R' + String(counter).padStart(2, '0');
    newRow.querySelector('.qty_lotrol_terima').value = '';

    counter++;

    let addButtonInRow = newRow.querySelector('.btn-add-detail');
    if (addButtonInRow) addButtonInRow.remove();

    let removeButton = newRow.querySelector('.btn-remove-detail');
    if (!removeButton) {
      removeButton = document.createElement('button');
      removeButton.type = 'button';
      removeButton.classList.add('btn', 'btn-danger', 'btn-remove-detail');
      removeButton.innerText = '-';
      removeButton.addEventListener('click', removeRow);

      let col = document.createElement('div');
      col.classList.add('col-md-1', 'mb-3', 'd-flex', 'align-items-end');
      col.appendChild(removeButton);

      newRow.replaceChild(col, newRow.firstElementChild);
    }

    container.appendChild(newRow);
    updateRolKe();
  }

  // Fungsi untuk menghapus baris
  function removeRow(event) {
    let rows = document.querySelectorAll('.detail-lot-rol');
    if (rows.length > 1) {
      event.target.closest('.detail-lot-rol').remove();
      counter--;
      updateRolKe();
    }
  }

  // Fungsi untuk memperbarui urutan "Rol Ke"
  function updateRolKe() {
    let rows = document.querySelectorAll('.detail-lot-rol');
    rows.forEach((row, index) => {
      let rolKeInput = row.querySelector('.rol_terima');
      rolKeInput.value = 'R' + String(index + 1).padStart(2, '0');
    });

    counter = rows.length + 1;
  }

  // Fungsi untuk kalkulasi jumlah kuantitas dan selisih
  function kalkulasi() {
    let totalQtyPerRol = 0;
    let qtyPerRolFields = document.querySelectorAll('.qty_lotrol_terima');

    // Loop untuk menjumlahkan Kuantitas Per Rol
    qtyPerRolFields.forEach(function(field) {
      let qty = parseFloat(field.value) || 0;  // Pastikan nilai numerik atau 0 jika kosong
      totalQtyPerRol += qty;
    });
    jumlahKuantitasField.value = totalQtyPerRol.toFixed(2);

    // Hitung Selisih antara total Kuantitas dan Jumlah Kuantitas Per Rol
    let selisih = totalKuantitas - totalQtyPerRol;
    selisihField.value = selisih.toFixed(2);
  }

  // Menambahkan event listener untuk tombol kalkulasi
  if (kalkulasiButton) {
    kalkulasiButton.addEventListener('click', kalkulasi);
  }

  // Inisialisasi event untuk tombol tambah
  if (addButton) {
    addButton.addEventListener('click', addRow);
  }

  // Mengupdate "Rol Ke" pertama kali setelah halaman dimuat
  updateRolKe();
});
</script>

<?= $this->endSection() ?> 