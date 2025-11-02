<?php
// 1. Sertakan header admin (untuk satpam)
// Kita panggil auth check untuk memastikan kasir (admin/dokter) masih login
include_once __DIR__ . '/../../../config/doctor_auth_check.php';
include_once __DIR__ . '/../../../config/database.php';

// 2. Pastikan diakses via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Metode tidak diizinkan!";
    exit;
}

// 3. Ambil data dari form
$appointment_id = $_POST['appointment_id'];
$total_tagihan = $_POST['total_tagihan']; // (Bisa kita simpan nanti jika ada tabel 'transactions')

// 4. Validasi
if (empty($appointment_id)) {
    header('Location: /pages/admin/billing/index.php?error=ID tidak ditemukan');
    exit;
}

$pdo = getDBConnection();
try {
    $pdo->beginTransaction();

    // ===========================================
    // Query 1: UPDATE status appointment menjadi 'Paid'
    // ===========================================
    $sql_update = "UPDATE appointment 
                   SET status = 'Paid' 
                   WHERE appointment_id = ?";
    
    $stmt_update = $pdo->prepare($sql_update);
    $stmt_update->execute([$appointment_id]);

    // ===========================================
    // (Opsional - tapi SANGAT DISARANKAN)
    // Nanti kita bisa INSERT ke tabel 'transactions' di sini
    // ===========================================
    
    // Jika semua berhasil
    $pdo->commit();

    // 7. Redirect kembali ke halaman antrian kasir
    header("Location: /pages/admin/billing/index.php?status=sukses_bayar");
    exit;

} catch (PDOException $e) {
    $pdo->rollBack();
    echo "Gagal memproses pembayaran: <pre>";
    print_r($e->getMessage());
    echo "</pre>";
}
?>