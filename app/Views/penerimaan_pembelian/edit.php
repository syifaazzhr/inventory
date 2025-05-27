<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Penerimaan Pembelian</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<a href="<?= base_url('admin/penerimaan_pembelian/' . $penerimaan['terima_id']); ?>" class="btn btn-outline-primary mb-3">
  <i class="ti ti-arrow-left"></i> Kembali
</a>

<h5 class="card-title fw-semibold mb-4 text-dark">Ubah Data Penerimaan Pembelian</h5>

<div class="card mb-4">
  <div class="card-body">
    <form action="<?= base_url('admin/penerimaan_pembelian/' . $penerimaan['terima_id'] . '/update'); ?>" method="post">
      <?= csrf_field(); ?>

      <!-- Nomor Bon dan Tanggal Terima -->
      <div class="row">
        <div class="col-md-4 mb-3">
          <label class="form-label">Nomor Bon</label>
          <input type="text" class="form-control" name="nomor_bon" value="<?= $penerimaan['nomor_bon']; ?>" readonly>
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Tanggal Terima</label>
          <input type="date" class="form-control" name="tgl_terima" value="<?= $penerimaan['tgl_terima']; ?>">
        </div>
      </div>  

      <!-- Kode Supplier, Nama Supplier, Nomor PO -->
      <div class="row">
        <div class="col-md-4 mb-3">
          <label class="form-label">Nama Supplier</label>
          <div class="position-relative">
            <input type="text" class="form-control" id="nama_supplier" autocomplete="off" placeholder="Ketik nama supplier lalu tekan Enter" value="<?= $selected_supplier['nama_supplier'] ?? ''; ?>" required>
            <input type="hidden" id="supplier_id" name="supplier_id" value="<?= $penerimaan['supplier_id']; ?>">
            <div id="result_supplier" class="dropdown-menu w-100" style="display:none; max-height: 200px; overflow-y: auto; position: absolute; z-index: 1000;"></div>
          </div>
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Kode Supplier</label>
          <input type="text" class="form-control" id="kode_supplier" value="<?= $selected_supplier['kode_supplier'] ?? ''; ?>" readonly>
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Nomor PO</label>
          <select id="nomor_po" class="form-control" required>
            <option value="">Pilih Nomor PO</option>
            <?php foreach ($po_list as $po): ?>
                <?php if ($po['supplier_id'] == $penerimaan['supplier_id']): ?>
                <option value="<?= $po['po_id']; ?>" <?= $po['po_id'] == $penerimaan['po_id'] ? 'selected' : ''; ?>>
                    <?= $po['nomor_po']; ?>
                </option>
                <?php endif; ?>
            <?php endforeach; ?>
            </select>
          <input type="hidden" name="po_id" id="po_id" value="<?= $selected_po_id ?? ''; ?>">
        </div>
      </div>

      <!-- Nomor Faktur, Surat Jalan, Tanggal Kirim, Tanggal Jatuh Tempo -->
      <div class="row">
        <div class="col-md-4 mb-3">
          <label class="form-label">Nomor Faktur</label>
          <input type="text" class="form-control" name="nomor_faktur" value="<?= $penerimaan['nomor_faktur']; ?>">
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Surat Jalan</label>
          <input type="text" class="form-control" name="surat_jalan" value="<?= $penerimaan['surat_jalan']; ?>">
        </div>
      </div>

      <div class="row">
        <div class="col-md-4 mb-3">
          <label class="form-label">Tanggal Kirim</label>
          <input type="date" class="form-control" name="tgl_kirim" value="<?= $penerimaan['tgl_kirim']; ?>">
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Tanggal Jatuh Tempo</label>
          <input type="date" class="form-control" name="tgl_jatuh_tempo" value="<?= $penerimaan['tgl_jatuh_tempo']; ?>">
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Admin</label>
          <input type="text" class="form-control" name="admin_terima" value="<?= $penerimaan['admin_terima']; ?>" readonly>
        </div>
      </div>

      <!-- Detail Penerimaan Pembelian -->
      <div id="detail-penerimaan-container">
      <?php foreach ($detail_penerimaan as $index => $detail) : ?>
          <div class="row detail-penerimaan">
            <div class="col-md-1 mb-3 d-flex align-items-end">
              <?php if ($index === 0): ?> 
                <button type="button" class="btn btn-primary btn-add-detail">+</button>
              <?php else: ?>
                <button type="button" class="btn btn-danger btn-remove-detail">-</button>
              <?php endif; ?>
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-label">Kode Stok</label>
              <select class="form-control stok_id" name="stok_id[]" required>
                <option value="">Pilih Kode Stok</option>
                <?php foreach ($stok_list as $stok) : ?>
                  <option value="<?= $stok['stok_id']; ?>"
                          <?= isset($detail['stok_id']) && $stok['stok_id'] == $detail['stok_id'] ? 'selected' : ''; ?>
                          data-nama="<?= $stok['nama_barang']; ?>"
                          data-harga="<?= $stok['harga_beli']; ?>">
                    <?= $stok['kode_stok']; ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Nama Barang</label>
                <input type="text" class="form-control nama_barang" value="<?= isset($detail['nama_barang']) ? $detail['nama_barang'] : ''; ?>" readonly>
            </div>
            <div class="col-md-2 mb-3">
              <label class="form-label">Kuantitas per Barang</label>
              <input type="text" class="form-control kuantitas" name="total_kuantitas[]" value="<?= number_format($detail['qty_det_terima'], 2, '.', ','); ?>" required>
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-label">Harga Beli per Barang</label>
              <input type="text" class="form-control harga_total" name="jumlah_harga_beli[]" value="<?= number_format($detail['jumlah_det_terima'], 2, '.', ','); ?>" readonly>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- Total PO -->
      <div class="row">
        <div class="col-md-3 mb-3">
          <label class="form-label">Total Kuantitas</label>
          <input type="text" class="form-control" id="total_kuantitas_penerimaan" value="<?= number_format($total_kuantitas_penerimaan ?? 0, 2, '.', ','); ?>" readonly>
        </div>
        <div class="col-md-3 mb-3">
          <label class="form-label">Total Beli</label>
          <input type="text" class="form-control" id="total_harga_beli_penerimaan" value="<?= number_format($total_harga_beli_penerimaan ?? 0, 2, '.', ','); ?>" readonly>
        </div>
        <div class="col-md-3 mb-3">
          <label class="form-label">PPN</label>
          <input type="text" class="form-control" id="ppn_penerimaan" value="<?= number_format($ppn_penerimaan ?? 0, 2, '.', ','); ?>" readonly>
        </div>
        <div class="col-md-3 mb-3">
          <label class="form-label">Jumlah Total</label>
          <input type="text" class="form-control" id="total_harga_beli_penerimaan_ppn" value="<?= number_format($total_harga_beli_penerimaan_ppn ?? 0, 2, '.', ','); ?>" readonly>
        </div>
      </div>

      <button type="submit" class="btn btn-primary mt-2">Simpan Perubahan</button>
    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const container = document.getElementById('detail-penerimaan-container');
  const addButton = document.querySelector('.btn-add-detail');
  const kodeSupplierSelect = document.getElementById('kode_supplier');
