<?php
// Sertakan header dan koneksi
include_once __DIR__ . '/../../layouts/admin_header.php';
include_once __DIR__ . '/../../../config/database.php';

// 1. Cek apakah 'id' ada di URL
if (!isset($_GET['id'])) {
    echo "<div class='alert alert-danger'>ID Obat tidak ditemukan.</div>";
    include_once __DIR__ . '/../../layouts/admin_footer.php';
    exit;
}

$obat_id = $_GET['id'];
$pdo = getDBConnection();

// 2. Ambil data obat dari database
$stmt = $pdo->prepare("SELECT * FROM medicine WHERE obat_id = ?");
$stmt->execute([$obat_id]);
$medicine = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$medicine) {
    echo "<div class='alert alert-danger'>Data obat tidak ditemukan.</div>";
    include_once __DIR__ . '/../../layouts/admin_footer.php';
    exit;
}
?>

<h1>Edit Obat / Inventaris</h1>
<hr>

<form action="/actions/admin/medicines/update.php" method="POST">
    
    <input type="hidden" name="obat_id" value="<?php echo htmlspecialchars($medicine['obat_id']); ?>">

    <div class="mb-3">
        <label for="nama_obat" class="form-label">Nama Obat/Alat</label>
        <input type="text" class="form-control" id="nama_obat" name="nama_obat" value="<?php echo htmlspecialchars($medicine['nama_obat']); ?>" required>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="kategori" class="form-label">Kategori</label>
            <select class="form-select" id="kategori" name="kategori" required>
                <option value="Antibiotik" <?php if($medicine['kategori'] == 'Antibiotik') echo 'selected'; ?>>Antibiotik</option>
                <option value="Vitamin" <?php if($medicine['kategori'] == 'Vitamin') echo 'selected'; ?>>Vitamin</option>
                <option value="Vaksin" <?php if($medicine['kategori'] == 'Vaksin') echo 'selected'; ?>>Vaksin</option>
                <option value="Anti_Parasit" <?php if($medicine['kategori'] == 'Anti_Parasit') echo 'selected'; ?>>Anti Parasit</option>
                <option value="Suplemen" <?php if($medicine['kategori'] == 'Suplemen') echo 'selected'; ?>>Suplemen</option>
                <option value="Alat_Medis" <?php if($medicine['kategori'] == 'Alat_Medis') echo 'selected'; ?>>Alat Medis</option>
                <option value="Lainnya" <?php if($medicine['kategori'] == 'Lainnya') echo 'selected'; ?>>Lainnya</option>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label for="bentuk_sediaan" class="form-label">Bentuk Sediaan</label>
            <select class="form-select" id="bentuk_sediaan" name="bentuk_sediaan">
                <option value="" <?php if(empty($medicine['bentuk_sediaan'])) echo 'selected'; ?>>-- Pilih Bentuk --</option>
                <option value="Tablet" <?php if($medicine['bentuk_sediaan'] == 'Tablet') echo 'selected'; ?>>Tablet</option>
                <option value="Kapsul" <?php if($medicine['bentuk_sediaan'] == 'Kapsul') echo 'selected'; ?>>Kapsul</option>
                <option value="Sirup" <?php if($medicine['bentuk_sediaan'] == 'Sirup') echo 'selected'; ?>>Sirup</option>
                <option value="Injeksi" <?php if($medicine['bentuk_sediaan'] == 'Injeksi') echo 'selected'; ?>>Injeksi</option>
                <option value="Salep" <?php if($medicine['bentuk_sediaan'] == 'Salep') echo 'selected'; ?>>Salep</option>
                <option value="Tetes" <?php if($medicine['bentuk_sediaan'] == 'Tetes') echo 'selected'; ?>>Tetes</option>
                <option value="Lainnya" <?php if($medicine['bentuk_sediaan'] == 'Lainnya') echo 'selected'; ?>>Lainnya</option>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="satuan" class="form-label">Satuan</label>
            <input type="text" class="form-control" id="satuan" name="satuan" value="<?php echo htmlspecialchars($medicine['satuan']); ?>" required>
        </div>
        <div class="col-md-4 mb-3">
            <label for="stok" class="form-label">Stok</label>
            <input type="number" class="form-control" id="stok" name="stok" value="<?php echo htmlspecialchars($medicine['stok']); ?>">
        </div>
        <div class="col-md-4 mb-3">
            <label for="status_tersedia" class="form-label">Status</label>
            <select class="form-select" id="status_tersedia" name="status_tersedia" required>
                <option value="1" <?php if($medicine['status_tersedia'] == 1) echo 'selected'; ?>>Tersedia</option>
                <option value="0" <?php if($medicine['status_tersedia'] == 0) echo 'selected'; ?>>Tidak Tersedia</option>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="harga_beli" class="form-label">Harga Beli (Modal)</label>
            <input type="number" class="form-control" id="harga_beli" name="harga_beli" value="<?php echo htmlspecialchars($medicine['harga_beli']); ?>" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="harga_jual" class="form-label">Harga Jual</label>
            <input type="number" class="form-control" id="harga_jual" name="harga_jual" value="<?php echo htmlspecialchars($medicine['harga_jual']); ?>" required>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="supplier" class="form-label">Supplier (Opsional)</label>
            <input type="text" class="form-control" id="supplier" name="supplier" value="<?php echo htmlspecialchars($medicine['supplier'] ?? ''); ?>">
        </div>
        <div class="col-md-6 mb-3">
            <label for="expired_date" class="form-label">Tanggal Expired (Opsional)</label>
            <input type="date" class="form-control" id="expired_date" name="expired_date" value="<?php echo htmlspecialchars($medicine['expired_date']); ?>">
        </div>
    </div>

    <div class="mb-3">
        <label for="deskripsi" class="form-label">Deskripsi (Opsional)</label>
        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"><?php echo htmlspecialchars($medicine['deskripsi'] ?? ''); ?></textarea>
    </div>
    
    <button type="submit" class="btn btn-success">Update Obat</button>
    <a href="/pages/admin/medicines/index.php" class="btn btn-secondary">Batal</a>
</form>

<?php
// Sertakan footer
include_once __DIR__ . '/../../layouts/admin_footer.php';
?>