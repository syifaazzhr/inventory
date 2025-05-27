<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Penerimaan Pembelian</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<a href="<?= base_url('admin/penerimaan_pembelian'); ?>" class="btn btn-outline-primary mb-3">
  <i class="ti ti-arrow-left"></i> Kembali
</a>
 
<h5 class="card-title fw-semibold mb-4 text-dark">Tambah Data Penerimaan Pembelian</h5>

<div class="card mb-4">
  <div class="card-body">
    <form action="<?= base_url('admin/penerimaan_pembelian/store'); ?>" method="post">
      <?= csrf_field(); ?>

      <!-- Nomor Bon dan Tanggal Terima -->
      <div class="row">
        <div class="col-md-4 mb-3">
          <label class="form-label">Nomor Bon</label>
          <input type="text" class="form-control" name="nomor_bon" value="<?= $nomor_bon; ?>" readonly>
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Tanggal Terima</label>
          <input type="date" class="form-control" name="tgl_terima" value="<?= date('Y-m-d'); ?>">
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
        <div class="col-md-4 mb-3">
          <label class="form-label">Nomor PO</label>
          <select id="nomor_po" class="form-control">
            <option value="">Pilih Nomor PO</option>
            <!-- Nomor PO akan diisi menggunakan JavaScript -->
          </select>
          <!-- Hidden input untuk menyimpan po_id yang akan dikirim -->
          <input type="hidden" name="po_id" id="po_id" value="">
        </div>
      </div>

      <!-- Nomor Faktur, Surat Jalan, Tanggal Kirim, Tanggal Jatuh Tempo -->
      <div class="row">
        <div class="col-md-4 mb-3">
          <label class="form-label">Nomor Faktur</label>
          <input type="text" class="form-control" name="nomor_faktur">
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Surat Jalan</label>
          <input type="text" class="form-control" name="surat_jalan">
        </div>
      </div>

      <div class="row">
        <div class="col-md-4 mb-3">
          <label class="form-label">Tanggal Kirim</label>
          <input type="date" class="form-control" name="tgl_kirim">
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Tanggal Jatuh Tempo</label>
          <input type="date" class="form-control" name="tgl_jatuh_tempo">
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Admin</label>
          <input type="text" class="form-control" name="admin_po" value="<?= auth()->user()->username; ?>" readonly>
        </div>
      </div>

      <!-- Detail Penerimaan Pembelian -->
      <div id="detail-penerimaan-container">
        <div class="row detail-penerimaan">
          <div class="col-md-1 mb-3 d-flex align-items-end">
            <button type="button" class="btn btn-primary btn-add-detail">+</button>
          </div>
          <div class="col-md-3 mb-3">
            <label class="form-label">Kode Stok</label>
            <select class="form-control stok_id" name="stok_id[]" required>
              <option value="">Pilih Kode Stok</option>
              <?php foreach ($stok_list as $stok) : ?>
                <option value="<?= $stok['stok_id']; ?>" data-nama="<?= $stok['nama_barang']; ?>" data-harga="<?= $stok['harga_beli']; ?>">
                  <?= $stok['kode_stok']; ?>
                </option>
              <?php endforeach; ?>
            </select>
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
            <input type="text" class="form-control harga_total" name="jumlah_harga_beli[]" required>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3 mb-3">
          <label class="form-label">Total Kuantitas</label>
          <input type="text" class="form-control" id="total_kuantitas_penerimaan" readonly>
        </div>
        <div class="col-md-3 mb-3">
          <label class="form-label">Total Beli</label>
          <input type="text" class="form-control" id="total_harga_penerimaan" readonly>
        </div>
        <div class="col-md-3 mb-3">
          <label class="form-label">PPN</label>
          <input type="text" class="form-control" id="ppn_terima" readonly>
        </div>
        <div class="col-md-3 mb-3">
          <label class="form-label">Jumlah Total</label>
          <input type="text" class="form-control" id="total_terima_ppn" readonly>
        </div>
      </div> 

      <button type="submit" class="btn btn-primary mt-2">Simpan</button>
    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const container = document.getElementById('detail-penerimaan-container');
  const addButton = document.querySelector('.btn-add-detail'); // Tombol tambah hanya satu di awal
  const kodeSupplierSelect = document.getElementById('kode_supplier');
  const nomorPoSelect = document.getElementById('nomor_po'); // Dropdown Nomor PO
  const namaSupplierInput = document.getElementById('nama_supplier');
  const hiddenPoIdInput = document.getElementById('po_id');
  const inputs = document.querySelectorAll('input[type="text"], textarea');
  const orderDateInput = document.querySelector('input[name="tgl_terima"]'); // Menargetkan input Tanggal Terima
  const shipDateInput = document.querySelector('input[name="tgl_kirim"]'); // Menargetkan input Tanggal Kirim
  const dueDateInput = document.querySelector('input[name="tgl_jatuh_tempo"]'); // Menargetkan input Tanggal Jatuh Tempo

  // Fungsi untuk mengupdate Tanggal Kirim dan Tanggal Jatuh Tempo
  function updateShipAndDueDate() {
    let tglTerima = orderDateInput.value; // Mengambil nilai dari Tanggal Terima
    if (tglTerima) {
      // Menggunakan Tanggal Terima untuk mengisi Tanggal Kirim dan Tanggal Jatuh Tempo
      shipDateInput.value = tglTerima;
      dueDateInput.value = tglTerima;
    }
  }

  // Panggil fungsi updateShipAndDueDate untuk mengisi Tanggal Kirim dan Tanggal Jatuh Tempo saat pertama kali halaman dimuat
  updateShipAndDueDate();

  // Menambahkan event listener pada perubahan Tanggal Terima
  orderDateInput.addEventListener('change', updateShipAndDueDate);

  $(function () {
    $('#nama_supplier').on('keydown', function (e) {
      if (e.key === 'Enter') {
        e.preventDefault(); 
        const keyword = $(this).val().trim();
        if (!keyword) return;

        console.log("Enter ditekan, cari supplier:", keyword);

        $.ajax({
          url: '/admin/penerimaan_pembelian/search_supplier',
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

      // Ambil daftar PO berdasarkan supplier_id
      fetch(`/admin/penerimaan_pembelian/po_by_supplier/${supplierId}`)
        .then(res => res.json())
        .then(data => {
          const nomorPoSelect = document.getElementById('nomor_po');
          nomorPoSelect.innerHTML = '<option value="">Pilih Nomor PO</option>';
          data.forEach(po => {
            const option = document.createElement('option');
            option.value = po.nomor_po;
            option.textContent = po.nomor_po;
            option.setAttribute('data-po-id', po.po_id);
            nomorPoSelect.appendChild(option);
          });
        });
    });

    $('#nama_supplier').on('input', function () {
      if ($(this).val().trim() === '') {
        $('#supplier_id').val('');
        $('#kode_supplier').val('');
        $('#result_supplier').hide();

        // Kosongkan dropdown Nomor PO & hidden po_id
        $('#nomor_po').html('<option value="">Pilih Nomor PO</option>');
        $('#po_id').val('');

        // Kosongkan semua kode stok di detail
        $('.stok_id').each(function () {
          $(this).html('<option value="">Pilih Kode Stok</option>');
        });

        // Kosongkan nama barang, kuantitas, dan harga total di semua baris detail
        $('.detail-penerimaan').each(function () {
          $(this).find('.nama_barang').val('');
          $(this).find('.kuantitas').val('');
          $(this).find('.harga_total').val('');
        });
      }
    });
  });

  inputs.forEach(function(input) {
    input.addEventListener('input', function() {
      this.value = this.value.toUpperCase();
    });
  });

  function updateTotal() {
    let totalQty = 0, totalHarga = 0;
    document.querySelectorAll('.detail-penerimaan').forEach(row => {
        let qty = parseFloat((row.querySelector('.kuantitas').value || '0').replace(',', '.')) || 0;
        let hargaText = row.querySelector('.harga_total').value || '0';
        let harga = parseFloat(hargaText.replaceAll(',', '').replace(' ', '').replace(' ', '')) || 0;

        totalQty += qty;
        totalHarga += harga;
    });

    const ppn = totalHarga * 0.11;
    const jumlahTotal = totalHarga + ppn;

    document.getElementById('total_kuantitas_penerimaan').value = totalQty.toFixed(2);
    document.getElementById('total_harga_penerimaan').value = totalHarga.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
    document.getElementById('ppn_terima').value = ppn.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
    document.getElementById('total_terima_ppn').value = jumlahTotal.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

  function autoFill(event) {
  if (event.target.classList.contains('stok_id')) {
    let row = event.target.closest('.detail-penerimaan');
    let selected = event.target.options[event.target.selectedIndex];

    row.querySelector('.nama_barang').value = selected.getAttribute('data-nama') || '';

    let hargaBeli = parseFloat(selected.getAttribute('data-harga')) || 0;
    let qty = parseFloat(row.querySelector('.kuantitas').value.replace(',', '.')) || 0;
    let hargaTotalField = row.querySelector('.harga_total');

    hargaTotalField.value = (hargaBeli * qty).toLocaleString('en-US', {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2
    });

    hargaTotalField.dataset.manual = "false"; 
    updateTotal();
  }
}

  function updateHarga(event) {
  if (event.target.classList.contains('kuantitas')) {
    let row = event.target.closest('.detail-penerimaan');
    let hargaTotalField = row.querySelector('.harga_total');

    let hargaBeli = parseFloat(row.querySelector('.stok_id option:checked')?.getAttribute('data-harga') || '0');
    let qty = parseFloat(event.target.value.replace(',', '.')) || 0;

    hargaTotalField.value = (hargaBeli * qty).toLocaleString('en-US', {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2
    });

    updateTotal();
  }
}

  function updateNamaSupplier() {
    let selectedOption = kodeSupplierSelect.options[kodeSupplierSelect.selectedIndex];
    if (selectedOption) {
      namaSupplierInput.value = selectedOption.getAttribute('data-nama') || '';
    }
  }

  function updatePOBySupplier() {
    const supplierId = kodeSupplierSelect.value;

    nomorPoSelect.innerHTML = '<option value="">Pilih Nomor PO</option>';

    if (supplierId && <?= json_encode($po_list); ?>[supplierId]) {
        const poList = <?= json_encode($po_list); ?>[supplierId];
        
        poList.forEach(po => {
            let option = document.createElement('option');
            option.value = po.nomor_po;  // Tampilkan nomor PO di dropdown
            option.setAttribute('data-po-id', po.po_id);  // Menyimpan po_id di atribut
            option.textContent = po.nomor_po;
            nomorPoSelect.appendChild(option);
        });
    }
  }

  function setPoId() {
    let selectedOption = nomorPoSelect.options[nomorPoSelect.selectedIndex];
    if (selectedOption) {
      let poId = selectedOption.getAttribute('data-po-id');
      hiddenPoIdInput.value = poId;  // Mengisi hidden input dengan po_id
    }
  }

  kodeSupplierSelect.addEventListener('change', updatePOBySupplier);
  nomorPoSelect.addEventListener('change', setPoId);

  if (kodeSupplierSelect.value) {
    updatePOBySupplier();
  }

  const namaSupplierSelect = document.getElementById('nama_supplier');
  const kodeSupplierInput = document.getElementById('kode_supplier');

  function updateKodeSupplier() {
    const selectedOption = namaSupplierSelect.options[namaSupplierSelect.selectedIndex];
    kodeSupplierInput.value = selectedOption.getAttribute('data-kode') || '';
  }

  function addRow() {
    let row = document.querySelector('.detail-penerimaan');
    let newRow = row.cloneNode(true);

    // Reset input pada elemen baru
    newRow.querySelector('.kode_stok').value = '';
    newRow.querySelector('.nama_barang').value = '';
    newRow.querySelector('.kuantitas').value = '';
    newRow.querySelector('.harga_total').dataset.manual = "false";

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
    updateTotal();
  }

  function removeRow(event) {
    let rows = document.querySelectorAll('.detail-penerimaan');
    if (rows.length > 1) {
      event.target.closest('.detail-penerimaan').remove();
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
            option.textContent = stok.kode_stok;
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

  updateTotal();

  kodeSupplierSelect.addEventListener('change', updatePOBySupplier);
  nomorPoSelect.addEventListener('change', setPoId);
  container.addEventListener('change', autoFill);
  container.addEventListener('input', updateHarga);
  container.addEventListener('input', function (event) {
    if (event.target.classList.contains('harga_total')) {
      event.target.dataset.manual = "true";
      updateTotal();
    }
    if (event.target.classList.contains('kuantitas')) {
    updateTotal(); // Tambahkan ini
  }
  });
  addButton.addEventListener('click', addRow);
  if (kodeSupplierSelect.value) {
    updatePOBySupplier();
  }

  document.getElementById('nomor_po').addEventListener('change', function () {
  const selected = this.options[this.selectedIndex];
  const poId = selected.getAttribute('data-po-id');
  document.getElementById('po_id').value = poId;

  if (!poId) return;

  fetch(`/admin/penerimaan_pembelian/stok_by_po/${poId}`)
    .then(response => response.json())
    .then(data => {
      const container = document.getElementById('detail-penerimaan-container');

      // Kosongkan semua baris sebelumnya
      container.innerHTML = '';

      data.forEach(stok => {
        const newRow = document.createElement('div');
        newRow.classList.add('row', 'detail-penerimaan');

        newRow.innerHTML = `
          <div class="col-md-1 mb-3 d-flex align-items-end">
            <button type="button" class="btn btn-danger btn-remove-detail">-</button>
          </div>
          <div class="col-md-3 mb-3">
            <label class="form-label">Kode Stok</label>
            <select class="form-control stok_id" name="stok_id[]" required>
              <option value="${stok.stok_id}" data-nama="${stok.nama_barang}" data-harga="${stok.harga_beli}" selected>
                ${stok.kode_stok}
              </option>
            </select>
          </div>
          <div class="col-md-3 mb-3">
            <label class="form-label">Nama Barang</label>
            <input type="text" class="form-control nama_barang" value="${stok.nama_barang}" readonly>
          </div>
          <div class="col-md-2 mb-3">
            <label class="form-label">Kuantitas per Barang</label>
            <input type="text" class="form-control kuantitas" name="total_kuantitas[]" value="${stok.qty_detpo}" required>
          </div>
          <div class="col-md-3 mb-3">
            <label class="form-label">Harga Beli per Barang</label>
            <input type="text" class="form-control harga_total" name="jumlah_harga_beli[]" value="${(stok.qty_detpo * stok.harga_beli).toLocaleString('en-US', {minimumFractionDigits:2})}" required>
          </div>
        `;

        newRow.querySelector('.btn-remove-detail').addEventListener('click', function () {
          newRow.remove();
          updateTotal();
        });

        container.appendChild(newRow);
      });

      updateTotal();
    });
});
});
</script>

<?= $this->endSection() ?>