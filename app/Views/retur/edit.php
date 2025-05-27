<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Retur Pembelian</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<a href="<?= base_url('admin/retur/' . $retur['retur_id']); ?>" class="btn btn-outline-primary mb-3">
  <i class="ti ti-arrow-left"></i> Kembali
</a>

<h5 class="card-title fw-semibold mb-4 text-dark">Ubah Data Retur Pembelian</h5>

<div class="card mb-4">
  <div class="card-body">
    <form action="<?= base_url('admin/retur/' . $retur['retur_id'] . '/update'); ?>" method="post">
      <?= csrf_field(); ?> 

      <!-- Nomor Bon dan Tanggal Terima -->
      <div class="row">
        <div class="col-md-4 mb-3">
          <label class="form-label">Nomor Retur</label>
          <input type="text" class="form-control" name="nomor_retur" value="<?= $retur['nomor_retur']; ?>" readonly>
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Tanggal Retur</label>
          <input type="date" class="form-control" name="tgl_retur" value="<?= $retur['tgl_retur']; ?>" required>
        </div>
      </div>

      <div class="row">
        <div class="col-md-4 mb-3">
          <label class="form-label">Nama Supplier</label>
          <div class="position-relative">
            <input type="text" class="form-control" id="nama_supplier" autocomplete="off" placeholder="Ketik nama supplier lalu tekan Enter" value="<?= $selected_supplier['nama_supplier']; ?>" required>
            <input type="hidden" id="supplier_id" name="supplier_id" value="<?= $retur['supplier_id']; ?>">
            <div id="result_supplier" class="dropdown-menu w-100" style="display:none; max-height: 200px; overflow-y: auto; position: absolute; z-index: 1000;"></div>
          </div>
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Kode Supplier</label>
          <input type="text" class="form-control" id="kode_supplier" value="<?= $selected_supplier['kode_supplier']; ?>" readonly>
        </div>
      </div>

      <div class="row">
        <div class="col-md-8 mb-3">
          <label class="form-label">Keterangan</label>
          <textarea class="form-control" name="ket_retur" rows="1"><?= $retur['ket_retur']; ?></textarea>
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Admin</label>
          <input type="text" class="form-control" name="admin_retur" value="<?= $retur['admin_retur']; ?>" readonly>
        </div>
      </div>

      <div id="detail-retur-container">
      <?php foreach ($detail_retur as $index => $detail) : ?>
        <div class="row detail-retur">
          <div class="col-md-1 mb-3 d-flex align-items-end">
            <?php if ($index === 0): ?>
              <button type="button" class="btn btn-primary btn-add-detail">+</button>
            <?php else: ?>
              <button type="button" class="btn btn-danger btn-remove-detail">-</button>
            <?php endif; ?>
          </div>
          <div class="col-md-3 mb-3">
            <label class="form-label">Kode Stok</label>
            <div class="position-relative">
              <input type="text" class="form-control kode_stok_input" autocomplete="off" required value="<?= $detail['kode_stok']; ?>" placeholder="Ketik kode/nama lalu tekan Enter">
              <input type="hidden" class="kode_stok_hidden" name="kode_stok[]" value="<?= $detail['stok_id']; ?>">
              <div class="dropdown-menu result_kode_stok w-100" style="display:none; max-height: 200px; overflow-y: auto; position: absolute; z-index: 1000;"></div>
            </div>
          </div>
          <div class="col-md-3 mb-3">
            <label class="form-label">Nama Barang</label>
            <input type="text" class="form-control nama_barang" value="<?= isset($detail['nama_barang']) ? $detail['nama_barang'] : ''; ?>" readonly>
          </div>
          <div class="col-md-2 mb-3">
            <label class="form-label">Kuantitas per Barang</label>
            <input type="text" class="form-control kuantitas" name="total_kuantitas[]" value="<?= number_format($detail['qty_det_retur'], 2, '.', ','); ?>" required>
          </div>
          <div class="col-md-3 mb-3">
            <label class="form-label">Harga Beli per Barang</label>
            <input type="text" class="form-control harga_total" name="jumlah_harga_beli[]" value="<?= number_format($detail['jumlah_det_retur'], 2, '.', ','); ?>" data-manual="false">
          </div>
        </div>
      <?php endforeach; ?>
      </div>

      <div class="row">
        <div class="col-md-3 mb-3">
          <label class="form-label">Total Kuantitas</label>
          <input type="text" class="form-control" id="total_qty_retur" value="<?= $total_kuantitas ?? 0; ?>" readonly>
        </div>
        <div class="col-md-3 mb-3">
          <label class="form-label">Total Beli</label>
          <input type="text" class="form-control" id="total_beli_retur" value="<?= $jumlah_harga_beli ?? 0; ?>" readonly>
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">PPN</label>
            <input type="text" class="form-control" id="ppn_retur" value="<?= number_format($ppn_retur ?? 0, 2, '.', ','); ?>" readonly>
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">Jumlah Total</label>
            <input type="text" class="form-control" id="total_beli_retur_ppn" value="<?= number_format($total_harga_beli_retur_ppn ?? 0, 2, '.', ','); ?>" readonly>
        </div>
      </div>
      <button type="submit" class="btn btn-primary mt-2">Simpan Perubahan</button>
    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const container = document.getElementById('detail-retur-container');
  const addButton = document.querySelector('.btn-add-detail');
  const kodeSupplierSelect = document.getElementById('kode_supplier');
  const namaSupplierInput = document.getElementById('nama_supplier');
  const inputs = document.querySelectorAll('input[type="text"], textarea');

  $(function () {
  $('#nama_supplier').on('keydown', function (e) {
    if (e.key === 'Enter') {
      e.preventDefault(); 
      const keyword = $(this).val().trim();
      if (!keyword) return;

      $.ajax({
        url: '/admin/retur/search_supplier',
        method: 'GET',
        data: { q: keyword },
        dataType: 'json',
        success: function (data) {
          let html = '';
          if (data.length === 0) {
            html = '<div class="dropdown-item disabled">Tidak ada data ditemukan</div>';
          } else {
            data.forEach(sup => {
              html += `<div class="dropdown-item supplier-item"
                        data-id="${sup.id}"
                        data-nama="${sup.nama_supplier}"
                        data-kode="${sup.kode_supplier}">
                        ${sup.nama_supplier} (${sup.kode_supplier})
                      </div>`;
            });
          }

          $('#result_supplier').html(html).show();
        }
      });
    }
  });

  $(document).on('click', '.supplier-item', function () {
    const supplierId = $(this).data('id');
    const namaSupplier = $(this).data('nama');
    const kodeSupplier = $(this).data('kode');

    $('#supplier_id').val(supplierId);
    $('#nama_supplier').val(namaSupplier);
    $('#kode_supplier').val(kodeSupplier);
    $('#result_supplier').hide();

    fetch(`/admin/retur/stok_by_supplier/${supplierId}`)
      .then(res => res.json())
      .then(data => {
        document.querySelectorAll('.kode_stok').forEach(select => {
          select.innerHTML = '<option value="">Pilih Kode Stok</option>';
          data.forEach(stok => {
            const option = document.createElement('option');
            option.value = stok.stok_id;
            option.textContent = stok.kode_stok;
            option.setAttribute('data-nama', stok.nama_barang);
            option.setAttribute('data-harga', stok.harga_beli);
            select.appendChild(option);
          });
        });
      });
  });

  $('#nama_supplier').on('input', function () {
      if ($(this).val().trim() === '') {
        $('#supplier_id').val('');
        $('#kode_supplier').val('');
        $('#result_supplier').hide();
      }
    });
  });

  // ENTER CARI KODE STOK
