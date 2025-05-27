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
    <h5 class="card-title fw-semibold">Form Ubah Lot & Rol</h5>
    <form action="<?= base_url('admin/penerimaan_pembelian/lot_rol/' . esc($lotrol_id) . '/update'); ?>" method="post">
      <?= csrf_field(); ?>
      <input type="hidden" name="terima_id" value="<?= esc($terima_id); ?>">
      <input type="hidden" name="det_terima_id" value="<?= esc($det_terima_id); ?>">
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
      <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label">Kode Stok</label>
            <input type="text" class="form-control" value="<?= esc(isset($stok_detail['kode_stok']) ? $stok_detail['kode_stok'] : ''); ?>" readonly>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Nama Barang</label>
            <input type="text" class="form-control" value="<?= esc(isset($stok_detail['nama_barang']) ? $stok_detail['nama_barang'] : ''); ?>" readonly>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Kuantitas</label>
            <input type="text" class="form-control" 
                id="kuantitas_utama"
                value="<?= number_format($detail['qty_det_terima'], 2, '.', ''); ?>" 
                readonly>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label">Lot Ke</label>
            <input type="text" class="form-control lot_terima" name="lot_terima[]" value="<?= esc(isset($lotRolDetails[0]['lot_terima']) ? $lotRolDetails[0]['lot_terima'] : ''); ?>" required>
        </div>
    </div> 
    <div id="detail-lot-rol-container">
      <?php foreach ($lotRolDetails as $index => $lotRol): ?>
        <div class="row detail-lot-rol">
          <div class="col-md-1 mb-3 d-flex align-items-end">
            <button type="button" class="btn btn-primary btn-add-detail">+</button>
          </div>
          <div class="col-md-3 mb-3">
              <label class="form-label">Rol Ke</label>
              <input type="text" class="form-control rol_terima" name="rol_terima[]" value="<?= esc($lotRol['rol_terima']); ?>" required>
          </div>
          <div class="col-md-3 mb-3">
              <label class="form-label">Kuantitas Per Rol</label>
              <input type="text" class="form-control qty_lotrol_terima" name="qty_lotrol_terima[]" 
                value="<?= number_format($lotRol['qty_lotrol_terima'], 2, '.', ''); ?>" 
                required>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
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
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
    </div>
    </form>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const container = document.getElementById("detail-lot-rol-container");
    const inputs = document.querySelectorAll('input[type="text"], textarea');

    document.querySelectorAll('.qty_lotrol_terima').forEach(input => {
        input.addEventListener('blur', function () {
            let val = parseFloat(this.value);
            if (isNaN(val) || val < 1) {
            alert("Minimal kuantitas adalah 1");
            this.focus();
            }
        });
    });

    inputs.forEach(function(input) {
        input.addEventListener('input', function() {
        this.value = this.value.toUpperCase();
        });
    });

    function updateButtons() {
        const rows = container.querySelectorAll(".detail-lot-rol");
        rows.forEach((row, index) => {
            const button = row.querySelector("button");
            const rolKeInput = row.querySelector(".rol_terima");
            rolKeInput.value = "R" + String(index + 1).padStart(2, '0');

            if (index === 0) {
                button.classList.remove("btn-danger");
                button.classList.add("btn-primary");
                button.textContent = "+";
                button.onclick = addNewRow;
            } else {
                button.classList.remove("btn-primary");
                button.classList.add("btn-danger");
                button.textContent = "-";
                button.onclick = function () {
                    row.remove();
                    updateButtons();
                };
            }
        });
    }

    function addNewRow() {
        const template = container.querySelector(".detail-lot-rol");
        const newRow = template.cloneNode(true);

        newRow.querySelector(".qty_lotrol_terima").value = "";
        newRow.querySelector(".rol_terima").value = "";

        container.appendChild(newRow);
        updateButtons();
    }

    function doKalkulasi() 
    {
        const qtyInputs = container.querySelectorAll(".qty_lotrol_terima");
        let totalQty = 0;

        qtyInputs.forEach(input => {
            let val = parseFloat(input.value.replace(',', '.')) || 0;
            totalQty += val;
        });

        const kuantitasUtama = parseFloat(document.getElementById("kuantitas_utama").value.replace(',', '.')) || 0;

        document.getElementById("jumlah_kuantitas").value = totalQty.toFixed(2);
        document.getElementById("selisih").value = (kuantitasUtama - totalQty).toFixed(2);
    }

    document.getElementById("kalkulasi-btn").addEventListener("click", doKalkulasi);

    updateButtons();
});
</script>

<?= $this->endSection() ?>