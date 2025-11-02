<?php
// 1. Mulai Session dan panggil 'satpam'
session_start();
include_once __DIR__ . '/../../config/database.php';
include_once __DIR__ . '/../../config/doctor_auth_check.php';

// 2. Pastikan diakses via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Metode tidak diizinkan!";
    exit;
}

// 3. Ambil data dari form
$appointment_id = $_POST['appointment_id'];
$pet_id = $_POST['pet_id'];
$dokter_id = $_SESSION['user_id'];
$keluhan = $_POST['keluhan'];
$diagnosa = $_POST['diagnosa'];
$tindakan = $_POST['tindakan'];
$catatan_dokter = $_POST['catatan_dokter'];
$berat_badan = $_POST['berat_badan_saat_periksa'];
$suhu_tubuh = $_POST['suhu_tubuh'];

// (BARU) Ambil array layanan_ids. Jika tidak ada, default ke array kosong
$layanan_ids = $_POST['layanan_ids'] ?? [];

// 4. Validasi
if (empty($appointment_id) || empty($pet_id) || empty($keluhan) || empty($diagnosa)) {
    header('Location: /pages/doctor/medical_record.php?appointment_id=' . $appointment_id . '&error=datakosong');
    exit;
}

// 5. Handle data opsional (TETAP SAMA)
$tindakan = empty($tindakan) ? null : $tindakan;
$catatan_dokter = empty($catatan_dokter) ? null : $catatan_dokter;
$berat_badan = empty($berat_badan) ? null : $berat_badan;
$suhu_tubuh = empty($suhu_tubuh) ? null : $suhu_tubuh;

// 6. Gunakan Transaksi Database
$pdo = getDBConnection();
try {
    $pdo->beginTransaction(); // Mulai Transaksi

    // ===========================================
    // Query 1: INSERT ke medical_record (TETAP SAMA)
    // ===========================================
    $sql_insert_record = "INSERT INTO medical_record 
                    (pet_id, dokter_id, appointment_id, keluhan, diagnosa, tindakan, catatan_dokter, berat_badan_saat_periksa, suhu_tubuh, status_kunjungan) 
                   VALUES 
                    (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pemeriksaan')";
    
    $stmt_insert_record = $pdo->prepare($sql_insert_record);
    $stmt_insert_record->execute([
        $pet_id, $dokter_id, $appointment_id,
        $keluhan, $diagnosa, $tindakan, $catatan_dokter,
        $berat_badan, $suhu_tubuh
    ]);

    // Ambil ID rekam medis yg baru disimpan
    $rekam_id = $pdo->lastInsertId();

    // ===========================================
    // (BARU) Query 2: Loop dan INSERT ke appointment_layanan
    // ===========================================
    if (!empty($layanan_ids)) {
        // Siapkan query untuk mengambil harga
        $sql_get_price = "SELECT harga FROM service WHERE layanan_id = ?";
        $stmt_get_price = $pdo->prepare($sql_get_price);
        
        // Siapkan query untuk insert
        $sql_insert_service = "INSERT INTO appointment_layanan 
                                (appointment_id, layanan_id, jumlah, harga_satuan, subtotal) 
                               VALUES (?, ?, 1, ?, ?)";
        $stmt_insert_service = $pdo->prepare($sql_insert_service);

        foreach ($layanan_ids as $layanan_id) {
            // 1. Ambil harga layanan
            $stmt_get_price->execute([$layanan_id]);
            $service = $stmt_get_price->fetch(PDO::FETCH_ASSOC);
            $harga_satuan = $service['harga'] ?? 0;
            
            // 2. Insert ke tabel (jumlah default 1, subtotal = harga)
            $stmt_insert_service->execute([
                $appointment_id,
                $layanan_id,
                $harga_satuan,
                $harga_satuan // Subtotal (karena jumlah=1)
            ]);
        }
    }

    // ===========================================
    // Query 3: UPDATE status appointment (TETAP SAMA)
    // ===========================================
    $sql_update_app = "UPDATE appointment 
                       SET status = 'Completed' 
                       WHERE appointment_id = ? AND dokter_id = ?";
    
    $stmt_update_app = $pdo->prepare($sql_update_app);
    $stmt_update_app->execute([$appointment_id, $dokter_id]);
    
    // Jika semua berhasil
    $pdo->commit(); // Selesaikan Transaksi

    // 7. Redirect ke halaman resep (TETAP SAMA)
    header("Location: /pages/doctor/prescription.php?rekam_id=" . $rekam_id);
    exit;

} catch (Exception $e) { // Ganti PDOException ke Exception agar bisa 'throw'
    // Jika ada yang gagal
    $pdo->rollBack(); // Batalkan semua perubahan
    echo "Gagal menyimpan data: <pre>";
    print_r($e->getMessage());
    echo "</pre>";
}
?>