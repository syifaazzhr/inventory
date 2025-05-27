<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<title>Purchase Order</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
$from = service('request')->getGet('from');
$backUrl = match ($from) {
    'empty'  => base_url('admin/dashboard/empty_alert'),
    default  => base_url('admin/purchase_order'),
};
?>

<a href="<?= $backUrl; ?>" class="btn btn-outline-primary mb-3">
  <i class="ti ti-arrow-left"></i> Kembali
</a>

<h5 class="card-title fw-semibold mb-4 text-dark">Tambah Data Purchase Order</h5>

<div class="card mb-4">
  <div class="card-body">
    <form action="<?= base_url('admin/purchase_order/store'); ?>" method="post">
      <?= csrf_field(); ?>
      <div class="row">
        <div class="col-md-4 mb-3">
          <label class="form-label">Nomor PO</label>
          <input type="text" class="form-control" name="nomor_po" value="<?= $nomor_po; ?>" readonly>
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Tanggal Order</label>
          <input type="date" class="form-control" name="tgl_order" value="<?= date('Y-m-d'); ?>">
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Batas Tanggal Kirim</label>
          <input type="date" class="form-control" name="max_tgl_kirim" value="<?= date('Y-m-d'); ?>">
        </div>
      </div>
      <div class="row">
        <div class="col-md-4 mb-3">
          <label class="form-label">Nomor Worksheet</label>
          <input type="text" class="form-control" name="nomor_ws">
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Nomor Artikel</label>
          <input type="text" class="form-control" name="nomor_art">
        </div>
      </div>
      <div class="row">
        <div class="col-md-4 mb-3">
          <label class="form-label">Nama Supplier</label>
            <div class="position-relative">
              <input type="text" class="form-control" id="nama_supplier" autocomplete="off" placeholder="Ketik nama supplier lalu tekan Enter" required>
              <input type="hidden" id="supplier_id" name="supplier_id">
              <div id="result_supplier" class="dropdown-menu w-100" style="display:none; max-height: 200px; overflow-y: auto; position: absolute; z-index: 1000;"></div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Kode Supplier</label>
          <input type="text" class="form-control" id="kode_supplier" readonly>
        </div>
      </div>
      <div class="row">
        <div class="col-md-8 mb-3">
          <label class="form-label">Keterangan</label>
          <textarea class="form-control" name="ket_po" rows="1"></textarea>
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Admin</label>
          <input type="text" class="form-control" name="admin_po" value="<?= auth()->user()->username; ?>" readonly>
        </div>
      </div>
      <div id="detail-po-container">
        <div class="row detail-po">
          <div class="col-md-1 mb-3 d-flex align-items-end">
            <button type="button" class="btn btn-primary btn-add-detail">+</button>
          </div>
          <div class="col-md-3 mb-3">
            <label class="form-label">Kode Stok</label>
            <div class="position-relative">
              <input type="text" class="form-control kode_stok_input" autocomplete="off" required placeholder="Ketik kode/nama lalu tekan Enter">
              <input type="hidden" class="kode_stok_hidden" name="kode_stok[]">
              <div class="dropdown-menu result_kode_stok w-100" style="display:none; max-height: 200px; overflow-y: auto; position: absolute; z-index: 1000;"></div>
            </div>
          </div>
          <div class="col-md-3 mb-3">
            <label class="form-label">Nama Barang</label>
            <input type="text" class="form-control nama_barang" readonly>
          </div>
          <div class="col-md-2 mb-3">
            <label class="form-label">Kuantitas per Barang</label>
            <input type="text" class="form-control kuantitas" name="total_kuantitas[]" required>
          </div>
          <div class="col-md-3 mb-3">
            <label class="form-label">Harga Beli per Barang</label>
            <input type="text" class="form-control harga_total" name="jumlah_harga_beli[]">
          </div>
        </div>
      </div> 
      <div class="row">
        <div class="col-md-3 mb-3">
          <label class="form-label">Total Kuantitas</label>
          <input type="text" class="form-control" id="total_kuantitas_po" readonly>
        </div>
        <div class="col-md-3 mb-3">
          <label class="form-label">Total Beli</label>
          <input type="text" class="form-control" id="total_harga_po" readonly>
        </div>
        <div class="col-md-3 mb-3">
          <label class="form-label">PPN</label>
          <input type="text" class="form-control" id="ppn_po" readonly>
        </div>
        <div class="col-md-3 mb-3">
          <label class="form-label">Jumlah Total</label>
          <input type="text" class="form-control" id="total_po_ppn" readonly>
        </div>
      </div>
      <button type="submit" class="btn btn-primary mt-2">Simpan</button>
    </form>
  </div>
