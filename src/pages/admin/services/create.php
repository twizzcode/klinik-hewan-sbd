<?php
// Sertakan header
include_once __DIR__ . '/../../layouts/admin_header.php';
?>

<h1>Tambah Layanan Baru</h1>
<p>Silakan isi form di bawah ini untuk menambahkan layanan baru.</p>
<hr>

<form action="/actions/admin/services/store.php" method="POST">
    
    <div class="mb-3">
        <label for="nama_layanan" class="form-label">Nama Layanan</label>
        <input type="text" class="form-control" id="nama_layanan" name="nama_layanan" required>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="kategori" class="form-label">Kategori</label>
            <select class="form-select" id="kategori" name="kategori" required>
                <option value="Pemeriksaan" selected>Pemeriksaan</option>
                <option value="Vaksinasi">Vaksinasi</option>
                <option value="Grooming">Grooming</option>
                <option value="Bedah">Bedah</option>
                <option value="Rawat_Inap">Rawat Inap</option>
                <option value="Tes_Lab">Tes Lab</option>
                <option value="Emergency">Emergency</option>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label for="harga" class="form-label">Harga (Rp)</label>
            <input type="number" class="form-control" id="harga" name="harga" placeholder="Contoh: 50000" required>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="durasi_estimasi" class="form-label">Durasi Estimasi (Menit)</label>
            <input type="number" class="form-control" id="durasi_estimasi" name="durasi_estimasi" placeholder="Contoh: 30">
        </div>
        <div class="col-md-6 mb-3">
            <label for="status_tersedia" class="form-label">Status</label>
            <select class="form-select" id="status_tersedia" name="status_tersedia" required>
                <option value="1" selected>Tersedia</option>
                <option value="0">Tidak Tersedia</option>
            </select>
        </div>
    </div>

    <div class="mb-3">
        <label for="deskripsi" class="form-label">Deskripsi (Opsional)</label>
        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"></textarea>
    </div>
    
    <button type="submit" class="btn btn-primary">Simpan Layanan</button>
    <a href="/pages/admin/services/index.php" class="btn btn-secondary">Batal</a>
</form>

<?php
// Sertakan footer
include_once __DIR__ . '/../../layouts/admin_footer.php';
?>