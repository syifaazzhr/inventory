<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Stok Bahan</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<a href="<?= base_url('admin/stok_bahan'); ?>" class="btn btn-outline-primary mb-3">
  <i class="ti ti-arrow-left"></i> Kembali
</a>

<?php if (session()->getFlashdata('msg')) : ?>
  <div class="pb-2">
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?= session()->getFlashdata('msg') ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  </div>
<?php endif; ?>

<h5 class="card-title fw-semibold mb-4 text-dark">Tambah Data Stok Bahan</h5>

<div class="card mb-4">
  <div class="card-body">
    <form action="<?= base_url('admin/stok_bahan/store'); ?>" method="post">
      <?= csrf_field(); ?>

      <!-- Kode Stok (Otomatis) -->
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Kode Stok</label>
          <input type="text" class="form-control" id="kode_stok" name="kode_stok" value="<?= $kode_stok; ?>" readonly>
        </div>
      </div>
      
      <!-- Grup -->
      <div class="row">
        <div class="col-md-4 mb-3">
          <label class="form-label">Nama Grup</label>
            <div class="position-relative">
              <input type="text" class="form-control" id="nama_grup" autocomplete="off" placeholder="Ketik nama grup lalu tekan Enter" required>
              <input type="hidden" id="grup_id" name="grup_id">
              <div id="result_grup" class="dropdown-menu w-100" style="display:none; max-height: 200px; overflow-y: auto; position: absolute; z-index: 1000;"></div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Kode Grup</label>
          <input type="text" class="form-control" id="kode_grup" readonly>
        </div>
      </div>

      <!-- Bahan -->
      <div class="row">
          <div class="col-md-4 mb-3">
              <label class="form-label">Jenis Bahan</label>
              <div class="position-relative">
                  <input type="text" class="form-control" id="nama_bahan" autocomplete="off" placeholder="Ketik nama bahan lalu tekan Enter" required>
                  <input type="hidden" id="bahan_id" name="bahan_id">
                  <div id="result_bahan" class="dropdown-menu w-100" style="display:none; max-height:200px; overflow-y:auto; position:absolute; z-index:1000;"></div>
              </div>
          </div>
          <div class="col-md-4 mb-3">
              <label class="form-label">Kode Bahan</label>
              <input type="text" class="form-control" id="kode_bahan" readonly>
          </div>
      </div>

      <!-- Warna -->
      <div class="row">
        <div class="col-md-4 mb-3">
          <label class="form-label">Jenis Warna</label>
          <div class="position-relative">
            <input type="text" class="form-control" id="jenis_warna_input" autocomplete="off" placeholder="Ketik jenis warna lalu tekan Enter" required>
            <input type="hidden" id="warna_id" name="warna_id">
            <div id="result_warna" class="dropdown-menu w-100" style="display:none; max-height:200px; overflow-y:auto; position:absolute; z-index:1000;"></div>
          </div>
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Kode Warna</label>
          <input type="text" class="form-control" id="kode_warna" readonly>
        </div>
      </div>

      <!-- Satuan -->
      <div class="row">
        <div class="col-md-3 mb-3">
  <label class="form-label">Kode Satuan</label>
  <div class="position-relative">
    <input type="text" class="form-control" id="kode_satuan_input" autocomplete="off" placeholder="Ketik kode satuan lalu tekan Enter" required>
    <input type="hidden" id="satuan_id" name="satuan_id">
    <div id="result_satuan" class="dropdown-menu w-100" style="display:none; max-height:200px; overflow-y:auto; position:absolute; z-index:1000;"></div>
  </div>
</div>
        <div class="col-md-3 mb-3">
  <label class="form-label">Jenis Satuan</label>
  <input type="text" class="form-control" id="jenis_satuan" readonly>