const nomorPoSelect = document.getElementById('nomor_po');
const namaSupplierInput = document.getElementById('nama_supplier');
const hiddenPoIdInput = document.getElementById('po_id');
const poList = <?= json_encode($po_list); ?>;
const selectedSupplierId = "<?= $penerimaan['supplier_id'] ?? ''; ?>";
const selectedPoId = "<?= $selected_po_id ?? ''; ?>";
  const inputs = document.querySelectorAll('input[type="text"], textarea');

  inputs.forEach(function(input) {
    input.addEventListener('input', function() {
      this.value = this.value.toUpperCase();
    });
  });

  $(function () {
    $('#nama_supplier').on('keydown', function (e) {
      if (e.key === 'Enter') {
        e.preventDefault(); 
        const keyword = $(this).val().trim();
        if (!keyword) return;

        $.ajax({
          url: '/admin/penerimaan_pembelian/search_supplier', 
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
      $('#po_id').val('');
      $('#nomor_po').val('');
      $('#result_supplier').hide();

      fetch(`/admin/penerimaan_pembelian/po_by_supplier/${supplierId}`)
        .then(res => res.json())
        .then(data => {
            const nomorPoSelect = document.getElementById('nomor_po');
            nomorPoSelect.innerHTML = '';

            const placeholderOption = document.createElement('option');
            placeholderOption.value = '';
            placeholderOption.textContent = 'Pilih Nomor PO';
            placeholderOption.disabled = true;
            placeholderOption.selected = true;
            nomorPoSelect.appendChild(placeholderOption);

            data.forEach(po => {
            const option = document.createElement('option');
            option.value = po.po_id;
            option.text = po.nomor_po;
            nomorPoSelect.appendChild(option);
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

  function updateTotal() {
    let totalQty = 0, totalHarga = 0;
    document.querySelectorAll('.detail-penerimaan').forEach(row => {
        let qtyText = row.querySelector('.kuantitas').value || '0';
        let qty = parseFloat(qtyText.replace(',', '.')) || 0;
        let hargaSatuan = parseFloat(row.querySelector('.stok_id option:checked').getAttribute('data-harga')) || 0;
        let subtotal = qty * hargaSatuan;
        totalQty += qty;
        totalHarga += subtotal;
    });

    const ppn = Math.round(totalHarga * 0.11);
const jumlahTotal = Math.round(totalHarga + ppn);

    document.getElementById('total_kuantitas_penerimaan').value = totalQty.toFixed(2);
    document.getElementById('total_harga_penerimaan').value = totalHarga.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
    document.getElementById('ppn_penerimaan').value = ppn.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
    document.getElementById('total_ppn_penerimaan').value = jumlahTotal.toLocaleString('en-US', { // ✅ Total + PPN 
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

  function autoFill(event) {
    if (event.target.classList.contains('kode_stok')) {
      let row = event.target.closest('.detail-penerimaan');
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
        const row = event.target.closest('.detail-penerimaan');
        const selectedOption = row.querySelector('.stok_id option:checked');
        const harga = parseFloat(selectedOption?.getAttribute('data-harga') || 0);
        const qtyRaw = event.target.value.replace(',', '.') || 0;
        const qty = parseFloat(qtyRaw) || 0;
        const total = harga * qty;

        // Pembulatan ke terdekat (misal .50 ke atas dibulatkan)
        const roundedTotal = Math.round(total);

        row.querySelector('.harga_total').value = roundedTotal.toLocaleString('en-US', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        });

        updateTotal();
    }
}

  container.addEventListener('input', updateHarga);

  updateTotal();
});

  // Fungsi untuk mengisi otomatis nama supplier
  function updateNamaSupplier() {
    let selectedOption = kodeSupplierSelect.options[kodeSupplierSelect.selectedIndex];
    if (selectedOption) {
      namaSupplierInput.value = selectedOption.getAttribute('data-nama') || '';
    }
  }

  // Event listener saat kode supplier berubah
  kodeSupplierSelect.addEventListener('change', updateNamaSupplier);

  // Panggil fungsi updateNamaSupplier saat halaman pertama kali dimuat
  updateNamaSupplier();

  function updatePOBySupplier(triggeredByUser = true) {
  const supplierId = document.getElementById('supplier_id').value;

  // FILTER PO SESUAI SUPPLIER
  const poForSupplier = poList.filter(po => String(po.supplier_id) === String(supplierId));

  nomorPoSelect.innerHTML = '';
  hiddenPoIdInput.value = '';

  if (poForSupplier.length > 0) {
    const placeholderOption = document.createElement('option');
    placeholderOption.value = '';
    placeholderOption.textContent = 'Pilih Nomor PO';
    placeholderOption.disabled = true;
    placeholderOption.selected = true;
    nomorPoSelect.appendChild(placeholderOption);

    poForSupplier.forEach(po => {
      const option = document.createElement('option');
      option.value = po.po_id;
      option.textContent = po.nomor_po;
      option.setAttribute('data-penerimaan-id', po.po_id);
      if (po.po_id == selectedPoId) {
        option.selected = true;
        hiddenPoIdInput.value = po.po_id;
      }
      nomorPoSelect.appendChild(option);
    });
  } else {
    const noPoOption = document.createElement('option');
    noPoOption.textContent = 'Tidak ada Nomor PO';
    noPoOption.disabled = true;
    noPoOption.selected = true;
    nomorPoSelect.appendChild(noPoOption);
    nomorPoSelect.disabled = true;
  }
}

  if (selectedSupplierId && selectedPoId) {
  fetch(`/admin/penerimaan_pembelian/po_by_supplier/${selectedSupplierId}`)
    .then(res => res.json())
    .then(data => {
      nomorPoSelect.innerHTML = '';
      let found = false;

      console.log('selectedPoId:', selectedPoId);
      console.log('PO yang tersedia:', data);

      data.forEach(po => {
        const option = document.createElement('option');
        option.value = po.po_id;
        option.text = po.nomor_po;
        option.setAttribute('data-penerimaan-id', po.po_id);

        if (String(po.po_id) === String(selectedPoId)) {
          option.selected = true;
          hiddenPoIdInput.value = po.po_id;
          found = true;
        }

        nomorPoSelect.appendChild(option);
      });

      if (!found) {
        const placeholderOption = document.createElement('option');
        placeholderOption.value = '';
        placeholderOption.textContent = 'Pilih Nomor PO';
        placeholderOption.disabled = true;
        placeholderOption.selected = true;
        nomorPoSelect.prepend(placeholderOption);
      } else {
        setPoId(); // isi hidden input
        nomorPoSelect.dispatchEvent(new Event('change')); // trigger pengisian stok
      }
    });
}

  // Event listener saat kode supplier berubah
  kodeSupplierSelect.addEventListener('change', () => {
    updatePOBySupplier(true);   // Memuat ulang PO berdasarkan supplier yang dipilih
  });

  // Fungsi untuk mengupdate hidden po_id saat nomor PO dipilih
  function setPoId() {
    let selectedOption = nomorPoSelect.options[nomorPoSelect.selectedIndex];
    if (selectedOption) {
      let poId = selectedOption.getAttribute('data-penerimaan-id');
      hiddenPoIdInput.value = poId;  // Mengisi hidden input dengan po_id
    }
  }

  // Event listener saat nomor PO berubah
  nomorPoSelect.addEventListener('change', setPoId);

  function addRow() {
    const row = document.querySelector('.detail-penerimaan');
    const newRow = row.cloneNode(true);

    // Reset values
    newRow.querySelector('.stok_id').value = '';
    newRow.querySelector('.nama_barang').value = '';
    newRow.querySelector('.kuantitas').value = '';
    newRow.querySelector('.harga_total').value = '';

    // Ganti tombol "+" dengan tombol "-"
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
      const row = event.target.closest('.detail-penerimaan');
      row.remove();
      updateTotal();
    }
  });

  // Update nama_barang & harga_total saat stok dipilih
container.addEventListener('change', function (event) {
  if (event.target.classList.contains('stok_id')) {
    const row = event.target.closest('.detail-penerimaan');
    const option = event.target.selectedOptions[0];
    const harga = parseFloat(option.getAttribute('data-harga')) || 0;
    const nama = option.getAttribute('data-nama') || '';

    // Set nama barang
    row.querySelector('.nama_barang').value = nama;

    // Set harga beli langsung (tanpa dikali qty)
    row.querySelector('.harga_total').value = harga.toLocaleString('en-US', {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2
    });

    updateTotal();
  }
});

  // Tambahkan event listener untuk input dan tombol
  container.addEventListener('change', autoFill);
    container.addEventListener('input', updateHarga);
    container.addEventListener('input', function (event) {
    if (event.target.classList.contains('harga_total')) {
      event.target.dataset.manual = "true";
      updateTotal();
    }

  document.getElementById('nomor_po').addEventListener('change', function () {
    const poId = this.value;
    const stokSelects = document.querySelectorAll('.stok_id');
    const namaBarangInputs = document.querySelectorAll('.nama_barang');

    if (!poId) return;

    fetch(`/admin/penerimaan_pembelian/stok_by_po/${poId}`)
        .then(response => response.json())
        .then(data => {
            stokSelects.forEach(select => {
                select.innerHTML = '<option value="">Pilih Kode Stok</option>';
                data.forEach(stok => {
                    const option = document.createElement('option');
                    option.value = stok.stok_id;
                    option.text = stok.kode_stok;
                    option.setAttribute('data-nama', stok.nama_barang);
                    option.setAttribute('data-harga', stok.harga_beli);
                    select.appendChild(option);
                });
            });

            namaBarangInputs.forEach(input => input.value = '');
        });
});
});
</script>

<?= $this->endSection() ?>