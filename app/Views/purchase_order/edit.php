<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Purchase Order</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<a href="<?= base_url('admin/purchase_order/' . $purchase_order['po_id']); ?>" class="btn btn-outline-primary mb-3">
  <i class="ti ti-arrow-left"></i> Kembali
</a>

<h5 class="card-title fw-semibold mb-4 text-dark">Ubah Data Purchase Order</h5>

<div class="card mb-4">
  <div class="card-body">
    <form action="<?= base_url('admin/purchase_order/' . $purchase_order['po_id'] . '/update'); ?>" method="post">
      <?= csrf_field(); ?>
      <div class="row">
        <div class="col-md-4 mb-3">
          <label class="form-label">Nomor PO</label>
          <input type="text" class="form-control" name="nomor_po" value="<?= $purchase_order['nomor_po']; ?>" readonly>
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Tanggal Order</label>
          <input type="date" class="form-control" name="tgl_order" value="<?= $purchase_order['tgl_order']; ?>" required>
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Batas Tanggal Kirim</label>
          <input type="date" class="form-control" name="max_tgl_kirim" value="<?= $purchase_order['max_tgl_kirim']; ?>" required>
        </div>
      </div>
      <div class="row">
        <div class="col-md-4 mb-3">
          <label class="form-label">Nomor Worksheet</label>
          <input type="text" class="form-control" name="nomor_ws" value="<?= $purchase_order['nomor_ws']; ?>">
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Nomor Artikel</label>
          <input type="text" class="form-control" name="nomor_art" value="<?= $purchase_order['nomor_art']; ?>">
        </div>
      </div>
      <div class="row">
        <div class="col-md-4 mb-3">
          <label class="form-label">Nama Supplier</label>
          <div class="position-relative">
            <input type="text" class="form-control" id="nama_supplier" autocomplete="off" placeholder="Ketik nama supplier lalu tekan Enter" value="<?= $selected_supplier['nama_supplier']; ?>" required>
            <input type="hidden" id="supplier_id" name="supplier_id" value="<?= $purchase_order['supplier_id']; ?>">
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
          <textarea class="form-control" name="ket_po" rows="1"><?= $purchase_order['ket_po']; ?></textarea>
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Admin</label>
          <input type="text" class="form-control" name="admin_po" value="<?= $purchase_order['admin_po']; ?>" readonly>
        </div>
      </div>
      <div id="detail-po-container">
      <?php foreach ($purchase_order_details as $index => $detail) : ?>
        <div class="row detail-po">
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
            <input type="text" class="form-control kuantitas" name="total_kuantitas[]" value="<?= number_format($detail['qty_detpo'], 2, '.', ','); ?>" required>
          </div>
          <div class="col-md-3 mb-3">
            <label class="form-label">Harga Beli per Barang</label>
            <input type="text" class="form-control harga_total" name="jumlah_harga_beli[]" value="<?= number_format($detail['jumlah_detpo'], 2, '.', ','); ?>" data-manual="false">
          </div>
        </div>
      <?php endforeach; ?>
      </div>
      <div class="row">
        <div class="col-md-3 mb-3">
          <label class="form-label">Total Kuantitas</label>
          <input type="text" class="form-control" id="total_kuantitas_po" value="<?= number_format($total_kuantitas_po ?? 0, 2, '.', ','); ?>" readonly>
        </div>
        <div class="col-md-3 mb-3">
          <label class="form-label">Total Beli</label>
          <input type="text" class="form-control" id="total_harga_po" value="<?= number_format($total_harga_beli_po ?? 0, 2, '.', ','); ?>" readonly>
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">PPN</label>
            <input type="text" class="form-control" id="ppn_po" value="<?= number_format($ppn_po ?? 0, 2, '.', ','); ?>" readonly>
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">Jumlah Total</label>
            <input type="text" class="form-control" id="total_po_ppn" value="<?= number_format($total_harga_beli_po_ppn ?? 0, 2, '.', ','); ?>" readonly>
        </div>
      </div>
      <button type="submit" class="btn btn-primary mt-2">Simpan Perubahan</button>
    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const container = document.getElementById('detail-po-container');
  const addButton = document.querySelector('.btn-add-detail');
  const inputs = document.querySelectorAll('input[type="text"], textarea');

  $(function () {
  $('#nama_supplier').on('keydown', function (e) {
    if (e.key === 'Enter') {
      e.preventDefault(); 
      const keyword = $(this).val().trim();
      if (!keyword) return;

      $.ajax({
        url: '/admin/purchase_order/search_supplier',
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

    fetch(`/admin/purchase_order/stok_by_supplier/${supplierId}`)
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

  $(document).on('keydown', '.kode_stok_input', function (e) {
    if (e.key === 'Enter') {
      e.preventDefault();
      const input = $(this);
      const keyword = input.val().trim();
      const parent = input.closest('.position-relative');
      const resultBox = parent.find('.result_kode_stok');

      if (!keyword) return;

      $.ajax({
        url: '/admin/purchase_order/search_stok',
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

  $(document).on('click', '.stok-item', function () {
    const item = $(this);
    const parent = item.closest('.position-relative');
    const row = item.closest('.detail-po');

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
    const row = $(this).closest('.detail-po');
    row.find('.kode_stok_hidden').val('');
    row.find('.nama_barang').val('');
    row.find('.harga_total').val('');
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
      const rows = document.querySelectorAll('.detail-po');
      if (rows.length > 1) {
        e.target.closest('.detail-po').remove();
        updateTotal();
      }
    }
  });

  function updateTotal() {
    let totalQty = 0, totalHarga = 0;
    document.querySelectorAll('.detail-po').forEach(row => {
        let qty = parseFloat((row.querySelector('.kuantitas').value || '0').replace(',', '.')) || 0;
        let harga = parseFloat((row.querySelector('.harga_total').value || '0').replace(/,/g, '')) || 0;
        totalQty += qty;
        totalHarga += harga;
    });

    const ppn = totalHarga * 0.11; 
    const totalPPN = totalHarga + ppn;

    document.getElementById('total_kuantitas_po').value = totalQty.toFixed(2);
    document.getElementById('total_harga_po').value = totalHarga.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    document.getElementById('ppn_po').value = ppn.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); // Harus PPN
    document.getElementById('total_po_ppn').value = totalPPN.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); // Total + PPN
}

  function autoFill(event) {
    if (event.target.classList.contains('kode_stok')) {
      let row = event.target.closest('.detail-po');
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
    const row = event.target.closest('.detail-po');
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

  const kodeSupplierSelect = document.getElementById('kode_supplier');
  const namaSupplierInput = document.getElementById('nama_supplier');

  function updateNamaSupplier() {
    let selectedOption = kodeSupplierSelect.options[kodeSupplierSelect.selectedIndex];
    namaSupplierInput.value = selectedOption.getAttribute('data-nama') || '';
  }

  kodeSupplierSelect.addEventListener('change', updateNamaSupplier);

  function addRow() {
  const row = document.querySelector('.detail-po');
  const newRow = row.cloneNode(true);

  // Reset semua field
  newRow.querySelector('.kode_stok_input').value = '';
  newRow.querySelector('.kode_stok_input').dataset.harga = '0'; // 🛠 Tambahkan ini
  newRow.querySelector('.kode_stok_hidden').value = '';
  newRow.querySelector('.nama_barang').value = '';
  newRow.querySelector('.kuantitas').value = '';
  newRow.querySelector('.harga_total').value = '';
  newRow.querySelector('.harga_total').dataset.manual = "false";

  // Ganti tombol + jadi -
  const addButton = newRow.querySelector('.btn-add-detail');
  if (addButton) {
    const removeButton = document.createElement('button');
    removeButton.type = 'button';
    removeButton.classList.add('btn', 'btn-danger', 'btn-remove-detail');
    removeButton.innerText = '-';

    const col = document.createElement('div');
    col.classList.add('col-md-1', 'mb-3', 'd-flex', 'align-items-end');
    col.appendChild(removeButton);

    newRow.replaceChild(col, newRow.firstElementChild);
  }

  container.appendChild(newRow);
}

    // Fungsi menghapus baris jika lebih dari satu
    function removeRow(event) {
      let rows = document.querySelectorAll('.detail-po');
      if (rows.length > 1) {
        event.target.closest('.detail-po').remove();
        updateTotal();
      }
    }

    container.addEventListener('change', autoFill);
    container.addEventListener('input', updateHarga);
    container.addEventListener('input', function (event) {
    if (event.target.classList.contains('harga_total')) {
      event.target.dataset.manual = "true";
      updateTotal();
    }
  });
  container.addEventListener('click', function (e) {
  if (e.target.classList.contains('btn-add-detail')) {
    addRow();
  }
});
  document.querySelectorAll('.detail-po').forEach(row => {
    const inputHarga = row.querySelector('.kode_stok_input');
    const harga = row.querySelector('.harga_total').value.replace(/,/g, '') || "0";
    const qty = parseFloat(row.querySelector('.kuantitas').value.replace(',', '.')) || 1;
    const hargaPerSatuan = parseFloat(harga) / qty;
    inputHarga.dataset.harga = hargaPerSatuan;
  });
  document.querySelectorAll('.detail-po').forEach(row => {
    const qtyInput = row.querySelector('.kuantitas');
    const event = new Event('input');
    qtyInput.dispatchEvent(event);
  });

  $(document).on('input', '.kode_stok_input', function () {
  const cursorPos = this.selectionStart;
  this.value = this.value.toUpperCase();
  this.setSelectionRange(cursorPos, cursorPos); // biar kursor tidak lompat
});
  updateTotal(); 
});

</script>

<?= $this->endSection() ?>