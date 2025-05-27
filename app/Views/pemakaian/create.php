<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Pemakaian Bahan</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<a href="<?= base_url('admin/pemakaian'); ?>" class="btn btn-outline-primary mb-3">
  <i class="ti ti-arrow-left"></i> Kembali
</a>

<h5 class="card-title fw-semibold mb-4 text-dark">Tambah Data Pemakaian Bahan</h5>

<div class="card mb-4">
  <div class="card-body">
    <form action="<?= base_url('admin/pemakaian/store'); ?>" method="post">
      <?= csrf_field(); ?>

      <div class="row">
        <div class="col-md-4 mb-3">
          <label class="form-label">Nomor Pakai</label>
          <input type="text" class="form-control" name="nomor_pakai" value="<?= $nomor_pakai; ?>" readonly>
          <input type="hidden" name="jenis" value="<?= esc($_GET['jenis'] ?? 'Aksesoris'); ?>">
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Tanggal Keluar</label>
          <input type="date" class="form-control" name="tgl_keluar" value="<?= date('Y-m-d'); ?>">
        </div>
      </div>

      <div class="row">
        <div class="col-md-4 mb-3">
          <label class="form-label">Nomor Worksheet Warna</label>
          <input type="text" class="form-control" name="nomor_ws_warna_pakai">
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Nomor Worksheet</label>
          <input type="text" class="form-control" name="nomor_ws_pakai">
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Nomor Artikel</label>
          <input type="text" class="form-control" name="nomor_art_pakai">
        </div>
      </div>

      <div class="row">
        <div class="col-md-8 mb-3">
          <label class="form-label">Keterangan</label>
          <textarea class="form-control" name="ket_pakai" rows="1"></textarea>
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Admin</label>
          <input type="text" class="form-control" name="admin_pakai" value="<?= auth()->user()->username; ?>" readonly>
        </div>
      </div>

      <div id="detail-pemakaian-container">
        <div class="row detail-pemakaian">
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
          <div class="col-md-3 mb-3">
            <label class="form-label">Kuantitas per Barang</label>
            <input type="text" class="form-control kuantitas" name="total_kuantitas[]" required>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-4 mb-3">
          <label class="form-label">Total Kuantitas</label>
          <input type="text" class="form-control" id="total_kuantitas_pakai" readonly>
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Nama Penerima</label>
          <input type="text" class="form-control" name="nama_penerima">
        </div>
      </div>

      <button type="submit" class="btn btn-primary mt-2">Simpan</button>
    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const container = document.getElementById('detail-pemakaian-container');
  const addButton = document.querySelector('.btn-add-detail'); 
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
          url: '/admin/pemakaian/search_supplier',
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
 
      fetch(`/admin/pemakaian/stok_by_supplier/${supplierId}`)
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
        url: '/admin/pemakaian/search_stok',
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
    const row = item.closest('.detail-pemakaian');

    const kode = item.data('kode'); // Ini untuk isi field dengan kode stok yang benar
    const id = item.data('id');     // Ini untuk hidden input (stok_id)
    const nama = item.data('nama');
    const harga = parseFloat(item.data('harga')) || 0;

    const inputKode = parent.find('.kode_stok_input');
    inputKode.val(kode);
    parent.find('.kode_stok_hidden').val(id);
    inputKode.data('harga', harga); // simpan harga satuan
    // parent.find('.kode_stok_hidden').val(kode);
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
    const row = $(this).closest('.detail-pemakaian');

    parent.find('.kode_stok_hidden').val('');
    row.find('.nama_barang').val('');
    row.find('.harga_total').val('');
  });

  document.addEventListener('input', function(e) {
    if (e.target.matches('input[type="text"], textarea')) {
      e.target.value = e.target.value.toUpperCase();
    }
  });

  function updateTotal() {
    let totalQty = 0, totalHarga = 0;
    document.querySelectorAll('.detail-pemakaian').forEach(row => {
      let qty = parseFloat((row.querySelector('.kuantitas').value || '0').replace(',', '.')) || 0;
      totalQty += qty;
    });
    document.getElementById('total_kuantitas_pakai').value = totalQty.toFixed(2);
  }

  function autoFill(event) {
    if (event.target.classList.contains('kode_stok')) {
      let row = event.target.closest('.detail-pemakaian');
      let selected = event.target.options[event.target.selectedIndex];
      row.querySelector('.nama_barang').value = selected.getAttribute('data-nama') || '';
      let qty = parseFloat(row.querySelector('.kuantitas').value.replace(',', '.')) || 1;

      updateTotal();
    }
  }

  function addRow() {
    let row = document.querySelector('.detail-pemakaian');
    let newRow = row.cloneNode(true);

    // Reset input fields
    newRow.querySelector('.kode_stok_input').value = '';
    newRow.querySelector('.kode_stok_hidden').value = '';
    newRow.querySelector('.nama_barang').value = '';
    newRow.querySelector('.kuantitas').value = '';

    // Remove existing add button and add remove button if not exist
    let addButton = newRow.querySelector('.btn-add-detail');
    if (addButton) addButton.remove();

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
}

  function removeRow(event) {
    let rows = document.querySelectorAll('.detail-pemakaian');
    if (rows.length > 1) {
      event.target.closest('.detail-pemakaian').remove();
      updateTotal();
    }
  }

  container.addEventListener('change', autoFill);
  container.addEventListener('input', function (event) {
    if (event.target.classList.contains('kuantitas') || event.target.classList.contains('harga_total')) {
      updateTotal();
    }
  });
  container.addEventListener('click', function (e) {
    if (e.target.classList.contains('btn-add-detail')) {
        addRow();
    }
    if (e.target.classList.contains('btn-remove-detail')) {
        removeRow(e);
    }
});
});
</script>

<?= $this->endSection() ?>