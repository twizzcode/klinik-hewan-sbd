<?php
// Sertakan header
include_once __DIR__ . '/../../layouts/admin_header.php';
?>

<h1>Tambah Obat / Inventaris Baru</h1>
<p>Silakan isi form di bawah ini.</p>
<hr>

<form action="/actions/admin/medicines/store.php" method="POST">
    
    <div class="mb-3">
        <label for="nama_obat" class="form-label">Nama Obat/Alat</label>
        <input type="text" class="form-control" id="nama_obat" name="nama_obat" required>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="kategori" class="form-label">Kategori</label>
            <select class="form-select" id="kategori" name="kategori" required>
                <option value="Antibiotik" selected>Antibiotik</option>
                <option value="Vitamin">Vitamin</option>
                <option value="Vaksin">Vaksin</option>
                <option value="Anti_Parasit">Anti Parasit</option>
                <option value="Suplemen">Suplemen</option>
                <option value="Alat_Medis">Alat Medis</option>
                <option value="Lainnya">Lainnya</option>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label for="bentuk_sediaan" class="form-label">Bentuk Sediaan</label>
            <select class="form-select" id="bentuk_sediaan" name="bentuk_sediaan">
                <option value="" selected>-- Pilih Bentuk --</option>
                <option value="Tablet">Tablet</option>
                <option value="Kapsul">Kapsul</option>
                <option value="Sirup">Sirup</option>
                <option value="Injeksi">Injeksi</option>
                <option value="Salep">Salep</option>
                <option value="Tetes">Tetes</option>
                <option value="Lainnya">Lainnya</option>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="satuan" class="form-label">Satuan</label>
            <input type="text" class="form-control" id="satuan" name="satuan" placeholder="Contoh: Botol, Strip, Pcs" required>
        </div>
        <div class="col-md-4 mb-3">
            <label for="stok" class="form-label">Stok Awal</label>
            <input type="number" class="form-control" id="stok" name="stok" value="0">
        </div>
        <div class="col-md-4 mb-3">
            <label for="status_tersedia" class="form-label">Status</label>
            <select class="form-select" id="status_tersedia" name="status_tersedia" required>
                <option value="1" selected>Tersedia</option>
                <option value="0">Tidak Tersedia</option>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="harga_beli" class="form-label">Harga Beli (Modal)</label>
            <input type="number" class="form-control" id="harga_beli" name="harga_beli" placeholder="Contoh: 50000" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="harga_jual" class="form-label">Harga Jual</label>
            <input type="number" class="form-control" id="harga_jual" name="harga_jual" placeholder="Contoh: 60000" required>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="supplier" class="form-label">Supplier (Opsional)</label>
            <input type="text" class="form-control" id="supplier" name="supplier">
        </div>
        <div class="col-md-6 mb-3">
            <label for="expired_date" class="form-label">Tanggal Expired (Opsional)</label>
            <input type="date" class="form-control" id="expired_date" name="expired_date">
        </div>
    </div>

    <div class="mb-3">
        <label for="deskripsi" class="form-label">Deskripsi (Opsional)</label>
        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"></textarea>
    </div>
    
    <button type="submit" class="btn btn-primary">Simpan Obat</button>
    <a href="/pages/admin/medicines/index.php" class="btn btn-secondary">Batal</a>
</form>

<?php
// Sertakan footer
include_once __DIR__ . '/../../layouts/admin_footer.php';
?>