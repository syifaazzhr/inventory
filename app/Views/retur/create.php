<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Retur Pembelian</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<a href="<?= base_url('admin/retur'); ?>" class="btn btn-outline-primary mb-3">
  <i class="ti ti-arrow-left"></i> Kembali
</a>

<h5 class="card-title fw-semibold mb-4 text-dark">Tambah Data Retur Pembelian</h5>

<div class="card mb-4">
  <div class="card-body">
    <form action="<?= base_url('admin/retur/store'); ?>" method="post">
      <?= csrf_field(); ?>
      <div class="row">
        <div class="col-md-4 mb-3">
          <label class="form-label">Nomor Retur</label>
          <input type="text" class="form-control" name="nomor_retur" value="<?= $nomor_retur; ?>" readonly>
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Tanggal Retur</label>
          <input type="date" class="form-control" name="tgl_retur" value="<?= date('Y-m-d'); ?>">
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
          <textarea class="form-control" name="ket_retur" rows="1"></textarea>
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Admin</label>
          <input type="text" class="form-control" name="admin_retur" value="<?= auth()->user()->username; ?>" readonly>
        </div>
      </div>
      <div id="detail-retur-container">
        <div class="row detail-retur">
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
          <input type="text" class="form-control" id="total_kuantitas_retur" readonly>
        </div>
        <div class="col-md-3 mb-3">
          <label class="form-label">Total Beli</label>
          <input type="text" class="form-control" id="total_harga_retur" readonly>
        </div>
        <div class="col-md-3 mb-3">
          <label class="form-label">PPN</label>
          <input type="text" class="form-control" id="ppn_retur" readonly>
        </div>
        <div class="col-md-3 mb-3">
          <label class="form-label">Jumlah Total</label>
          <input type="text" class="form-control" id="total_harga_retur_ppn" readonly>
        </div>
      </div>

      <button type="submit" class="btn btn-primary mt-2">Simpan</button>
    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const container = document.getElementById('detail-retur-container');
  // const addButton = document.querySelector('.btn-add-detail');
  const namaSupplierInput = document.getElementById('nama_supplier');
  const supplierIdInput = document.getElementById('supplier_id');
  const kodeSupplierInput = document.getElementById('kode_supplier');
  const inputs = document.querySelectorAll('input[type="text"], textarea');

  $(function () {
    $('#nama_supplier').on('keydown', function (e) {
      if (e.key === 'Enter') {
        e.preventDefault(); 
        const keyword = $(this).val().trim();
        if (!keyword) return;

        console.log("Enter ditekan, cari supplier:", keyword);

        $.ajax({
          url: '/admin/retur/search_supplier',
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
                          data-id="${sup.supplier_id}"
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

      fetch(`/admin/retur/stok_by_supplier/${supplierId}`)
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
    const row = item.closest('.detail-retur');

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

    const hargaTotal = harga * qty;

    row.find('.harga_total').val(hargaTotal.toLocaleString('en-US', {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2
    }));

    row.find('.harga_total_raw').val(hargaTotal); 

    updateTotal();
  });

  $(document).on('input', '.kode_stok_input', function () {
  const parent = $(this).closest('.position-relative');
  const row = $(this).closest('.detail-retur');

  parent.find('.kode_stok_hidden').val('');
  row.find('.nama_barang').val('');
  row.find('.kuantitas').val(''); 
  row.find('.harga_total').val('');

  updateTotal(); 
});
 
  inputs.forEach(function(input) {
    input.addEventListener('input', function() {
      this.value = this.value.toUpperCase();
    });
  });

  function updateTotal() {
    let totalQty = 0, totalHarga = 0;
    document.querySelectorAll('.detail-retur').forEach(row => {
      let qty = parseFloat((row.querySelector('.kuantitas').value || '0').replace(',', '.')) || 0;
      let hargaText = row.querySelector('.harga_total').value || '0';
      let harga = parseFloat(hargaText.replaceAll(',', '').replace(' ', '').replace(' ', '')) || 0;
      totalQty += qty;
      totalHarga += harga;
    });
 
    const ppn = totalHarga * 0.11;
    const totalPPN = totalHarga + ppn;

    document.getElementById('total_kuantitas_retur').value = totalQty.toFixed(2);
    document.getElementById('total_harga_retur').value = totalHarga.toLocaleString('en-US', {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2
    });
    document.getElementById('ppn_retur').value = ppn.toLocaleString('en-US', {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2
    });
    document.getElementById('total_harga_retur_ppn').value = totalPPN.toLocaleString('en-US', {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2
    });
  }

  function autoFill(event) {
    if (event.target.classList.contains('kode_stok')) {
      let row = event.target.closest('.detail-retur');
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
      const row = $(event.target).closest('.detail-retur');
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

//   function addRow() {
//   let row = document.querySelector('.detail-retur');
//   let newRow = row.cloneNode(true);

//   newRow.querySelector('.kode_stok_input').value = '';
//   newRow.querySelector('.kode_stok_hidden').value = '';
//   newRow.querySelector('.nama_barang').value = '';
//   newRow.querySelector('.kuantitas').value = '';
//   newRow.querySelector('.harga_total').value = '';

//   let addButton = newRow.querySelector('.btn-add-detail');
//   if (addButton) addButton.remove();

//   let removeButton = newRow.querySelector('.btn-remove-detail');
//   if (!removeButton) {
//     removeButton = document.createElement('button');
//     removeButton.type = 'button';
//     removeButton.classList.add('btn', 'btn-danger', 'btn-remove-detail');
//     removeButton.innerText = '-';
    
//     let col = document.createElement('div');
//     col.classList.add('col-md-1', 'mb-3', 'd-flex', 'align-items-end');
//     col.appendChild(removeButton);

//     newRow.replaceChild(col, newRow.firstElementChild);
//   }

//   container.appendChild(newRow);
// }

function addRow() {
    let row = document.querySelector('.detail-retur');
    let newRow = row.cloneNode(true);

    newRow.querySelector('.kode_stok_input').value = '';
    newRow.querySelector('.kode_stok_hidden').value = '';
    newRow.querySelector('.nama_barang').value = '';
    newRow.querySelector('.kuantitas').value = '';
    newRow.querySelector('.harga_total').value = '';

    let addButton = newRow.querySelector('.btn-add-detail');
    if (addButton) addButton.remove();

    let removeButton = newRow.querySelector('.btn-remove-detail');
    if (!removeButton) {
        removeButton = document.createElement('button');
        removeButton.type = 'button';
        removeButton.classList.add('btn', 'btn-danger', 'btn-remove-detail');
        removeButton.innerText = '-';

        let col = document.createElement('div');
        col.classList.add('col-md-1', 'mb-3', 'd-flex', 'align-items-end');
        col.appendChild(removeButton);

        newRow.replaceChild(col, newRow.firstElementChild);
    }

    container.appendChild(newRow);

    newRow.querySelectorAll('input[type="text"], textarea').forEach(function(input) {
        input.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
    });
}

  function removeRow(event) {
    let rows = document.querySelectorAll('.detail-retur');
    if (rows.length > 1) {
      event.target.closest('.detail-retur').remove();
      updateTotal();
    }
  }

  container.addEventListener('change', autoFill);
  container.addEventListener('input', updateHarga);
  container.addEventListener('input', function (e) {
    if (e.target.classList.contains('harga_total')) {
      const row = e.target.closest('.detail-retur');
      const val = e.target.value.replace(/[^0-9.]/g, '').trim();
      const parsed = parseFloat(val) || 0;

      updateTotal();
    }
  });
  container.addEventListener('click', function(event) {
  if (event.target.classList.contains('btn-add-detail')) {
    addRow();
  } else if (event.target.classList.contains('btn-remove-detail')) {
    removeRow(event);
  }
});
  container.addEventListener('input', function (e) {
    if (e.target.classList.contains('harga_total')) {
      let row = e.target.closest('.detail-retur');
      let hargaText = e.target.value.replace(/,/g, '').trim();
      let harga = parseFloat(hargaText) || 0;

      row.querySelector('.harga_total_raw').value = harga;

      updateTotal();
    }
  });
});
</script>

<?= $this->endSection() ?>