$(document).on('keydown', '.kode_stok_input', function (e) {
  if (e.key === 'Enter') {
    e.preventDefault();
    const input = $(this);
    const keyword = input.val().trim();
    const parent = input.closest('.position-relative');
    const resultBox = parent.find('.result_kode_stok');

    if (!keyword) return;

    $.ajax({
      url: '/admin/retur/search_stok',
      method: 'GET',
      data: { q: keyword },
      dataType: 'json',
      success: function (data) {
        let html = '';
        if (data.length === 0) {
          html = '<div class="dropdown-item disabled">Kode Stok tidak ditemukan</div>';
        } else {
          data.forEach(stok => {
            html += `<div class="dropdown-item stok-item"
                        data-id="${stok.stok_id}"
                        data-kode="${stok.kode_stok}"
                        data-nama="${stok.nama_barang}"
                        data-harga="${stok.harga_beli}">
                        ${stok.kode_stok} - ${stok.nama_barang}
                    </div>`;
          });
        }
        resultBox.html(html).show();
      }
    });
  }
});

// PILIH SALAH SATU HASIL
$(document).on('click', '.stok-item', function () {
  const item = $(this);
  const parent = item.closest('.position-relative');
  const row = item.closest('.detail-retur');

  const stokId = item.data('id');
  const kode = item.data('kode');
  const nama = item.data('nama');
  const harga = parseFloat(item.data('harga')) || 0;

  parent.find('.kode_stok_input').val(kode);
  parent.find('.kode_stok_input')[0].dataset.harga = harga; // <- pastikan dataset langsung di-set
  parent.find('.kode_stok_hidden').val(stokId);
  parent.find('.result_kode_stok').hide();

  row.find('.nama_barang').val(nama);

  const qty = parseFloat(row.find('.kuantitas').val().replace(',', '.')) || 1;
  row.find('.harga_total').val((harga * qty).toLocaleString('en-US', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  })).data('manual', false);

  updateTotal();
});

