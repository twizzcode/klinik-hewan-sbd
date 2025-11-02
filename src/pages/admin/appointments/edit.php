<?php
// Sertakan header dan koneksi
include_once __DIR__ . '/../../layouts/admin_header.php';
include_once __DIR__ . '/../../../config/database.php';

// 1. Cek apakah 'id' ada di URL
if (!isset($_GET['id'])) {
    echo "<div class='alert alert-danger'>ID Appointment tidak ditemukan.</div>";
    include_once __DIR__ . '/../../layouts/admin_footer.php';
    exit;
}

$appointment_id = $_GET['id'];
$pdo = getDBConnection();

// ==========================================================
// 1. AMBIL DATA APPOINTMENT YANG SPESIFIK
// ==========================================================
$stmt_app = $pdo->prepare("SELECT * FROM appointment WHERE appointment_id = ?");
$stmt_app->execute([$appointment_id]);
$appointment = $stmt_app->fetch(PDO::FETCH_ASSOC);

if (!$appointment) {
    echo "<div class='alert alert-danger'>Data appointment tidak ditemukan.</div>";
    include_once __DIR__ . '/../../layouts/admin_footer.php';
    exit;
}

// ==========================================================
// 2. AMBIL SEMUA DATA HEWAN (DENGAN NAMA PEMILIKNYA)
// ==========================================================
$sql_pets = "SELECT p.pet_id, p.nama_hewan, o.nama_lengkap AS nama_pemilik
             FROM pet p JOIN owner o ON p.owner_id = o.owner_id
             ORDER BY o.nama_lengkap, p.nama_hewan";
$stmt_pets = $pdo->query($sql_pets);

// ==========================================================
// 3. AMBIL SEMUA DOKTER YANG AKTIF
// ==========================================================
$sql_doctors = "SELECT dokter_id, nama_dokter FROM veterinarian WHERE status = 'Aktif' ORDER BY nama_dokter";
$stmt_doctors = $pdo->query($sql_doctors);
?>

<h1>Edit Appointment</h1>
<hr>

<form action="/actions/admin/appointments/update.php" method="POST">
    
    <input type="hidden" name="appointment_id" value="<?php echo htmlspecialchars($appointment['appointment_id']); ?>">

    <div class="mb-3">
        <label for="pet_id" class="form-label">Pasien (Hewan & Pemilik)</label>
        <select class="form-select" id="pet_id" name="pet_id" required>
            <option value="" disabled>-- Pilih Hewan --</option>
            <?php
            while ($pet = $stmt_pets->fetch(PDO::FETCH_ASSOC)) {
                // Tambahkan logic 'selected' untuk memilih hewan di appointment ini
                $selected = ($pet['pet_id'] == $appointment['pet_id']) ? 'selected' : '';
                echo "<option value='" . htmlspecialchars($pet['pet_id']) . "' $selected>" 
                   . htmlspecialchars($pet['nama_hewan']) 
                   . " (Pemilik: " . htmlspecialchars($pet['nama_pemilik']) . ")"
                   . "</option>";
            }
            ?>
        </select>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="dokter_id" class="form-label">Dokter yang Dituju</label>
            <select class="form-select" id="dokter_id" name="dokter_id" required>
                <option value="" disabled>-- Pilih Dokter --</option>
                 <?php
                while ($doctor = $stmt_doctors->fetch(PDO::FETCH_ASSOC)) {
                    // Tambahkan logic 'selected'
                    $selected = ($doctor['dokter_id'] == $appointment['dokter_id']) ? 'selected' : '';
                    echo "<option value='" . htmlspecialchars($doctor['dokter_id']) . "' $selected>" 
                       . htmlspecialchars($doctor['nama_dokter']) 
                       . "</option>";
                }
                ?>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label for="status" class="form-label">Status Janji Temu</label>
            <select class="form-select" id="status" name="status" required>
                <option value="Pending" <?php if($appointment['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                <option value="Confirmed" <?php if($appointment['status'] == 'Confirmed') echo 'selected'; ?>>Confirmed</option>
                <option value="Completed" <?php if($appointment['status'] == 'Completed') echo 'selected'; ?>>Completed</option>
                <option value="Cancelled" <?php if($appointment['status'] == 'Cancelled') echo 'selected'; ?>>Cancelled</option>
                <option value="No_Show" <?php if($appointment['status'] == 'No_Show') echo 'selected'; ?>>No Show</option>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="tanggal_appointment" class="form-label">Tanggal</label>
            <input type="date" class="form-control" id="tanggal_appointment" name="tanggal_appointment" value="<?php echo htmlspecialchars($appointment['tanggal_appointment']); ?>" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="jam_appointment" class="form-label">Jam</label>
            <input type="time" class="form-control" id="jam_appointment" name="jam_appointment" value="<?php echo htmlspecialchars($appointment['jam_appointment']); ?>" required>
        </div>
    </div>

    <div class="mb-3">
        <label for="keluhan_awal" class="form-label">Keluhan Awal</label>
        <textarea class="form-control" id="keluhan_awal" name="keluhan_awal" rows="3"><?php echo htmlspecialchars($appointment['keluhan_awal'] ?? ''); ?></textarea>
    </div>
    
    <button type="submit" class="btn btn-success">Update Appointment</button>
    <a href="/pages/admin/appointments/index.php" class="btn btn-secondary">Batal</a>
</form>

<?php
// Sertakan footer
include_once __DIR__ . '/../../layouts/admin_footer.php';
?>