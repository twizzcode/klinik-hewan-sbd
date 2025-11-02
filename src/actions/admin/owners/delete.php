<?php
// Sertakan koneksi
include_once __DIR__ . '/../../../config/database.php';

// 1. Cek apakah 'id' ada di URL
if (!isset($_GET['id'])) {
    header('Location: /pages/admin/owners/index.php?status=error_no_id');
    exit;
}

$owner_id = $_GET['id'];

try {
    $pdo = getDBConnection();
    
    // 2. Siapkan query SQL DELETE
    $sql = "DELETE FROM owner WHERE owner_id = ?";
    $stmt = $pdo->prepare($sql);
    
    // 3. Eksekusi query
    $stmt->execute([$owner_id]);

    // 4. Redirect kembali ke halaman daftar
    header("Location: /pages/admin/owners/index.php?status=sukses_hapus");
    exit;

} catch (PDOException $e) {
    // Ini adalah error yang paling mungkin:
    // "Cannot delete or update a parent row: a foreign key constraint fails"
    
    $errorMessage = "Tidak bisa menghapus pemilik. Pastikan semua data hewan peliharaan (pet) dan janji temu (appointment) yang terkait dengan pemilik ini telah dihapus lebih dulu.";
    header("Location: /pages/admin/owners/index.php?status=gagal_hapus&error=" . urlencode($errorMessage));
    exit;
}
?>