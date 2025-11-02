<?php
// Sertakan koneksi
include_once __DIR__ . '/../../../config/database.php';

// 1. Cek apakah 'id' ada di URL
if (!isset($_GET['id'])) {
    header('Location: /pages/admin/doctors/index.php?status=error_no_id');
    exit;
}

$dokter_id = $_GET['id'];

try {
    $pdo = getDBConnection();
    
    // 2. Siapkan query SQL DELETE
    $sql = "DELETE FROM veterinarian WHERE dokter_id = ?";
    $stmt = $pdo->prepare($sql);
    
    // 3. Eksekusi query
    $stmt->execute([$dokter_id]);

    // 4. Redirect kembali ke halaman daftar
    header("Location: /pages/admin/doctors/index.php?status=sukses_hapus");
    exit;

} catch (PDOException $e) {
    // PENTING: Penanganan error
    // Jika dokter ini sudah terhubung dengan data di tabel lain (misal: 'appointment'),
    // database akan menolak penghapusan (karena Foreign Key Constraint).
    // Ini adalah hal yang bagus untuk integritas data.
    
    // Kirim pesan error kembali ke halaman index
    header("Location: /pages/admin/doctors/index.php?status=gagal_hapus&error=" . urlencode("Tidak bisa menghapus dokter. Mungkin dokter sudah memiliki riwayat janji temu."));
    exit;
}
?>