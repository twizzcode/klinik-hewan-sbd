<?php
// Sertakan header
include_once __DIR__ . '/../../layouts/admin_header.php';
// Sertakan koneksi database
include_once __DIR__ . '/../../../config/database.php';

// KITA BUTUH DATA PEMILIK UNTUK DROPDOWN
$pdo = getDBConnection();
$stmt_owners = $pdo->query("SELECT owner_id, nama_lengkap FROM owner ORDER BY nama_lengkap ASC");
?>

<h1>Tambah Hewan Baru</h1>
<p>Silakan isi form di bawah ini.</p>
<hr>

[Image of a data flow diagram showing a form that is populated by the 'owner' table and, upon submission, inserts data into the 'pet' table]

<form action="/actions/admin/pets/store.php" method="POST">
    
    <div class="mb-3">
        <label for="owner_id" class="form-label">Pemilik Hewan</label>
        <select class="form-select" id="owner_id" name="owner_id" required>
            <option value="" selected disabled>-- Pilih Pemilik --</option>
            <?php
            // Loop data pemilik dari query $stmt_owners di atas
            while ($owner = $stmt_owners->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='" . htmlspecialchars($owner['owner_id']) . "'>" 
                   . htmlspecialchars($owner['nama_lengkap']) 
                   . "</option>";
            }
            ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="nama_hewan" class="form-label">Nama Hewan</label>
        <input type="text" class="form-control" id="nama_hewan" name="nama_hewan" required>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="jenis" class="form-label">Jenis Hewan</label>
            <select class="form-select" id="jenis" name="jenis" required>
                <option value="Kucing" selected>Kucing</option>
                <option value="Anjing">Anjing</option>
                <option value="Burung">Burung</option>
                <option value="Kelinci">Kelinci</option>
                <option value="Hamster">Hamster</option>
                <option value="Reptil">Reptil</option>
                <option value="Lainnya">Lainnya</option>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label for="ras" class="form-label">Ras (Opsional)</label>
            <input type="text" class="form-control" id="ras" name="ras" placeholder="Contoh: Persia, Golden Retriever">
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
            <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                <option value="Jantan" selected>Jantan</option>
                <option value="Betina">Betina</option>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label for="tanggal_lahir" class="form-label">Tanggal Lahir (Opsional)</label>
            <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir">
        </div>
    </div>

     <div class="mb-3">
        <label for="warna" class="form-label">Warna (Opsional)</label>
        <input type="text" class="form-control" id="warna" name="warna" placeholder="Contoh: Putih Polos, Belang Tiga">
    </div>

    <div class="mb-3">
        <label for="ciri_khusus" class="form-label">Ciri Khusus (Opsional)</label>
        <textarea class="form-control" id="ciri_khusus" name="ciri_khusus" rows="2"></textarea>
    </div>
    
    <button type="submit" class="btn btn-primary">Simpan Hewan</button>
    <a href="/pages/admin/pets/index.php" class="btn btn-secondary">Batal</a>
</form>

<?php
// Sertakan footer
include_once __DIR__ . '/../../layouts/admin_footer.php';
?>