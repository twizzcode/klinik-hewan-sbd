<?php
// 1. Pastikan diakses via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Metode tidak diizinkan!";
    header('Location: /pages/admin/appointments/index.php');
    exit;
}

// 2. Sertakan koneksi
include_once __DIR__ . '/../../../config/database.php';

// 3. Ambil data dari form, TERMASUK 'appointment_id'
$appointment_id = $_POST['appointment_id'];
$pet_id = $_POST['pet_id'];
$dokter_id = $_POST['dokter_id'];
$tanggal_appointment = $_POST['tanggal_appointment'];
$jam_appointment = $_POST['jam_appointment'];
$status = $_POST['status'];
$keluhan_awal = $_POST['keluhan_awal'];

// 4. Validasi
if (empty($appointment_id) || empty($pet_id) || empty($dokter_id) || empty($tanggal_appointment) || empty($jam_appointment)) {
    header('Location: /pages/admin/appointments/edit.php?id=' . $appointment_id . '&error=datakosong');
    exit;
}

// 5. Handle data opsional
$keluhan_awal = empty($keluhan_awal) ? null : $keluhan_awal;

try {
    $pdo = getDBConnection();
    
    // ==========================================================
    // 6. CARI LAGI OWNER_ID BERDASARKAN PET_ID (jika pet diganti)
    // ==========================================================
    $stmt_owner = $pdo->prepare("SELECT owner_id FROM pet WHERE pet_id = ?");
    $stmt_owner->execute([$pet_id]);
    $pet_data = $stmt_owner->fetch(PDO::FETCH_ASSOC);
    
    if (!$pet_data) {
        header('Location: /pages/admin/appointments/edit.php?id=' . $appointment_id . '&error=pet_not_found');
        exit;
    }
    $owner_id = $pet_data['owner_id'];
    // ==========================================================

    // 7. Coba update ke database
    $sql = "UPDATE appointment SET 
                pet_id = ?, 
                owner_id = ?, 
                dokter_id = ?, 
                tanggal_appointment = ?, 
                jam_appointment = ?, 
                status = ?, 
                keluhan_awal = ?
            WHERE appointment_id = ?";
    
    $stmt = $pdo->prepare($sql);
    
    $stmt->execute([
        $pet_id,
        $owner_id,
        $dokter_id,
        $tanggal_appointment,
        $jam_appointment,
        $status,
        $keluhan_awal,
        $appointment_id // 'appointment_id' terakhir untuk WHERE
    ]);

    // 8. Redirect kembali ke halaman daftar
    header("Location: /pages/admin/appointments/index.php?status=sukses_update");
    exit;

} catch (PDOException $e) {
    echo "Gagal mengupdate data: <pre>";
    print_r($e->getMessage());
    echo "</pre>";
}
?>