</div>
        <div class="col-md-3 mb-3">
          <label class="form-label">Isi dalam Satuan</label>
          <input type="text" class="form-control" id="isi_satuan" required>
        </div>
        <div class="col-md-3 mb-3">
          <label class="form-label">Unit Satuan</label>
          <input type="text" class="form-control" id="jenis_unit" readonly>
        </div>
      </div>

      <!-- Harga Beli, Harga per PCS, Last Received -->
      <div class="row">
        <div class="col-md-4 mb-3">
          <label class="form-label">Harga Beli</label>
          <input type="number" class="form-control" name="harga_beli" required>
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Harga per PCS</label>
          <input type="number" class="form-control" name="harga_per_pcs" required>
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Terakhir Diterima</label>
          <input type="date" class="form-control" name="last_received" required>
        </div>
      </div>

      <!-- Supplier -->
      <div class="row">
          <div class="col-md-6 mb-3">
              <label class="form-label">Nama Supplier</label>
              <div class="position-relative">
                  <input type="text" class="form-control" id="nama_supplier" autocomplete="off" placeholder="Ketik nama supplier lalu tekan Enter" required>
                  <input type="hidden" id="supplier_id" name="supplier_id">
                  <div id="result_supplier" class="dropdown-menu w-100" style="display:none; max-height:200px; overflow-y:auto; position:absolute; z-index:1000;"></div>
              </div>
          </div>
          <div class="col-md-6 mb-3">
              <label class="form-label">Kode Supplier</label>
              <input type="text" class="form-control" id="kode_supplier" readonly>
          </div>
      </div>

      <!-- Nama Barang -->
      <div class="row">
        <div class="col-md-12 mb-3">
          <label class="form-label">Nama Barang</label>
          <input type="text" class="form-control" id="nama_barang" name="nama_barang" placeholder="Masukkan Nama Barang" required>
        </div>
      </div>

      <button type="submit" class="btn btn-primary mt-2">Simpan</button>
    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const namaGrupInput = document.getElementById('nama_grup');
    const resultGrup = document.getElementById('result_grup');
    const lastReceivedInput = document.querySelector('[name="last_received"]');
    if (lastReceivedInput) {
        const today = new Date();
        const formattedDate = today.toISOString().split('T')[0]; // Format YYYY-MM-DD
        lastReceivedInput.value = formattedDate;
    }

    namaGrupInput.addEventListener('keydown', function (e) {
    const keyword = this.value.trim();

    if (e.key === 'Enter' && keyword.length > 0) {
        e.preventDefault();

        fetch(`<?= base_url('admin/stok_bahan/searchGrup') ?>?query=${encodeURIComponent(keyword)}`)
            .then(response => response.json())
            .then(data => {
                resultGrup.innerHTML = '';
                if (data.length > 0) {
                    data.forEach(item => {
                        const option = document.createElement('div');
                        option.className = 'dropdown-item';
                        option.textContent = item.nama_grup;
                        option.setAttribute('data-id', item.grup_id); 
                        option.setAttribute('data-kode', item.kode_grup);

                        option.addEventListener('click', function () {
                            namaGrupInput.value = this.textContent;
                            document.getElementById('grup_id').value = this.dataset.id;
                            document.getElementById('kode_grup').value = this.dataset.kode;
                            resultGrup.style.display = 'none';
                        });

                        resultGrup.appendChild(option);
                    });
                    resultGrup.style.display = 'block';
                } else {
                    resultGrup.innerHTML = '<div class="dropdown-item disabled">Nama Grup Tidak Ditemukan</div>';
                    resultGrup.style.display = 'block';
                }
            })
            .catch(err => console.error(err));
    }
});

    // Sembunyikan dropdown jika klik di luar
    document.addEventListener('click', function(e) {
        if (!namaGrupInput.contains(e.target) && !resultGrup.contains(e.target)) {
            resultGrup.style.display = 'none';
        }
    });

    const namaBahanInput = document.getElementById('nama_bahan');
    const resultBahan = document.getElementById('result_bahan');

    namaBahanInput.addEventListener('keydown', function(e) {
        const keyword = this.value.trim();

        if (e.key === 'Enter' && keyword.length > 0) {
            e.preventDefault();

            const grupId = document.getElementById('grup_id').value;

            if (!grupId) {
                alert('Silakan pilih Nama Grup terlebih dahulu.');
                return;
            }

            fetch(`<?= base_url('admin/stok_bahan/searchBahan') ?>?query=${encodeURIComponent(keyword)}&grup_id=${grupId}`)
                .then(response => response.json())
                .then(data => {
                    resultBahan.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(item => {
                            const option = document.createElement('div');
                            option.className = 'dropdown-item';
                            option.textContent = item.nama_bahan;
                            option.setAttribute('data-id', item.bahan_id);
                            option.setAttribute('data-kode', item.kode_bahan);

                            option.addEventListener('click', function () {
                                namaBahanInput.value = this.textContent;
                                document.getElementById('kode_bahan').value = this.dataset.kode;
                                document.getElementById('bahan_id').value = this.dataset.id; 
                                resultBahan.style.display = 'none';
                            });

                            resultBahan.appendChild(option);
                        });
                        resultBahan.style.display = 'block';
                    } else {
                        resultBahan.innerHTML = '<div class="dropdown-item disabled">Nama Bahan Tidak Ditemukan</div>';
                        resultBahan.style.display = 'block';
                    }
                })
                .catch(err => console.error(err));
        }
    });

    // Sembunyikan dropdown saat klik di luar
    document.addEventListener('click', function(e) {
        if (!namaBahanInput.contains(e.target) && !resultBahan.contains(e.target)) {
            resultBahan.style.display = 'none';
        }
    });

    const jenisWarnaInput = document.getElementById('jenis_warna_input');
