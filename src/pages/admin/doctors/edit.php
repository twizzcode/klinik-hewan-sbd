<?php
// Sertakan header dan koneksi
include_once __DIR__ . '/../../layouts/admin_header.php';
include_once __DIR__ . '/../../../config/database.php';

// 1. Cek apakah 'id' ada di URL
if (!isset($_GET['id'])) {
    echo "<div class='alert alert-danger'>ID Dokter tidak ditemukan.</div>";
    include_once __DIR__ . '/../../layouts/admin_footer.php';
    exit;
}

$dokter_id = $_GET['id'];
$pdo = getDBConnection();

// 2. Ambil data dokter dari database
$stmt = $pdo->prepare("SELECT * FROM veterinarian WHERE dokter_id = ?");
$stmt->execute([$dokter_id]);
$dokter = $stmt->fetch(PDO::FETCH_ASSOC);

// Jika dokter dengan ID itu tidak ada
if (!$dokter) {
    echo "<div class='alert alert-danger'>Data dokter tidak ditemukan.</div>";
    include_once __DIR__ . '/../../layouts/admin_footer.php';
    exit;
}
?>

<h1>Edit Data Dokter</h1>
<p>Silakan ubah data di bawah ini.</p>
<hr>

<form action="/actions/admin/doctors/update.php" method="POST">
    
    <input type="hidden" name="dokter_id" value="<?php echo htmlspecialchars($dokter['dokter_id']); ?>">

    <div class="mb-3">
        <label for="nama_dokter" class="form-label">Nama Dokter</label>
        <input type="text" class="form-control" id="nama_dokter" name="nama_dokter" value="<?php echo htmlspecialchars($dokter['nama_dokter']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="no_lisensi" class="form-label">No. Lisensi</label>
        <input type="text" class="form-control" id="no_lisensi" name="no_lisensi" value="<?php echo htmlspecialchars($dokter['no_lisensi']); ?>">
    </div>
    <div class="mb-3">
        <label for="spesialisasi" class="form-label">Spesialisasi</label>
        <select class="form-select" id="spesialisasi" name="spesialisasi">
            <option value="Umum" <?php if($dokter['spesialisasi'] == 'Umum') echo 'selected'; ?>>Umum</option>
            <option value="Bedah" <?php if($dokter['spesialisasi'] == 'Bedah') echo 'selected'; ?>>Bedah</option>
            <option value="Gigi" <?php if($dokter['spesialisasi'] == 'Gigi') echo 'selected'; ?>>Gigi</option>
            <option value="Kulit" <?php if($dokter['spesialisasi'] == 'Kulit') echo 'selected'; ?>>Kulit</option>
            <option value="Kardio" <?php if($dokter['spesialisasi'] == 'Kardio') echo 'selected'; ?>>Kardio</option>
            <option value="Eksotik" <?php if($dokter['spesialisasi'] == 'Eksotik') echo 'selected'; ?>>Eksotik</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="no_telepon" class="form-label">No. Telepon</label>
        <input type="text" class="form-control" id="no_telepon" name="no_telepon" value="<?php echo htmlspecialchars($dokter['no_telepon']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($dokter['email']); ?>">
    </div>
    <div class="mb-3">
        <label for="tanggal_bergabung" class="form-label">Tanggal Bergabung</label>
        <input type="date" class="form-control" id="tanggal_bergabung" name="tanggal_bergabung" value="<?php echo htmlspecialchars($dokter['tanggal_bergabung']); ?>" required>
    </div>
    
    <button type="submit" class="btn btn-success">Update Data</button>
    <a href="/pages/admin/doctors/index.php" class="btn btn-secondary">Batal</a>
</form>

<?php
// Sertakan footer
include_once __DIR__ . '/../../layouts/admin_footer.php';
?>