$(document).on('input', '.kode_stok_input', function () {
  const row = $(this).closest('.detail-retur');
  row.find('.kode_stok_hidden').val('');
  row.find('.nama_barang').val('');
  row.find('.kuantitas').val(''); // ← tambahkan ini
  row.find('.harga_total').val('0.00');
  row.find('.kode_stok_input')[0].dataset.harga = ''; // reset harga satuan
  updateTotal();
});

inputs.forEach(function(input) {
  if (!input.classList.contains('harga_total') && !input.classList.contains('kuantitas')) {
    input.addEventListener('input', function() {
      const cursorPos = this.selectionStart;
      this.value = this.value.toUpperCase();
      this.setSelectionRange(cursorPos, cursorPos); // biar kursor gak loncat
    });
  }
});

  container.addEventListener('click', function(e) {
    if (e.target.classList.contains('btn-remove-detail')) {
      const rows = document.querySelectorAll('.detail-retur');
      if (rows.length > 1) {
        e.target.closest('.detail-retur').remove();
        updateTotal();
      }
    }
  });

  function updateTotal() {
  let totalQty = 0, totalHarga = 0;
  document.querySelectorAll('.detail-retur').forEach(row => {
    let qtyText = row.querySelector('.kuantitas').value || '0';
    let qty = parseFloat(qtyText.replace(',', '.')) || 0;

    let hargaText = row.querySelector('.harga_total').value || '0';
    let harga = parseFloat(hargaText.replace(/,/g, '')) || 0;

    totalQty += qty;
    totalHarga += harga;
  });

  const ppn = totalHarga * 0.11; 
  const totalPPN = totalHarga + ppn;

  document.getElementById('total_qty_retur').value = totalQty.toFixed(2);
  document.getElementById('total_beli_retur').value = totalHarga.toLocaleString('en-US', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  });
  document.getElementById('ppn_retur').value = ppn.toLocaleString('en-US', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  });
  document.getElementById('total_beli_retur_ppn').value = totalPPN.toLocaleString('en-US', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  });
}

document.querySelectorAll('.detail-retur').forEach(function(row) {
  const hargaField = row.querySelector('.harga_total');
  const qtyField = row.querySelector('.kuantitas');
  const kodeInput = row.querySelector('.kode_stok_input');

  // Ambil harga total dari value (sudah dalam format number)
  const hargaTotal = parseFloat(hargaField.value.replace(/,/g, '')) || 0;
  const qty = parseFloat(qtyField.value.replace(',', '.')) || 1;

  // Hitung ulang dan simpan sebagai dataset
  const hargaSatuan = hargaTotal / qty;
  kodeInput.dataset.harga = hargaSatuan.toFixed(2);
});

  function autoFill(event) {
    if (event.target.classList.contains('kode_stok')) {
      let row = event.target.closest('.detail-retur');
      let selected = event.target.options[event.target.selectedIndex];

      row.querySelector('.nama_barang').value = selected.getAttribute('data-nama') || '';

      let hargaBeli = parseFloat(selected.getAttribute('data-harga')) || 0;
      let qty = parseFloat(row.querySelector('.kuantitas').value.replace(',', '.')) || 1;
      let hargaField = row.querySelector('.harga_total');

      if (hargaField.dataset.manual !== "true") {
        hargaField.value = (hargaBeli * qty).toLocaleString('en-US', {
          minimumFractionDigits: 2,
          maximumFractionDigits: 2
        });
      }

      hargaField.dataset.manual = "false"; // Reset manual jika ganti kode stok
      updateTotal();
    }
  }

  function updateHarga(event) {
  if (event.target.classList.contains('kuantitas')) {
    const row = event.target.closest('.detail-retur');
    const hargaField = row.querySelector('.harga_total');
    const inputHarga = row.querySelector('.kode_stok_input');
    
    const hargaData = inputHarga.dataset.harga;
    if (!hargaData || isNaN(hargaData)) return;

    const hargaSatuan = parseFloat(hargaData);
    const qty = parseFloat(event.target.value.replace(',', '.')) || 0;

    if (hargaField.dataset.manual !== "true") {
      const totalHarga = hargaSatuan * qty;
      hargaField.value = totalHarga.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      });
    }

    updateTotal();
  }
}

