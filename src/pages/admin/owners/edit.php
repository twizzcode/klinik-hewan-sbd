<?php
// Sertakan header dan koneksi
include_once __DIR__ . '/../../layouts/admin_header.php';
include_once __DIR__ . '/../../../config/database.php';

// 1. Cek apakah 'id' ada di URL
if (!isset($_GET['id'])) {
    echo "<div class='alert alert-danger'>ID Pemilik tidak ditemukan.</div>";
    include_once __DIR__ . '/../../layouts/admin_footer.php';
    exit;
}

$owner_id = $_GET['id'];
$pdo = getDBConnection();

// 2. Ambil data pemilik dari database
$stmt = $pdo->prepare("SELECT * FROM owner WHERE owner_id = ?");
$stmt->execute([$owner_id]);
$owner = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$owner) {
    echo "<div class='alert alert-danger'>Data pemilik tidak ditemukan.</div>";
    include_once __DIR__ . '/../../layouts/admin_footer.php';
    exit;
}
?>

<h1>Edit Data Pemilik</h1>
<hr>

<form action="/actions/admin/owners/update.php" method="POST">
    
    <input type="hidden" name="owner_id" value="<?php echo htmlspecialchars($owner['owner_id']); ?>">

    <div class="mb-3">
        <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
        <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="<?php echo htmlspecialchars($owner['nama_lengkap']); ?>" required>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="no_telepon" class="form-label">No. Telepon</label>
            <input type="text" class="form-control" id="no_telepon" name="no_telepon" value="<?php echo htmlspecialchars($owner['no_telepon']); ?>" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="email" class="form-label">Email (Opsional)</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($owner['email'] ?? ''); ?>">
        </div>
    </div>

    <div class="mb-3">
        <label for="alamat" class="form-label">Alamat (Opsional)</label>
        <textarea class="form-control" id="alamat" name="alamat" rows="3"><?php echo htmlspecialchars($owner['alamat'] ?? ''); ?></textarea>
    </div>

    <div class="mb-3">
        <label for="catatan" class="form-label">Catatan (Opsional)</label>
        <textarea class="form-control" id="catatan" name="catatan" rows="2"><?php echo htmlspecialchars($owner['catatan'] ?? ''); ?></textarea>
    </div>
    
    <button type="submit" class="btn btn-success">Update Pemilik</button>
    <a href="/pages/admin/owners/index.php" class="btn btn-secondary">Batal</a>
</form>

<?php
// Sertakan footer
include_once __DIR__ . '/../../layouts/admin_footer.php';
?>