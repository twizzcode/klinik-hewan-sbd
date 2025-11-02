<?php
// Sertakan header dan koneksi
include_once __DIR__ . '/../../layouts/admin_header.php';
include_once __DIR__ . '/../../../config/database.php';

// 1. Cek apakah 'id' ada di URL
if (!isset($_GET['id'])) {
    echo "<div class='alert alert-danger'>ID Layanan tidak ditemukan.</div>";
    include_once __DIR__ . '/../../layouts/admin_footer.php';
    exit;
}

$layanan_id = $_GET['id'];
$pdo = getDBConnection();

// 2. Ambil data layanan dari database
$stmt = $pdo->prepare("SELECT * FROM service WHERE layanan_id = ?");
$stmt->execute([$layanan_id]);
$service = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$service) {
    echo "<div class='alert alert-danger'>Data layanan tidak ditemukan.</div>";
    include_once __DIR__ . '/../../layouts/admin_footer.php';
    exit;
}
?>

<h1>Edit Layanan</h1>
<hr>

<form action="/actions/admin/services/update.php" method="POST">
    
    <input type="hidden" name="layanan_id" value="<?php echo htmlspecialchars($service['layanan_id']); ?>">

    <div class="mb-3">
        <label for="nama_layanan" class="form-label">Nama Layanan</label>
        <input type="text" class="form-control" id="nama_layanan" name="nama_layanan" value="<?php echo htmlspecialchars($service['nama_layanan']); ?>" required>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="kategori" class="form-label">Kategori</label>
            <select class="form-select" id="kategori" name="kategori" required>
                <option value="Pemeriksaan" <?php if($service['kategori'] == 'Pemeriksaan') echo 'selected'; ?>>Pemeriksaan</option>
                <option value="Vaksinasi" <?php if($service['kategori'] == 'Vaksinasi') echo 'selected'; ?>>Vaksinasi</option>
                <option value="Grooming" <?php if($service['kategori'] == 'Grooming') echo 'selected'; ?>>Grooming</option>
                <option value="Bedah" <?php if($service['kategori'] == 'Bedah') echo 'selected'; ?>>Bedah</option>
                <option value="Rawat_Inap" <?php if($service['kategori'] == 'Rawat_Inap') echo 'selected'; ?>>Rawat Inap</option>
                <option value="Tes_Lab" <?php if($service['kategori'] == 'Tes_Lab') echo 'selected'; ?>>Tes Lab</option>
                <option value="Emergency" <?php if($service['kategori'] == 'Emergency') echo 'selected'; ?>>Emergency</option>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label for="harga" class="form-label">Harga (Rp)</label>
            <input type="number" class="form-control" id="harga" name="harga" value="<?php echo htmlspecialchars($service['harga']); ?>" required>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="durasi_estimasi" class="form-label">Durasi Estimasi (Menit)</label>
            <input type="number" class="form-control" id="durasi_estimasi" name="durasi_estimasi" value="<?php echo htmlspecialchars($service['durasi_estimasi']); ?>">
        </div>
        <div class="col-md-6 mb-3">
            <label for="status_tersedia" class="form-label">Status</label>
            <select class="form-select" id="status_tersedia" name="status_tersedia" required>
                <option value="1" <?php if($service['status_tersedia'] == 1) echo 'selected'; ?>>Tersedia</option>
                <option value="0" <?php if($service['status_tersedia'] == 0) echo 'selected'; ?>>Tidak Tersedia</option>
            </select>
        </div>
    </div>

    <div class="mb-3">
        <label for="deskripsi" class="form-label">Deskripsi (Opsional)</label>
        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"><?php echo htmlspecialchars($service['deskripsi']); ?></textarea>
    </div>
    
    <button type="submit" class="btn btn-success">Update Layanan</button>
    <a href="/pages/admin/services/index.php" class="btn btn-secondary">Batal</a>
</form>

<?php
// Sertakan footer
include_once __DIR__ . '/../../layouts/admin_footer.php';
?>