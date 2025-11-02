<?php
// Sertakan header
include_once __DIR__ . '/../../layouts/admin_header.php';
?>

<h1>Tambah Dokter Baru</h1>
<p>Silakan isi form di bawah ini untuk menambahkan dokter baru.</p>
<hr>

<form action="/actions/admin/doctors/store.php" method="POST">
    <div class="mb-3">
        <label for="nama_dokter" class="form-label">Nama Dokter</label>
        <input type="text" class="form-control" id="nama_dokter" name="nama_dokter" required>
    </div>
    <div class="mb-3">
        <label for="no_lisensi" class="form-label">No. Lisensi</label>
        <input type="text" class="form-control" id="no_lisensi" name="no_lisensi">
    </div>
    <div class="mb-3">
        <label for="spesialisasi" class="form-label">Spesialisasi</label>
        <select class="form-select" id="spesialisasi" name="spesialisasi">
            <option value="Umum" selected>Umum</option>
            <option value="Bedah">Bedah</option>
            <option value="Gigi">Gigi</option>
            <option value="Kulit">Kulit</option>
            <option value="Kardio">Kardio</option>
            <option value="Eksotik">Eksotik</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="no_telepon" class="form-label">No. Telepon</label>
        <input type="text" class="form-control" id="no_telepon" name="no_telepon" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email">
    </div>
    <div class="mb-3">
        <label for="tanggal_bergabung" class="form-label">Tanggal Bergabung</label>
        <input type="date" class="form-control" id="tanggal_bergabung" name="tanggal_bergabung" required>
    </div>
    
    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="/pages/admin/doctors/index.php" class="btn btn-secondary">Batal</a>
</form>

<?php
// Sertakan footer
include_once __DIR__ . '/../../layouts/admin_footer.php';
?>