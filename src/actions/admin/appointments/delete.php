<?php
// Sertakan koneksi
include_once __DIR__ . '/../../../config/database.php';

// 1. Cek apakah 'id' ada di URL
if (!isset($_GET['id'])) {
    header('Location: /pages/admin/appointments/index.php?status=error_no_id');
    exit;
}

$appointment_id = $_GET['id'];

try {
    $pdo = getDBConnection();
    
    // 2. Siapkan query SQL DELETE
    $sql = "DELETE FROM appointment WHERE appointment_id = ?";
    $stmt = $pdo->prepare($sql);
    
    // 3. Eksekusi query
    $stmt->execute([$appointment_id]);

    // 4. Redirect kembali ke halaman daftar
    header("Location: /pages/admin/appointments/index.php?status=sukses_hapus");
    exit;

} catch (PDOException $e) {
    // PENTING: Jika appointment ini terhubung ke 'medical_record' atau 'appointment_layanan'
    // database akan menolak penghapusan. Ini bagus.
    
    $errorMessage = "Tidak bisa menghapus. Janji temu ini mungkin sudah memiliki rekam medis atau tagihan.";
    header("Location: /pages/admin/appointments/index.php?status=gagal_hapus&error=" . urlencode($errorMessage));
    exit;
}
?>