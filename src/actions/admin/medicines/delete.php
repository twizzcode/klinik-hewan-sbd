<?php
// Sertakan koneksi
include_once __DIR__ . '/../../../config/database.php';

// 1. Cek apakah 'id' ada di URL
if (!isset($_GET['id'])) {
    header('Location: /pages/admin/medicines/index.php?status=error_no_id');
    exit;
}

$obat_id = $_GET['id'];

try {
    $pdo = getDBConnection();
    
    // 2. Siapkan query SQL DELETE
    $sql = "DELETE FROM medicine WHERE obat_id = ?";
    $stmt = $pdo->prepare($sql);
    
    // 3. Eksekusi query
    $stmt->execute([$obat_id]);

    // 4. Redirect kembali ke halaman daftar
    header("Location: /pages/admin/medicines/index.php?status=sukses_hapus");
    exit;

} catch (PDOException $e) {
    // PENTING: Jika obat ini sudah terpakai di 'resep',
    // database akan menolak penghapusan. Ini bagus.
    
    $errorMessage = "Tidak bisa menghapus obat. Mungkin obat sudah terpakai di data resep.";
    header("Location: /pages/admin/medicines/index.php?status=gagal_hapus&error=" . urlencode($errorMessage));
    exit;
}
?>