</div> 

<script>
document.addEventListener('DOMContentLoaded', function() {
  const container = document.getElementById('detail-po-container');
  const addButton = document.querySelector('.btn-add-detail');
  const inputs = document.querySelectorAll('input[type="text"], textarea');
  const orderDateInput = document.querySelector('input[name="tgl_order"]'); // Mengambil input Tanggal Order
  const shipDateInput = document.querySelector('input[name="max_tgl_kirim"]'); // Mengambil input Tanggal Kirim

  // Fungsi untuk mengupdate Tanggal Kirim
  function updateShipDate() {
    let tglOrder = orderDateInput.value; // Ambil nilai dari Tanggal Order
    if (tglOrder) {
      // Menghitung 30 hari setelah Tanggal Order
      let orderDate = new Date(tglOrder);
      orderDate.setDate(orderDate.getDate() + 30); // Menambahkan 30 hari

      // Format tanggal ke format YYYY-MM-DD
      let day = ("0" + orderDate.getDate()).slice(-2);
      let month = ("0" + (orderDate.getMonth() + 1)).slice(-2); // Bulan dimulai dari 0
      let year = orderDate.getFullYear();
      let formattedDate = `${year}-${month}-${day}`;

      // Set nilai Tanggal Kirim dengan tanggal yang telah dihitung
      shipDateInput.value = formattedDate;
    }
  }

  // Panggil fungsi updateShipDate untuk mengisi Tanggal Kirim saat pertama kali halaman dimuat
  updateShipDate();

  // Menambahkan event listener pada perubahan Tanggal Order
  orderDateInput.addEventListener('change', updateShipDate);
  
  $(function () {
    $('#nama_supplier').on('keydown', function (e) {
      if (e.key === 'Enter') {
        e.preventDefault(); 
        const keyword = $(this).val().trim();
        if (!keyword) return;

        console.log("Enter ditekan, cari supplier:", keyword);

        $.ajax({
          url: '/admin/purchase_order/search_supplier',
          method: 'GET',
          data: { q: keyword },
          dataType: 'json',
          success: function (data) {
            let html = '';
            if (data.length === 0) {
              html = '<div class="dropdown-item disabled">Nama Supplier tidak ditemukan</div>';
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
          },
          error: function (xhr, status, error) {
            console.error('AJAX Error:', status, error);
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
              option.textContent = `${stok.kode_stok} - ${stok.nama_barang}`;
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

      console.log("Enter ditekan, cari stok:", keyword);

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
        },
        error: function (xhr, status, error) {
          console.error('AJAX Error:', status, error);
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

    const inputKode = parent.find('.kode_stok_input');
    inputKode.val(kode);
    inputKode.data('harga', harga); 
    parent.find('.kode_stok_hidden').val(stokId);
    parent.find('.result_kode_stok').hide();

    row.find('.nama_barang').val(nama);

    const qtyField = row.find('.kuantitas');
    const qty = parseFloat(qtyField.val().replace(',', '.')) || 1;

    const hargaTotalField = row.find('.harga_total');
    hargaTotalField.val((harga * qty).toLocaleString('en-US', {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2
    }));
    hargaTotalField.data('manual', false);

    updateTotal();
  });

  $(document).on('input', '.kode_stok_input', function () {
    const parent = $(this).closest('.position-relative');
    const row = $(this).closest('.detail-po');

    parent.find('.kode_stok_hidden').val('');
    row.find('.nama_barang').val('');
    row.find('.harga_total').val('');
  });

  inputs.forEach(function(input) {
    input.addEventListener('input', function() {
      this.value = this.value.toUpperCase();
    });
  });

  function updateTotal() {
      let totalQty = 0, totalHarga = 0;
      document.querySelectorAll('.detail-po').forEach(row => {
          let qty = parseFloat((row.querySelector('.kuantitas').value || '0').replace(',', '.')) || 0;
          let hargaText = row.querySelector('.harga_total').value || '0';
          let harga = parseFloat(hargaText.replace(/,/g, '')) || 0;
          totalQty += qty;
          totalHarga += harga;
      });

      const ppn = totalHarga * 0.11;
      const totalPPN = totalHarga + ppn;

      document.getElementById('total_kuantitas_po').value = totalQty.toFixed(2);
      document.getElementById('total_harga_po').value = totalHarga.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
      document.getElementById('ppn_po').value = ppn.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); 
      document.getElementById('total_po_ppn').value = totalPPN.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  }

  function autoFill(event) {
    if (event.target.classList.contains('kode_stok')) {
      let row = event.target.closest('.detail-po');
      let selected = event.target.options[event.target.selectedIndex];

      row.querySelector('.nama_barang').value = selected.getAttribute('data-nama') || '';

      let hargaBeli = parseFloat(selected.getAttribute('data-harga').replace(',', '.')) || 0;
      let qty = parseFloat(row.querySelector('.kuantitas').value.replace(',', '.')) || 1;
      let hargaTotalField = row.querySelector('.harga_total');

      if (hargaTotalField.dataset.manual !== "true") {
        hargaTotalField.value = (hargaBeli * qty).toLocaleString('en-US', {
          minimumFractionDigits: 2,
          maximumFractionDigits: 2
        });
      }

      hargaTotalField.dataset.manual = "false";

      updateTotal();
    }
  }

  function updateHarga(event) {
    if (event.target.classList.contains('kuantitas')) {
      const row = $(event.target).closest('.detail-po');
      const hargaTotalField = row.find('.harga_total');
      const kodeStokInput = row.find('.kode_stok_input');
      
      const hargaSatuan = parseFloat(kodeStokInput.data('harga')) || 0;
      const qty = parseFloat(event.target.value.replace(',', '.')) || 0;

      if (hargaTotalField.data('manual') !== true) {
        const total = hargaSatuan * qty;
        hargaTotalField.val(total.toLocaleString('en-US', {
          minimumFractionDigits: 2,
          maximumFractionDigits: 2
        }));
      }

      updateTotal();
    }
  }

  const namaSupplierSelect = document.getElementById('nama_supplier');
  const kodeSupplierInput = document.getElementById('kode_supplier');

  function updateKodeSupplier() {
    const selectedOption = namaSupplierSelect.options[namaSupplierSelect.selectedIndex];
    kodeSupplierInput.value = selectedOption.getAttribute('data-kode') || '';
  }

  function addRow() {
    let row = document.querySelector('.detail-po');
    let newRow = row.cloneNode(true);

    // Reset input di row baru
    newRow.querySelector('.kode_stok_input').value = '';
    newRow.querySelector('.kode_stok_hidden').value = '';
    newRow.querySelector('.nama_barang').value = '';
    newRow.querySelector('.kuantitas').value = '';
    newRow.querySelector('.harga_total').value = '';
    newRow.querySelector('.harga_total').dataset.manual = "false";

    // Hapus tombol +
    let addButton = newRow.querySelector('.btn-add-detail');
    if (addButton) addButton.remove();

    // Tambahkan tombol -
    let removeButton = document.createElement('button');
    removeButton.type = 'button';
    removeButton.classList.add('btn', 'btn-danger', 'btn-remove-detail');
    removeButton.textContent = '-';

    removeButton.addEventListener('click', function () {
      newRow.remove();
      updateTotal();
    });

    // Buat kolom tombol -
    let col = document.createElement('div');
    col.classList.add('col-md-1', 'mb-3', 'd-flex', 'align-items-end');
    col.appendChild(removeButton);

    // Ganti kolom pertama dengan tombol -
    newRow.replaceChild(col, newRow.firstElementChild);

    newRow.querySelectorAll('input[type="text"]').forEach(function(input) {
        input.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
    });

    container.appendChild(newRow);
  }

  function removeRow(event) {
    let rows = document.querySelectorAll('.detail-po');
    if (rows.length > 1) {
      event.target.closest('.detail-po').remove();
      updateTotal();
    }
  }

  $('#nama_supplier').on('autocompleteselect', function (e, ui) {
    const supplierId = ui.item.supplier_id;
    fetch(`/admin/purchase_order/stok_by_supplier/${supplierId}`)
      .then(res => res.json())
      .then(data => {
        document.querySelectorAll('.kode_stok').forEach(select => {
          select.innerHTML = '<option value="">Pilih Kode Stok</option>';
          data.forEach(stok => {
            const option = document.createElement('option');
            option.value = stok.stok_id;
            option.textContent = `${stok.kode_stok} - ${stok.nama_barang}`;
            option.setAttribute('data-nama', stok.nama_barang);
            option.setAttribute('data-harga', stok.harga_beli);
            select.appendChild(option);
          });

          const row = select.closest('.detail-po');
          row.querySelector('.nama_barang').value = '';
          row.querySelector('.harga_total').value = '';
          row.querySelector('.kuantitas').value = '';
        });
      });
  });

  container.addEventListener('change', autoFill);
  container.addEventListener('input', updateHarga);
  container.addEventListener('input', function (event) {
    if (event.target.classList.contains('harga_total')) {
      event.target.dataset.manual = "true";
      updateTotal();
    }
    if (event.target.classList.contains('kuantitas') || event.target.classList.contains('harga_total')) {
      updateTotal();
    }
  });
  addButton.addEventListener('click', addRow);
  document.querySelectorAll('.harga_total').forEach(function(field) {
    field.dataset.manual = "false";
  });
});

// document.addEventListener('DOMContentLoaded', function() {
//   const orderDateInput = document.querySelector('input[name="tgl_order"]'); // Mengambil input Tanggal Order
//   const shipDateInput = document.querySelector('input[name="max_tgl_kirim"]'); // Mengambil input Tanggal Kirim

//   // Fungsi untuk mengupdate Tanggal Kirim
//   function updateShipDate() {
//     let tglOrder = orderDateInput.value; // Ambil nilai dari Tanggal Order
//     if (tglOrder) {
//       // Menghitung 30 hari setelah Tanggal Order
//       let orderDate = new Date(tglOrder);
//       orderDate.setDate(orderDate.getDate() + 30); // Menambahkan 30 hari

//       // Format tanggal ke format YYYY-MM-DD
//       let day = ("0" + orderDate.getDate()).slice(-2);
//       let month = ("0" + (orderDate.getMonth() + 1)).slice(-2); // Bulan dimulai dari 0
//       let year = orderDate.getFullYear();
//       let formattedDate = `${year}-${month}-${day}`;

//       // Set nilai Tanggal Kirim dengan tanggal yang telah dihitung
//       shipDateInput.value = formattedDate;
//     }
//   }

//   // Panggil fungsi updateShipDate untuk mengisi Tanggal Kirim saat pertama kali halaman dimuat
//   updateShipDate();

//   // Menambahkan event listener pada perubahan Tanggal Order
//   orderDateInput.addEventListener('change', updateShipDate);
// });
</script>

<?= $this->endSection() ?>