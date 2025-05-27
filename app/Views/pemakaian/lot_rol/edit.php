<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Pemakaian Bahan</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<a href="<?= base_url('admin/pemakaian/lot_rol/' . esc($pakai_id) . '/' . esc($det_pakai_id)); ?>" class="btn btn-outline-primary mb-3">
    <i class="ti ti-arrow-left"></i> Kembali
</a>

<h5 class="card-title fw-semibold mb-4 text-dark">Ubah Data Lot & Rol Pemakaian Bahan</h5>

<div class="card  mb-4">
    <div class="card-body">
        <form action="<?= base_url('admin/pemakaian/lot_rol/' . $pakai_id . '/update'); ?>" method="post">
            <?= csrf_field(); ?>
            <input type="hidden" name="pakai_id" value="<?= esc($pakai_id); ?>">
            <input type="hidden" name="det_pakai_id" value="<?= esc($det_pakai_id); ?>">
            <input type="hidden" name="lot_pakai" value="<?= esc($lotRolDetails[0]['lot_ke'] ?? ''); ?>">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Nomor Pemakaian</label>
                    <input type="text" class="form-control" value="<?= esc($pemakaian['nomor_pakai'] ?? '-'); ?>" readonly>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Total Kuantitas Pemakaian</label>
                    <?php
                    $totalQty = 0;
                    if (!empty($lotrol_pemakaian)) {
                        foreach ($lotrol_pemakaian as $item) {
                            $totalQty += (float) $item['qty_lotrol_pakai'];
                        }
                    } elseif (!empty($detail_pemakaian['qty_det_pakai'])) {
                        $totalQty = (float) $detail_pemakaian['qty_det_pakai'];
                    }
                    ?>
                    <input type="text" class="form-control" id="total_kuantitas_pakai" value="<?= number_format($totalQty, 2, '.', ','); ?>" readonly>
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
            </div> 
            <div id="detail-lot-rol-container">
            <?php foreach ($lotrol_penerimaan as $lotrol): ?>
                <?php
                    $lot = $lotrol['lot_asal']; 
                    $rol = $lotrol['rol_asal'];
                    $terima = (float) $lotrol['qty_diterima'];

                    if (isset($lotrol['qty_pemakaian']) && $lotrol['qty_pemakaian'] > 0) {
                        // Jika sudah ada pemakaian, selalu tampilkan qty_pemakaian
                        $qty_pakai = (float) $lotrol['qty_pemakaian'];
                    } elseif (isset($lotrol['qty_retur']) && $lotrol['qty_retur'] > 0) {
                        // Kalau belum dipakai tapi sudah ada retur, tampilkan sisa
                        $qty_pakai = max(0, $terima - (float) $lotrol['qty_retur']);
                    } else {
                        // Kalau belum ada pemakaian dan retur, tampilkan qty_diterima
                        $qty_pakai = $terima;
                    }
                ?>
                <div class="row detail-lot-rol">
                    <div class="col-md-1 mb-3 d-flex align-items-end">
                        <button type="button" class="btn btn-danger" onclick="this.closest('.detail-lot-rol').remove();">-</button>
                    </div>
                    <div class="col mb-3">
                        <label class="form-label">Nomor PO</label>
                        <input type="text" class="form-control" value="<?= esc($lotrol['nomor_po'] ?? '-'); ?>" readonly>
                        <input type="hidden" name="po_id[]" value="<?= esc($lotrol['po_id'] ?? '') ?>">
                    </div>
                    <div class="col mb-3">
                        <label class="form-label">Lot</label>
                        <input type="text" class="form-control lot_pakai" name="lot_pakai[]" value="<?= esc($lot); ?>" readonly>
                    </div>
                    <div class="col mb-3">
                        <label class="form-label">Rol Ke</label>
                        <input type="text" class="form-control rol_pakai" name="rol_pakai[]" value="<?= esc($rol); ?>" readonly>
                    </div>
                    <div class="col mb-3">
                        <label class="form-label">Terima</label>
                        <input type="text" class="form-control" value="<?= number_format($terima, 2, '.', ','); ?>" readonly>
                    </div>
                    <div class="col mb-3">
                        <label class="form-label">Pakai</label>
                        <input type="text" class="form-control qty_lotrol_pakai" name="qty_lotrol_pakai[]" value="<?= number_format($qty_pakai, 2, '.', ''); ?>" required>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
            <div class="row mt-3 align-items-end">
                <div class="col-md-auto">
                    <button type="button" class="btn btn-primary" id="kalkulasi-btn">Kalkulasi</button>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Jumlah Kuantitas</label>
                    <input type="number" class="form-control" id="jumlah_kuantitas" readonly>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Selisih</label>
                    <input type="number" class="form-control" id="selisih" readonly>
                </div>
                <div class="col-md-auto">
                    <label class="form-label d-block invisible">Nolkan</label>
                    <button type="button" class="btn btn-warning" id="reset-retur-btn">Nolkan Semua</button>
                </div>
                <div class="col-md-auto">
                    <label class="form-label d-block invisible">Simpan</label>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const container = document.getElementById("detail-lot-rol-container");
    const inputs = document.querySelectorAll('input[type="text"], textarea');

    document.querySelectorAll('.qty_lotrol_pakai').forEach(input => {
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
        rows.forEach((row) => {
            const button = row.querySelector("button");

            button.classList.remove("btn-primary");
            button.classList.add("btn-danger");
            button.textContent = "-";
            button.onclick = function () {
                row.remove();
                updateButtons();
            };
        });
    }

    function addNewRow() {
        const template = container.querySelector(".detail-lot-rol");
        const newRow = template.cloneNode(true);

        newRow.querySelector(".qty_lotrol_pakai").value = "";
        newRow.querySelector(".rol_pakai").value = "R" + String(container.children.length + 1).padStart(2, '0');
        newRow.querySelector(".lot_pakai").value = ""; // optional, tergantung mau isi manual atau tidak

        container.appendChild(newRow);
        updateButtons();
    }

    function doKalkulasi() 
    {
        const qtyInputs = container.querySelectorAll(".qty_lotrol_pakai");
        let totalQty = 0;

        qtyInputs.forEach(input => {
            let val = parseFloat(input.value.replace(',', '.')) || 0;
            totalQty += val;
        });

        const totalRetur = parseFloat(document.getElementById("total_kuantitas_pakai").value.replace(',', '.')) || 0;

        document.getElementById("jumlah_kuantitas").value = totalQty.toFixed(2);
        document.getElementById("selisih").value = (totalRetur - totalQty).toFixed(2);
    }
    document.getElementById("kalkulasi-btn").addEventListener("click", doKalkulasi);

    document.getElementById("reset-retur-btn").addEventListener("click", function () {
        const returInputs = document.querySelectorAll(".qty_lotrol_pakai");
        returInputs.forEach(input => {
            input.value = "0.00";
        });
        doKalkulasi();
    });

    updateButtons();
});
</script>

<?= $this->endSection() ?>