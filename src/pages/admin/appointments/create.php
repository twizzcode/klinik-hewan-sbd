<?php
// Sertakan header
include_once __DIR__ . '/../../layouts/admin_header.php';
// Sertakan koneksi database
include_once __DIR__ . '/../../../config/database.php';

$pdo = getDBConnection();

// ==========================================================
// 1. AMBIL SEMUA DATA HEWAN (DENGAN NAMA PEMILIKNYA)
// ==========================================================
$sql_pets = "SELECT p.pet_id, p.nama_hewan, o.nama_lengkap AS nama_pemilik
             FROM pet p
             JOIN owner o ON p.owner_id = o.owner_id
             ORDER BY o.nama_lengkap, p.nama_hewan";
$stmt_pets = $pdo->query($sql_pets);

// ==========================================================
// 2. AMBIL SEMUA DOKTER YANG AKTIF
// ==========================================================
$sql_doctors = "SELECT dokter_id, nama_dokter 
                FROM veterinarian 
                WHERE status = 'Aktif' 
                ORDER BY nama_dokter";
$stmt_doctors = $pdo->query($sql_doctors);
?>

<h1>Buat Appointment Baru</h1>
<p>Form ini untuk admin/resepsionis mendaftarkan janji temu.</p>
<hr>



<form action="/actions/admin/appointments/store.php" method="POST">
    
    <div class="mb-3">
        <label for="pet_id" class="form-label">Pasien (Hewan & Pemilik)</label>
        <select class="form-select" id="pet_id" name="pet_id" required>
            <option value="" selected disabled>-- Pilih Hewan --</option>
            <?php
            // Loop data hewan dari query $stmt_pets
            while ($pet = $stmt_pets->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='" . htmlspecialchars($pet['pet_id']) . "'>" 
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
                <option value="" selected disabled>-- Pilih Dokter --</option>
                 <?php
                // Loop data dokter dari query $stmt_doctors
                while ($doctor = $stmt_doctors->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value='" . htmlspecialchars($doctor['dokter_id']) . "'>" 
                       . htmlspecialchars($doctor['nama_dokter']) 
                       . "</option>";
                }
                ?>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label for="status" class="form-label">Status Janji Temu</label>
            <select class="form-select" id="status" name="status" required>
                <option value="Confirmed" selected>Confirmed (Dikonfirmasi)</option>
                <option value="Pending">Pending</option>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="tanggal_appointment" class="form-label">Tanggal</label>
            <input type="date" class="form-control" id="tanggal_appointment" name="tanggal_appointment" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="jam_appointment" class="form-label">Jam</label>
            <input type="time" class="form-control" id="jam_appointment" name="jam_appointment" required>
        </div>
    </div>

    <div class="mb-3">
        <label for="keluhan_awal" class="form-label">Keluhan Awal</label>
        <textarea class="form-control" id="keluhan_awal" name="keluhan_awal" rows="3" placeholder="Contoh: Muntah-muntah, tidak mau makan..."></textarea>
    </div>
    
    <button type="submit" class="btn btn-primary">Simpan Appointment</button>
    <a href="/pages/admin/appointments/index.php" class="btn btn-secondary">Batal</a>
</form>

<?php
// Sertakan footer
include_once __DIR__ . '/../../layouts/admin_footer.php';
?>