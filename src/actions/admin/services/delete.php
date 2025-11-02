<?php
// Sertakan koneksi
include_once __DIR__ . '/../../../config/database.php';

// 1. Cek apakah 'id' ada di URL
if (!isset($_GET['id'])) {
    header('Location: /pages/admin/services/index.php?status=error_no_id');
    exit;
}

$layanan_id = $_GET['id'];

try {
    $pdo = getDBConnection();
    
    // 2. Siapkan query SQL DELETE
    $sql = "DELETE FROM service WHERE layanan_id = ?";
    $stmt = $pdo->prepare($sql);
    
    // 3. Eksekusi query
    $stmt->execute([$layanan_id]);

    // 4. Redirect kembali ke halaman daftar
    header("Location: /pages/admin/services/index.php?status=sukses_hapus");
    exit;

} catch (PDOException $e) {
    // PENTING: Jika layanan ini terhubung ke tabel 'appointment_layanan',
    // database akan menolak penghapusan. Ini bagus.
    
    $errorMessage = "Tidak bisa menghapus layanan. Mungkin layanan sudah terpakai di data janji temu (appointment).";
    header("Location: /pages/admin/services/index.php?status=gagal_hapus&error=" . urlencode($errorMessage));
    exit;
}
?>