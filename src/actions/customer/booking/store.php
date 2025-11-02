<?php
// 1. Mulai Session dan panggil 'satpam'
session_start();
include_once __DIR__ . '/../../../config/database.php';
include_once __DIR__ . '/../../../config/auth_check.php'; // Pastikan user masih login

// 2. Pastikan diakses via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Metode tidak diizinkan!";
    exit;
}

// 3. Ambil ID Pemilik dari SESSION
$owner_id = $_SESSION['user_id'];

// 4. Ambil data dari form
$pet_id = $_POST['pet_id'];
$dokter_id = $_POST['dokter_id'];
$tanggal_appointment = $_POST['tanggal_appointment'];
$jam_appointment = $_POST['jam_appointment'];
$keluhan_awal = $_POST['keluhan_awal'];
$status = 'Pending'; // <-- PENTING: Booking dari pelanggan selalu 'Pending'

// 5. Validasi data wajib
if (empty($pet_id) || empty($dokter_id) || empty($tanggal_appointment) || empty($jam_appointment)) {
    header('Location: /pages/customer/booking/index.php?error=datakosong');
    exit;
}

// 6. Handle data opsional
$keluhan_awal = empty($keluhan_awal) ? null : $keluhan_awal;

try {
    $pdo = getDBConnection();
    
    // 7. Simpan ke database
    $sql = "INSERT INTO appointment 
                (pet_id, owner_id, dokter_id, tanggal_appointment, jam_appointment, status, keluhan_awal) 
            VALUES 
                (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $pdo->prepare($sql);
    
    $stmt->execute([
        $pet_id,
        $owner_id, // Ambil dari SESSION
        $dokter_id,
        $tanggal_appointment,
        $jam_appointment,
        $status, // Hardcode 'Pending'
        $keluhan_awal
    ]);

    // 8. Redirect kembali ke halaman dashboard dengan pesan sukses
    header("Location: /pages/customer/dashboard.php?status=sukses_booking");
    exit;

} catch (PDOException $e) {
    echo "Gagal menyimpan data: <pre>";
    print_r($e->getMessage());
    echo "</pre>";
}
?>