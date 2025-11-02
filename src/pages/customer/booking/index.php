<?php
// 1. Panggil header (yang sudah ada 'satpam'-nya)
include_once __DIR__ . '/../layouts/header.php';
include_once __DIR__ . '/../../../config/database.php';

// 2. Ambil ID user dari Session
$owner_id = $_SESSION['user_id'];
$pdo = getDBConnection();

// ==========================================================
// 1. AMBIL HEWAN MILIK USER INI (status Aktif)
// ==========================================================
$sql_pets = "SELECT pet_id, nama_hewan 
             FROM pet 
             WHERE owner_id = ? AND status = 'Aktif'
             ORDER BY nama_hewan ASC";
$stmt_pets = $pdo->prepare($sql_pets);
$stmt_pets->execute([$owner_id]);

// ==========================================================
// 2. AMBIL SEMUA DOKTER YANG AKTIF
// ==========================================================
$sql_doctors = "SELECT dokter_id, nama_dokter, spesialisasi 
                FROM veterinarian 
                WHERE status = 'Aktif' 
                ORDER BY nama_dokter";
$stmt_doctors = $pdo->query($sql_doctors);
?>

<h1>Buat Janji Temu (Booking)</h1>
<p>Silakan pilih hewan Anda dan jadwal yang tersedia.</p>
<hr>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="/actions/customer/booking/store.php" method="POST">
    
                    <div class="mb-3">
                        <label for="pet_id" class="form-label">Hewan Peliharaan Anda</label>
                        <select class="form-select" id="pet_id" name="pet_id" required>
                            <option value="" selected disabled>-- Pilih Hewan Anda --</option>
                            <?php
                            if ($stmt_pets->rowCount() > 0) {
                                while ($pet = $stmt_pets->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option value='" . htmlspecialchars($pet['pet_id']) . "'>" 
                                       . htmlspecialchars($pet['nama_hewan']) 
                                       . "</option>";
                                }
                            } else {
                                echo "<option value='' disabled>Anda belum mendaftarkan hewan. Silakan tambah di 'Hewan Saya'.</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="dokter_id" class="form-label">Pilih Dokter</label>
                        <select class="form-select" id="dokter_id" name="dokter_id" required>
                            <option value="" selected disabled>-- Pilih Dokter --</option>
                             <?php
                            while ($doctor = $stmt_doctors->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='" . htmlspecialchars($doctor['dokter_id']) . "'>" 
                                   . htmlspecialchars($doctor['nama_dokter']) 
                                   . " (" . htmlspecialchars($doctor['spesialisasi']) . ")"
                                   . "</option>";
                            }
                            ?>
                        </select>
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
                        <textarea class="form-control" id="keluhan_awal" name="keluhan_awal" rows="3" placeholder="Jelaskan keluhan hewan Anda..."></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100" <?php if($stmt_pets->rowCount() == 0) echo 'disabled'; ?>>
                        Ajukan Janji Temu
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>


<?php
// 2. Panggil footer
include_once __DIR__ . '/../layouts/footer.php';
?>