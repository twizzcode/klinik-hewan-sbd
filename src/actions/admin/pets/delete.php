<?php
// Sertakan koneksi
include_once __DIR__ . '/../../../config/database.php';

// 1. Cek apakah 'id' ada di URL
if (!isset($_GET['id'])) {
    header('Location: /pages/admin/pets/index.php?status=error_no_id');
    exit;
}

$pet_id = $_GET['id'];

try {
    $pdo = getDBConnection();
    
    // 2. Siapkan query SQL DELETE
    $sql = "DELETE FROM pet WHERE pet_id = ?";
    $stmt = $pdo->prepare($sql);
    
    // 3. Eksekusi query
    $stmt->execute([$pet_id]);

    // 4. Redirect kembali ke halaman daftar
    header("Location: /pages/admin/pets/index.php?status=sukses_hapus");
    exit;

} catch (PDOException $e) {
    // PENTING: Jika hewan ini terhubung ke 'appointment', 'medical_record', dll.
    // database akan menolak penghapusan. Ini bagus.
    
    $errorMessage = "Tidak bisa menghapus hewan. Pastikan semua riwayat janji temu dan rekam medis hewan ini telah dihapus lebih dulu.";
    header("Location: /pages/admin/pets/index.php?status=gagal_hapus&error=" . urlencode($errorMessage));
    exit;
}
?>