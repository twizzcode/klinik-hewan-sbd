<?php
// Sertakan header dan koneksi
include_once __DIR__ . '/../../layouts/admin_header.php';
include_once __DIR__ . '/../../../config/database.php';

// 1. Cek apakah 'id' ada di URL
if (!isset($_GET['id'])) {
    echo "<div class='alert alert-danger'>ID Hewan tidak ditemukan.</div>";
    include_once __DIR__ . '/../../layouts/admin_footer.php';
    exit;
}

$pet_id = $_GET['id'];
$pdo = getDBConnection();

// 2. Ambil data hewan yang spesifik
$stmt_pet = $pdo->prepare("SELECT * FROM pet WHERE pet_id = ?");
$stmt_pet->execute([$pet_id]);
$pet = $stmt_pet->fetch(PDO::FETCH_ASSOC);

if (!$pet) {
    echo "<div class='alert alert-danger'>Data hewan tidak ditemukan.</div>";
    include_once __DIR__ . '/../../layouts/admin_footer.php';
    exit;
}

// 3. Ambil SEMUA data pemilik untuk dropdown
$stmt_owners = $pdo->query("SELECT owner_id, nama_lengkap FROM owner ORDER BY nama_lengkap ASC");
?>

<h1>Edit Data Hewan</h1>
<hr>

<form action="/actions/admin/pets/update.php" method="POST">
    
    <input type="hidden" name="pet_id" value="<?php echo htmlspecialchars($pet['pet_id']); ?>">

    <div class="mb-3">
        <label for="owner_id" class="form-label">Pemilik Hewan</label>
        <select class="form-select" id="owner_id" name="owner_id" required>
            <option value="" disabled>-- Pilih Pemilik --</option>
            <?php
            // Loop data pemilik
            while ($owner = $stmt_owners->fetch(PDO::FETCH_ASSOC)) {
                // Tambahkan logic 'selected' untuk memilih pemilik hewan ini
                $selected = ($owner['owner_id'] == $pet['owner_id']) ? 'selected' : '';
                echo "<option value='" . htmlspecialchars($owner['owner_id']) . "' $selected>" 
                   . htmlspecialchars($owner['nama_lengkap']) 
                   . "</option>";
            }
            ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="nama_hewan" class="form-label">Nama Hewan</label>
        <input type="text" class="form-control" id="nama_hewan" name="nama_hewan" value="<?php echo htmlspecialchars($pet['nama_hewan']); ?>" required>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="jenis" class="form-label">Jenis Hewan</label>
            <select class="form-select" id="jenis" name="jenis" required>
                <option value="Kucing" <?php if($pet['jenis'] == 'Kucing') echo 'selected'; ?>>Kucing</option>
                <option value="Anjing" <?php if($pet['jenis'] == 'Anjing') echo 'selected'; ?>>Anjing</option>
                <option value="Burung" <?php if($pet['jenis'] == 'Burung') echo 'selected'; ?>>Burung</option>
                <option value="Kelinci" <?php if($pet['jenis'] == 'Kelinci') echo 'selected'; ?>>Kelinci</option>
                <option value="Hamster" <?php if($pet['jenis'] == 'Hamster') echo 'selected'; ?>>Hamster</option>
                <option value="Reptil" <?php if($pet['jenis'] == 'Reptil') echo 'selected'; ?>>Reptil</option>
                <option value="Lainnya" <?php if($pet['jenis'] == 'Lainnya') echo 'selected'; ?>>Lainnya</option>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label for="ras" class="form-label">Ras (Opsional)</label>
            <input type="text" class="form-control" id="ras" name="ras" value="<?php echo htmlspecialchars($pet['ras'] ?? ''); ?>">
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
            <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                <option value="Jantan" <?php if($pet['jenis_kelamin'] == 'Jantan') echo 'selected'; ?>>Jantan</option>
                <option value="Betina" <?php if($pet['jenis_kelamin'] == 'Betina') echo 'selected'; ?>>Betina</option>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label for="tanggal_lahir" class="form-label">Tanggal Lahir (Opsional)</label>
            <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="<?php echo htmlspecialchars($pet['tanggal_lahir'] ?? ''); ?>">
        </div>
    </div>

     <div class="mb-3">
        <label for="warna" class="form-label">Warna (Opsional)</label>
        <input type="text" class="form-control" id="warna" name="warna" value="<?php echo htmlspecialchars($pet['warna'] ?? ''); ?>">
    </div>

    <div class="mb-3">
        <label for="ciri_khusus" class="form-label">Ciri Khusus (Opsional)</label>
        <textarea class="form-control" id="ciri_khusus" name="ciri_khusus" rows="2"><?php echo htmlspecialchars($pet['ciri_khusus'] ?? ''); ?></textarea>
    </div>
    
    <button type="submit" class="btn btn-success">Update Hewan</button>
    <a href="/pages/admin/pets/index.php" class="btn btn-secondary">Batal</a>
</form>

<?php
// Sertakan footer
include_once __DIR__ . '/../../layouts/admin_footer.php';
?>