function addRow() {
  const row = document.querySelector('.detail-retur');
  const newRow = row.cloneNode(true);

  // Reset semua nilai input
  newRow.querySelector('.kode_stok_input').value = '';
  newRow.querySelector('.kode_stok_hidden').value = '';
  newRow.querySelector('.nama_barang').value = '';
  newRow.querySelector('.kuantitas').value = '';
  newRow.querySelector('.harga_total').value = '0.00';
  newRow.querySelector('.kode_stok_input').dataset.harga = '';

  // Reset event dropdown hasil pencarian (kalau sebelumnya masih kebuka)
  const dropdown = newRow.querySelector('.result_kode_stok');
  if (dropdown) dropdown.style.display = 'none';

  // Pastikan tombolnya jadi tombol "-"
  const buttonCell = newRow.querySelector('.col-md-1');
  buttonCell.innerHTML = `
    <button type="button" class="btn btn-danger btn-remove-detail">-</button>
  `;

  container.appendChild(newRow);
}

  // Delegasi tombol "+"
  container.addEventListener('click', function (event) {
    if (event.target.classList.contains('btn-add-detail')) {
      event.preventDefault();
      addRow();
    }

    if (event.target.classList.contains('btn-remove-detail')) {
      event.preventDefault();
      const row = event.target.closest('.detail-retur');
      row.remove();
      updateTotal();
    }
  });

  container.addEventListener('change', function (event) {
  if (event.target.classList.contains('stok_id')) {
    const row = event.target.closest('.detail-retur');
    const option = event.target.selectedOptions[0];

    const harga = parseFloat(option.getAttribute('data-harga')) || 0;
    const nama = option.getAttribute('data-nama') || '';
    const kuantitas = parseFloat(option.getAttribute('data-kuantitas')) || 0;

    const qtyInput = row.querySelector('.kuantitas');
    const namaInput = row.querySelector('.nama_barang');
    const hargaInput = row.querySelector('.harga_total');

    // Isi nama barang
    namaInput.value = nama;

    // Isi kuantitas otomatis hanya jika masih kosong atau 0
    if (!qtyInput.value || parseFloat(qtyInput.value) === 0) {
      qtyInput.value = kuantitas.toFixed(2);
    }

    const qty = parseFloat(qtyInput.value) || 0;

    // Set harga satuan di hidden input jika belum ada
    if (!row.querySelector('.harga_satuan')) {
      const hidden = document.createElement('input');
      hidden.type = 'hidden';
      hidden.classList.add('harga_satuan');
      row.appendChild(hidden);
    }
    row.querySelector('.harga_satuan').value = harga;

    // Hitung total harga
    const hargaTotal = qty ? (harga * qty) : harga;

    hargaInput.value = hargaTotal.toLocaleString('en-US', {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2
    });

    updateTotal();
  }
});

  // Sebelum submit form
  document.querySelector('form').addEventListener('submit', function() {
    document.querySelectorAll('.harga_total').forEach(function(input) {
      input.value = input.value.replace(/,/g, ''); // Hapus koma ribuan
    });
  });

  container.addEventListener('input', function (event) {
    if (event.target.classList.contains('kuantitas')) {
      const row = event.target.closest('.detail-retur');
      const hargaSatuan = parseFloat(row.querySelector('.kode_stok_input')?.dataset?.harga || 0);
      const qty = parseFloat(event.target.value.replace(',', '.')) || 0;

      const total = hargaSatuan * qty;

      row.querySelector('.harga_total').value = total.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      });

      updateTotal();
    }
  });

  updateTotal(); 

  $(document).on('input', '.kode_stok_input', function () {
    const cursorPos = this.selectionStart;
    this.value = this.value.toUpperCase();
    this.setSelectionRange(cursorPos, cursorPos); // biar kursor tidak lompat
  });
});

</script>

<?= $this->endSection() ?>