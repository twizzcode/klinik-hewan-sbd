<?php
// 1. Pastikan diakses melalui POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Metode tidak diizinkan!";
    header('Location: /pages/admin/appointments/index.php');
    exit;
}

// 2. Sertakan koneksi
include_once __DIR__ . '/../../../config/database.php';

// 3. Ambil data dari form
$pet_id = $_POST['pet_id'];
$dokter_id = $_POST['dokter_id'];
$tanggal_appointment = $_POST['tanggal_appointment'];
$jam_appointment = $_POST['jam_appointment'];
$status = $_POST['status'];
$keluhan_awal = $_POST['keluhan_awal'];

// 4. Validasi data wajib
if (empty($pet_id) || empty($dokter_id) || empty($tanggal_appointment) || empty($jam_appointment)) {
    header('Location: /pages/admin/appointments/create.php?error=datakosong');
    exit;
}

// 5. Handle data opsional
$keluhan_awal = empty($keluhan_awal) ? null : $keluhan_awal;

try {
    $pdo = getDBConnection();
    
    // ==========================================================
    // 6. CARI OWNER_ID BERDASARKAN PET_ID
    // ==========================================================
    $stmt_owner = $pdo->prepare("SELECT owner_id FROM pet WHERE pet_id = ?");
    $stmt_owner->execute([$pet_id]);
    $pet_data = $stmt_owner->fetch(PDO::FETCH_ASSOC);
    
    if (!$pet_data) {
        // Jika pet_id tidak ditemukan (seharusnya tidak mungkin jika formnya benar)
        header('Location: /pages/admin/appointments/create.php?error=pet_not_found');
        exit;
    }
    $owner_id = $pet_data['owner_id'];
    // ==========================================================

    // 7. Simpan ke database
    $sql = "INSERT INTO appointment 
                (pet_id, owner_id, dokter_id, tanggal_appointment, jam_appointment, status, keluhan_awal) 
            VALUES 
                (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $pdo->prepare($sql);
    
    $stmt->execute([
        $pet_id,
        $owner_id, // Disini kita masukkan owner_id yang kita temukan
        $dokter_id,
        $tanggal_appointment,
        $jam_appointment,
        $status,
        $keluhan_awal
    ]);

    // 8. Redirect kembali ke halaman daftar
    header("Location: /pages/admin/appointments/index.php?status=sukses_tambah");
    exit;

} catch (PDOException $e) {
    echo "Gagal menyimpan data: <pre>";
    print_r($e->getMessage());
    echo "</pre>";
}
?>