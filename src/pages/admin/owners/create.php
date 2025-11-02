<?php
// Sertakan header
include_once __DIR__ . '/../../layouts/admin_header.php';
?>

<h1>Tambah Pemilik Baru</h1>
<p>Silakan isi form di bawah ini.</p>
<hr>

<form action="/actions/admin/owners/store.php" method="POST">
    
    <div class="mb-3">
        <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
        <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" required>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="no_telepon" class="form-label">No. Telepon</label>
            <input type="text" class="form-control" id="no_telepon" name="no_telepon" placeholder="Contoh: 0812..." required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="email" class="form-label">Email (Opsional)</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Contoh: email@example.com">
        </div>
    </div>

    <div class="mb-3">
        <label for="alamat" class="form-label">Alamat (Opsional)</label>
        <textarea class="form-control" id="alamat" name="alamat" rows="3"></textarea>
    </div>

    <div class="mb-3">
        <label for="catatan" class="form-label">Catatan (Opsional)</label>
        <textarea class="form-control" id="catatan" name="catatan" rows="2" placeholder="Catatan internal untuk admin..."></textarea>
    </div>
    
    <button type="submit" class="btn btn-primary">Simpan Pemilik</button>
    <a href="/pages/admin/owners/index.php" class="btn btn-secondary">Batal</a>
</form>

<?php
// Sertakan footer
include_once __DIR__ . '/../../layouts/admin_footer.php';
?>