const resultWarna = document.getElementById('result_warna');

jenisWarnaInput.addEventListener('keydown', function(e) {
    const keyword = this.value.trim();

    if (e.key === 'Enter' && keyword.length > 0) {
        e.preventDefault();

        fetch(`<?= base_url('admin/stok_bahan/searchWarna') ?>?query=${encodeURIComponent(keyword)}`)
            .then(response => response.json())
            .then(data => {
                resultWarna.innerHTML = '';
                if (data.length > 0) {
                    data.forEach(item => {
                        const option = document.createElement('div');
                        option.className = 'dropdown-item';
                        option.textContent = item.jenis_warna;
                        option.setAttribute('data-id', item.warna_id);
                        option.setAttribute('data-kode', item.kode_warna);

                        option.addEventListener('click', function () {
                            jenisWarnaInput.value = this.textContent;
                            document.getElementById('kode_warna').value = this.dataset.kode;
                            document.getElementById('warna_id').value = this.dataset.id; 
                            resultWarna.style.display = 'none';
                        });

                        resultWarna.appendChild(option);
                    });
                    resultWarna.style.display = 'block';
                } else {
                    resultWarna.innerHTML = '<div class="dropdown-item disabled">Jenis Warna Tidak Ditemukan</div>';
                    resultWarna.style.display = 'block';
                }
            })
            .catch(err => console.error(err));
    }
});

// Sembunyikan dropdown jika klik di luar
document.addEventListener('click', function(e) {
    if (!jenisWarnaInput.contains(e.target) && !resultWarna.contains(e.target)) {
        resultWarna.style.display = 'none';
    }
});

const kodeSatuanInput = document.getElementById('kode_satuan_input');
const resultSatuan = document.getElementById('result_satuan');

kodeSatuanInput.addEventListener('keydown', function(e) {
  const keyword = this.value.trim();

  if (e.key === 'Enter' && keyword.length > 0) {
    e.preventDefault();

    fetch(`<?= base_url('admin/stok_bahan/searchSatuan') ?>?query=${encodeURIComponent(keyword)}`)
      .then(response => response.json())
      .then(data => {
        resultSatuan.innerHTML = '';
        if (data.length > 0) {
          data.forEach(item => {
            const option = document.createElement('div');
            option.className = 'dropdown-item';
            option.textContent = item.kode_satuan;
            option.setAttribute('data-id', item.satuan_id);
            option.setAttribute('data-kode', item.kode_satuan);
            option.setAttribute('data-jenis', item.jenis_satuan);
            option.setAttribute('data-isi', item.isi_satuan);
            option.setAttribute('data-unit', item.jenis_unit);

            option.addEventListener('click', function () {
              kodeSatuanInput.value = this.dataset.kode;
              document.getElementById('jenis_satuan').value = this.dataset.jenis;
              document.getElementById('isi_satuan').value = this.dataset.isi;
              document.getElementById('jenis_unit').value = this.dataset.unit;
              document.getElementById('satuan_id').value = this.dataset.id;
              resultSatuan.style.display = 'none';
            });

            resultSatuan.appendChild(option);
          });
          resultSatuan.style.display = 'block';
        } else {
          resultSatuan.innerHTML = '<div class="dropdown-item disabled">Kode Satuan Tidak Ditemukan</div>';
          resultSatuan.style.display = 'block';
        }
      })
      .catch(err => console.error(err));
  }
});

document.addEventListener('click', function(e) {
  if (!kodeSatuanInput.contains(e.target) && !resultSatuan.contains(e.target)) {
    resultSatuan.style.display = 'none';
  }
});

