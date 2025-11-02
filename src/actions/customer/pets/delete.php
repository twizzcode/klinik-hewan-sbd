<?php
// 1. Mulai Session dan panggil 'satpam'
session_start();
include_once __DIR__ . '/../../../config/database.php';
include_once __DIR__ . '/../../../config/auth_check.php';

// 2. Ambil ID dari URL dan Session
if (!isset($_GET['id'])) {
    header('Location: /pages/customer/pets/index.php?status=error_no_id');
    exit;
}
$pet_id = $_GET['id'];
$owner_id = $_SESSION['user_id'];

try {
    $pdo = getDBConnection();
    
    // 3. KEAMANAN: Cek apakah hewan ini milik user yang login
    $sql_check = "SELECT pet_id FROM pet WHERE pet_id = ? AND owner_id = ?";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([$pet_id, $owner_id]);
    
    if ($stmt_check->rowCount() == 0) {
        // Jika tidak ada data (bukan miliknya)
        header("Location: /pages/customer/pets/index.php?error=aksesditolak");
        exit;
    }

    // 4. Jika aman, lakukan DELETE
    $sql_delete = "DELETE FROM pet WHERE pet_id = ? AND owner_id = ?";
    $stmt_delete = $pdo->prepare($sql_delete);
    $stmt_delete->execute([$pet_id, $owner_id]);

    // 5. Redirect kembali
    header("Location: /pages/customer/pets/index.php?status=sukses_hapus");
    exit;

} catch (PDOException $e) {
    // Tangani jika hewan masih punya data appointment/rekam medis
    $errorMessage = "Gagal menghapus hewan. Pastikan semua riwayat janji temu telah selesai.";
    header("Location: /pages/customer/pets/index.php?status=gagal_hapus&error=" . urlencode($errorMessage));
    exit;
}
?>