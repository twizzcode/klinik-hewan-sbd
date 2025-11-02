<?php
// 1. Panggil header (yang sudah ada 'satpam'-nya)
include_once __DIR__ . '/layouts/header.php';
include_once __DIR__ . '/../../config/database.php';

// 2. Ambil ID Appointment dari URL
if (!isset($_GET['appointment_id'])) {
    echo "<div class='alert alert-danger'>ID Appointment tidak ditemukan.</div>";
    include_once __DIR__ . '/layouts/footer.php';
    exit;
}

$appointment_id = $_GET['appointment_id'];
$dokter_id = $_SESSION['user_id']; // ID Dokter yang sedang login

$pdo = getDBConnection();

// ==========================================================
// 3. AMBIL DATA PASIEN (JOIN 3 TABEL)
// ==========================================================
$sql = "SELECT 
            a.pet_id, a.keluhan_awal, a.status,
            p.nama_hewan, p.jenis, p.ras, p.jenis_kelamin, p.tanggal_lahir,
            o.nama_lengkap AS nama_pemilik, o.no_telepon AS telp_pemilik
        FROM 
            appointment a
        JOIN 
            pet p ON a.pet_id = p.pet_id
        JOIN 
            owner o ON a.owner_id = o.owner_id
        WHERE 
            a.appointment_id = ? 
            AND a.dokter_id = ?
        ";

$stmt = $pdo->prepare($sql);
$stmt->execute([$appointment_id, $dokter_id]);
$pasien = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pasien) {
    echo "<div class='alert alert-danger'>Data janji temu tidak ditemukan atau bukan milik Anda.</div>";
    include_once __DIR__ . '/layouts/footer.php';
    exit;
}

// ==========================================================
// 4. (BARU) AMBIL DAFTAR LAYANAN DARI 'service'
// ==========================================================
$stmt_services = $pdo->query("SELECT layanan_id, nama_layanan, harga 
                             FROM service 
                             WHERE status_tersedia = 1 
                             ORDER BY nama_layanan ASC");
?>

<div class="row g-4">
    <div class="col-md-4">
        <h3>Info Pasien</h3>
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><?php echo htmlspecialchars($pasien['nama_hewan']); ?></h5>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Jenis:</strong> <?php echo htmlspecialchars($pasien['jenis']); ?></li>
                <li class="list-group-item"><strong>Ras:</strong> <?php echo htmlspecialchars($pasien['ras'] ?? 'N/A'); ?></li>
                <li class="list-group-item"><strong>Kelamin:</strong> <?php echo htmlspecialchars($pasien['jenis_kelamin']); ?></li>
                <li class="list-group-item"><strong>Tgl Lahir:</strong> <?php echo htmlspecialchars($pasien['tanggal_lahir'] ?? 'N/A'); ?></li>
            </ul>
        </div>
        
        <h3 class="mt-4">Info Pemilik</h3>
        <div class="card">
             <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Nama:</strong> <?php echo htmlspecialchars($pasien['nama_pemilik']); ?></li>
                <li class="list-group-item"><strong>Telepon:</strong> <?php echo htmlspecialchars($pasien['telp_pemilik']); ?></li>
            </ul>
        </div>
        <h3 class="mt-4">Keluhan Awal</h3>
        <div class="card">
             <div class="card-body">
                <p class="card-text"><?php echo htmlspecialchars($pasien['keluhan_awal'] ?? 'Tidak ada keluhan awal.'); ?></p>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <h3>Formulir Rekam Medis</h3>
        
        <?php if ($pasien['status'] == 'Completed'): ?>
            <div class="alert alert-success">Rekam medis untuk kunjungan ini sudah disimpan.</div>
        <?php else: ?>
            <div class="card">
                <div class="card-body">
                    <form action="/actions/doctor/store_record.php" method="POST">
                        <input type="hidden" name="appointment_id" value="<?php echo $appointment_id; ?>">
                        <input type="hidden" name="pet_id" value="<?php echo htmlspecialchars($pasien['pet_id']); ?>">
                        
                        <div class="mb-3">
                            <label for="keluhan" class="form-label">Keluhan (Hasil Anamnesa)</label>
                            <textarea class="form-control" id="keluhan" name="keluhan" rows="3" required><?php echo htmlspecialchars($pasien['keluhan_awal'] ?? ''); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="diagnosa" class="form-label">Diagnosa</label>
                            <textarea class="form-control" id="diagnosa" name="diagnosa" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="tindakan" class="form-label">Tindakan</label>
                            <textarea class="form-control" id="tindakan" name="tindakan" rows="3" placeholder="Contoh: Injeksi vitamin, pembersihan luka..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="catatan_dokter" class="form-label">Catatan Dokter (Resep, dll)</label>
                            <textarea class="form-control" id="catatan_dokter" name="catatan_dokter" rows="3" placeholder="Resep obat, saran perawatan di rumah..."></textarea>
                        </div>
                        <div class="row">
                             <div class="col-md-6 mb-3">
                                <label for="berat_badan_saat_periksa" class="form-label">Berat Badan (Kg)</label>
                                <input type="number" step="0.01" class="form-control" id="berat_badan_saat_periksa" name="berat_badan_saat_periksa">
                            </div>
                             <div class="col-md-6 mb-3">
                                <label for="suhu_tubuh" class="form-label">Suhu Tubuh (°C)</label>
                                <input type="number" step="0.1" class="form-control" id="suhu_tubuh" name="suhu_tubuh">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Layanan / Jasa yang Diberikan</label>
                            <div class="card p-2" style="max-height: 200px; overflow-y: auto;">
                                <?php
                                while ($service = $stmt_services->fetch(PDO::FETCH_ASSOC)) {
                                    $service_id = htmlspecialchars($service['layanan_id']);
                                    $service_name = htmlspecialchars($service['nama_layanan']);
                                    $service_price = htmlspecialchars($service['harga']);
                                    
                                    echo "<div class='form-check'>";
                                    // Perhatikan nama: 'layanan_ids[]'. Ini akan menjadi array di $_POST
                                    echo "<input class='form-check-input' type='checkbox' name='layanan_ids[]' value='$service_id' id='service_$service_id'>";
                                    echo "<label class='form-check-label' for='service_$service_id'>";
                                    echo "$service_name (Rp " . number_format($service_price, 0, ',', '.') . ")";
                                    echo "</label>";
                                    echo "</div>";
                                }
                                ?>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Simpan Rekam Medis & Lanjut ke Resep</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
// 4. Panggil footer
include_once __DIR__ . '/layouts/footer.php';
?>