const namaSupplierInput = document.getElementById('nama_supplier');
const resultSupplier = document.createElement('div');
resultSupplier.id = 'result_supplier';
resultSupplier.className = 'dropdown-menu w-100';
resultSupplier.style.display = 'none';
resultSupplier.style.maxHeight = '200px';
resultSupplier.style.overflowY = 'auto';
resultSupplier.style.position = 'absolute';
resultSupplier.style.zIndex = '1000';
namaSupplierInput.parentElement.appendChild(resultSupplier);

namaSupplierInput.addEventListener('keydown', function(e) {
    const keyword = this.value.trim();

    if (e.key === 'Enter' && keyword.length > 0) {
        e.preventDefault();

        fetch(`<?= base_url('admin/stok_bahan/searchSupplier') ?>?query=${encodeURIComponent(keyword)}`)
            .then(response => response.json())
            .then(data => {
                resultSupplier.innerHTML = '';
                if (data.length > 0) {
                    data.forEach(item => {
                        const option = document.createElement('div');
                        option.className = 'dropdown-item';
                        option.textContent = item.nama_supplier;
                        option.setAttribute('data-id', item.supplier_id);
                        option.setAttribute('data-kode', item.kode_supplier);

                        option.addEventListener('click', function () {
                            namaSupplierInput.value = this.textContent;
                            document.getElementById('kode_supplier').value = this.dataset.kode;
                            document.getElementById('supplier_id').value = this.dataset.id; 
                            resultSupplier.style.display = 'none';
                        });

                        resultSupplier.appendChild(option);
                    });
                    resultSupplier.style.display = 'block';
                } else {
                    resultSupplier.innerHTML = '<div class="dropdown-item disabled">Supplier Tidak Ditemukan</div>';
                    resultSupplier.style.display = 'block';
                }
            })
            .catch(err => console.error(err));
    }
});

// Sembunyikan dropdown saat klik di luar
document.addEventListener('click', function(e) {
    if (!namaSupplierInput.contains(e.target) && !resultSupplier.contains(e.target)) {
        resultSupplier.style.display = 'none';
    }
});

    // AutoFill untuk select option (Jika diperlukan)
    function autoFill(selectId, inputId, attr) {
        let selectElement = document.getElementById(selectId);
        let inputElement = document.getElementById(inputId);

        if (selectElement && inputElement) {
            selectElement.addEventListener('change', function() {
                let selectedOption = this.options[this.selectedIndex];
                inputElement.value = selectedOption.getAttribute('data-' + attr) || '';
            });
        }
    }

    autoFill('kode_bahan', 'nama_bahan', 'nama');
    autoFill('kode_warna', 'jenis_warna', 'jenis');
    autoFill('kode_supplier', 'nama_supplier', 'nama');

    // Auto generate Kode Stok
    const grupSelect = document.getElementById('nama_grup_select');
    const bahanSelect = document.getElementById('kode_bahan');
    const kodeStokInput = document.getElementById('kode_stok');

    function updateKodeStok() {
        const grupId = grupSelect ? grupSelect.value : '';
        const kodeBahan = bahanSelect.value;

        if (grupId && kodeBahan) {
            fetch(`<?= base_url('admin/stok_bahan/generateKode') ?>/${grupId}/${kodeBahan}`)
                .then(response => response.json())
                .then(data => {
                    if (data && data.kode_stok) {
                        kodeStokInput.value = data.kode_stok;
                    } else {
                        kodeStokInput.value = '';
                    }
                })
                .catch(error => {
                    console.error("Gagal mengambil kode stok:", error);
                    kodeStokInput.value = '';
                });
        } else {
            kodeStokInput.value = '';
        }
    }

    if (grupSelect) grupSelect.addEventListener('change', updateKodeStok);
    bahanSelect.addEventListener('change', updateKodeStok);
});
</script>

<script>
  // Auto Uppercase Semua Input Text & Textarea
  document.addEventListener('input', function(e) {
        if ((e.target.tagName === 'INPUT' && e.target.type === 'text') || e.target.tagName === 'TEXTAREA') {
            const cursorPos = e.target.selectionStart;
            e.target.value = e.target.value.toUpperCase();
            e.target.setSelectionRange(cursorPos, cursorPos);
        }
    });
</script>

<?= $this->endSection() ?>